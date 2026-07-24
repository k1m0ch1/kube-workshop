<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Task Management') - TaskManager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .navbar-brand { font-weight: 700; font-size: 1.4rem; }
        .task-card { transition: transform 0.2s, box-shadow 0.2s; }
        .task-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .stat-card { border-left: 4px solid; }
        .stat-card.total   { border-color: #6c757d; }
        .stat-card.pending { border-color: #ffc107; }
        .stat-card.in-progress { border-color: #0d6efd; }
        .stat-card.completed   { border-color: #198754; }
        .overdue { background-color: #fff5f5 !important; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('tasks.index') }}">
                <i class="bi bi-check2-square me-2"></i>TaskManager
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ route('tasks.index') }}">
                    <i class="bi bi-list-task me-1"></i>All Tasks
                </a>
                <a class="nav-link" href="{{ route('tasks.create') }}">
                    <i class="bi bi-plus-circle me-1"></i>New Task
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <footer class="text-center text-muted py-4 border-top mt-4">
        <small>TaskManager &copy; {{ date('Y') }} &mdash; Built with Laravel 11</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
