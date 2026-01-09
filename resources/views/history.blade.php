<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Task History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="card shadow-sm border-0 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="text-secondary"><i class="fas fa-history me-2"></i> ប្រវត្តិកិច្ចការងារចាស់ៗ</h4>
            <a href="{{ route('index') }}" class="btn btn-primary btn-sm">ត្រឡប់ទៅទំព័រដើម</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>កាលបរិច្ឆេទ</th>
                        <th>ឈ្មោះកិច្ចការ</th>
                        <th>ស្ថានភាព</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($historyTasks as $task)
                    <tr>
                        <td>{{ $task->created_at->format('d-M-Y') }}</td>
                        <td>{{ $task->title }}</td>
                        <td>
                            @if($task->is_completed)
                                <span class="badge bg-success">រួចរាល់</span>
                            @else
                                <span class="badge bg-warning text-dark">មិនទាន់រួចរាល់</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">មិនទាន់មានប្រវត្តិការងារនៅឡើយទេ</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>