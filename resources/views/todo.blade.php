<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Todo List</title>
    <link rel="icon" type="image/png" href="{{ asset('todolist.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .completed { text-decoration: line-through; color: #6c757d; }
        .card { border: none; border-radius: 15px; }
        .list-group-item { border: none; border-bottom: 1px solid #eee; margin-bottom: 5px; }
    </style>
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-lg p-4">
                <h3 class="text-primary mb-4"><i class="fas fa-tasks me-2"></i> My Daily Tasks</h3>
                <p class=" text-primary">Made By Sophea </p>

                <form action="{{ route('create') }}" method="POST" class="input-group mb-4">
                    @csrf
                    <input type="text" name="title" class="form-control form-control-lg" placeholder="What needs to be done?" required>
                    <button class="btn btn-primary px-4" type="submit">Add Task</button>
                </form>

                <ul class="list-group">
                    @forelse($tasks as $task)
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-white rounded shadow-sm px-3">
                            <div class="d-flex align-items-center">

                                <form id="toggle-form-{{ $task->id}}" action="{{ route('update', $task->id) }}" method="POST" class="me-3">
                                    @csrf 
                                    @method('PUT')
                                    <input type="hidden" name="action" value="toggle_status">

                                    <button type="submit" class="btn btn-link p-0 text-decoration-none"
                                        onclick="document.getElementById('toggle-form-{{ $task->id }}').submit();">
                                        
                                        @if($task->is_completed)
                                            <i class="fas fa-check-circle text-success fs-4"></i>
                                        @else
                                            <i class="far fa-circle text-secondary fs-4"></i>
                                        @endif
                                    </button>
                                </form>
                                <span class="{{ $task->is_completed ? 'completed' : '' }} fw-bold">
                                    {{ $task->title }}
                                </span>
                            </div>

                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#editModal{{ $task->id }}">
                                    <i class="fas fa-edit success"></i>
                                </button>

                                <form id="delete-form-{{ $task->id }}" action="{{ route('delete', $task->id) }}" method="POST">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete('{{$task ->id}}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </li>

                        <div class="modal fade" id="editModal{{ $task->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route('update', $task->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="action" value="update_title">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Task</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="text" name="title" value="{{ $task->title }}" class="form-control" required>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success">Save Changes</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-muted">No tasks yet. Start adding some!</p>
                    @endforelse
                </ul>

                <!--Report-->
                <div class="card shadow-sm p-4 mt-4 border-0" style="border-radius: 15px; background-color: #ffffff;">
                    <h5 class="text-primary mb-3"><i class="fas fa-chart-line me-2"></i> របាយការណ៍សរុប</h5>
                    
                    <div class="row g-2 text-center mb-4">
                        <div class="col-4">
                            <div class="p-2 border rounded bg-light shadow-sm">
                                <small class="text-muted">ការងារសរុប</small>
                                <div class="fw-bold fs-5">{{ $totalTasks }}</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 border rounded bg-light shadow-sm border-success border-2">
                                <small class="text-muted text-success">រួចរាល់</small>
                                <div class="fw-bold fs-5 text-success">{{ $completedTasks }}</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 border rounded bg-light shadow-sm border-warning border-2">
                                <small class="text-muted text-warning">នៅសល់</small>
                                <div class="fw-bold fs-5 text-warning">{{ $pendingTasks }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="progress" style="height: 12px; border-radius: 6px;">
                        <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" 
                            style="width: {{ $percentage }}%"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <span class="small text-muted">ភាគរយជោគជ័យ</span>
                        <span class="small fw-bold text-success">{{ $percentage }}%</span>
                    </div>
                </div>

                <!--History-->
                <div class="card shadow-sm p-4 mt-4 border-0" style="border-radius: 15px;">
                   

                    <div class="mt-4 pt-3 border-top">
                        <a href="{{ route('history') }}" class="btn btn-sm btn-outline-primary w-100">
                            <i class="fas fa-history me-1"></i> មើលប្រវត្តិកិច្ចការងារចាស់ៗ
                        </a>
                    </div>

                </div>
                

            </div>
        </div>
    </div>
</div>


</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
   // ប្រើ JavaScript ដើម្បីចាប់យកតម្លៃពី Session របស់ Laravel
    // ធ្វើបែបនេះនឹងជួយកាត់បន្ថយបន្ទាត់ក្រហមក្នុង VS Code
    document.addEventListener('DOMContentLoaded', function () {
        const successMessage = "{{ session('success') }}";
        
        if (successMessage) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: successMessage,
                timer: 3000,
                showConfirmButton: false
            });
        }
    });

    // Function សម្រាប់សួរបញ្ជាក់មុនពេលលុប
    function confirmDelete(taskId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // ស្វែងរក Form តាមរយៈ ID និងធ្វើការ Submit
                const form = document.getElementById('delete-form-' + taskId);
                if (form) {
                    form.submit();
                }
            }
        });
    }
</script>
</html>