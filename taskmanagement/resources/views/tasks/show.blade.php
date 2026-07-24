@extends('layouts.app')

@section('title', $task->title)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary btn-sm me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h2 class="mb-0"><i class="bi bi-clipboard-check me-2"></i>Task Details</h2>
        </div>

        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0">{{ $task->title }}</h5>
                @if($task->isOverdue())
                    <span class="badge bg-danger">Overdue</span>
                @endif
            </div>
            <div class="card-body p-4">
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="text-muted small mb-1">Status</div>
                        <span class="{{ $task->getStatusBadgeClass() }} fs-6">
                            {{ \App\Models\Task::statuses()[$task->status] }}
                        </span>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small mb-1">Priority</div>
                        <span class="{{ $task->getPriorityBadgeClass() }} fs-6">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small mb-1">Due Date</div>
                        <span class="{{ $task->isOverdue() ? 'text-danger fw-bold' : '' }}">
                            {{ $task->due_date ? $task->due_date->format('F d, Y') : 'No due date' }}
                        </span>
                    </div>
                </div>

                @if($task->description)
                    <div class="mb-4">
                        <div class="text-muted small mb-1">Description</div>
                        <p class="mb-0">{{ $task->description }}</p>
                    </div>
                @endif

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="text-muted small mb-1">Created</div>
                        <span>{{ $task->created_at->format('F d, Y \a\t H:i') }}</span>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small mb-1">Last Updated</div>
                        <span>{{ $task->updated_at->format('F d, Y \a\t H:i') }}</span>
                    </div>
                </div>

                {{-- Quick Status Update --}}
                <div class="border-top pt-3">
                    <div class="text-muted small mb-2">Quick Status Update</div>
                    <div class="d-flex gap-2 flex-wrap">
                        @foreach(\App\Models\Task::statuses() as $value => $label)
                            <form method="POST" action="{{ route('tasks.updateStatus', $task) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="{{ $value }}">
                                <button type="submit"
                                        class="btn btn-sm {{ $task->status === $value ? 'btn-dark' : 'btn-outline-secondary' }}"
                                        {{ $task->status === $value ? 'disabled' : '' }}>
                                    {{ $label }}
                                </button>
                            </form>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <form method="POST" action="{{ route('tasks.destroy', $task) }}"
                      onsubmit="return confirm('Delete this task permanently?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-trash me-1"></i>Delete
                    </button>
                </form>
                <a href="{{ route('tasks.edit', $task) }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-pencil me-1"></i>Edit Task
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
