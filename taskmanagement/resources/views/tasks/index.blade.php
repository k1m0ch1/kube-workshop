@extends('layouts.app')

@section('title', 'All Tasks')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-list-task me-2"></i>Task Management</h2>
    <a href="{{ route('tasks.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>New Task
    </a>
</div>

{{-- Stats Row --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card stat-card total h-100">
            <div class="card-body text-center">
                <div class="fs-1 fw-bold text-secondary">{{ $stats['total'] }}</div>
                <small class="text-muted">Total Tasks</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card pending h-100">
            <div class="card-body text-center">
                <div class="fs-1 fw-bold text-warning">{{ $stats['pending'] }}</div>
                <small class="text-muted">Pending</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card in-progress h-100">
            <div class="card-body text-center">
                <div class="fs-1 fw-bold text-primary">{{ $stats['in_progress'] }}</div>
                <small class="text-muted">In Progress</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card completed h-100">
            <div class="card-body text-center">
                <div class="fs-1 fw-bold text-success">{{ $stats['completed'] }}</div>
                <small class="text-muted">Completed</small>
            </div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <form method="GET" action="{{ route('tasks.index') }}" class="row g-2">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search tasks..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    @foreach(\App\Models\Task::statuses() as $value => $label)
                        <option value="{{ $value }}" @selected(request('status') == $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="priority" class="form-select">
                    <option value="">All Priorities</option>
                    @foreach(\App\Models\Task::priorities() as $value => $label)
                        <option value="{{ $value }}" @selected(request('priority') == $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">
                    <i class="bi bi-funnel me-1"></i>Filter
                </button>
            </div>
        </form>
        @if(request()->hasAny(['search', 'status', 'priority']))
            <div class="mt-2">
                <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-x me-1"></i>Clear Filters
                </a>
            </div>
        @endif
    </div>
</div>

{{-- Tasks Table --}}
@if($tasks->isEmpty())
    <div class="card shadow-sm">
        <div class="card-body text-center py-5">
            <i class="bi bi-inbox display-4 text-muted"></i>
            <p class="mt-3 text-muted">No tasks found. <a href="{{ route('tasks.create') }}">Create your first task!</a></p>
        </div>
    </div>
@else
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Due Date</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tasks as $task)
                    <tr class="{{ $task->isOverdue() ? 'overdue' : '' }}">
                        <td class="text-muted small">{{ $task->id }}</td>
                        <td>
                            <a href="{{ route('tasks.show', $task) }}" class="text-decoration-none fw-semibold text-dark">
                                {{ $task->title }}
                            </a>
                            @if($task->isOverdue())
                                <span class="badge bg-danger ms-1">Overdue</span>
                            @endif
                            @if($task->description)
                                <div class="text-muted small text-truncate" style="max-width:300px;">
                                    {{ $task->description }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <span class="{{ $task->getStatusBadgeClass() }}">
                                {{ \App\Models\Task::statuses()[$task->status] }}
                            </span>
                        </td>
                        <td>
                            <span class="{{ $task->getPriorityBadgeClass() }}">
                                {{ ucfirst($task->priority) }}
                            </span>
                        </td>
                        <td class="small {{ $task->isOverdue() ? 'text-danger fw-bold' : 'text-muted' }}">
                            {{ $task->due_date ? $task->due_date->format('M d, Y') : '—' }}
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('tasks.show', $task) }}" class="btn btn-outline-secondary" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('tasks.edit', $task) }}" class="btn btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('tasks.destroy', $task) }}"
                                      onsubmit="return confirm('Delete this task?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3 d-flex justify-content-between align-items-center">
        <small class="text-muted">
            Showing {{ $tasks->firstItem() }}–{{ $tasks->lastItem() }} of {{ $tasks->total() }} tasks
        </small>
        {{ $tasks->links() }}
    </div>
@endif
@endsection
