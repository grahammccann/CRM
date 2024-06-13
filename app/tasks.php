<?php
ob_start();
include($_SERVER['DOCUMENT_ROOT'] . "/app/includes/inc-app-header.php");
?>

<?php

// Fetch tasks for the logged-in user
$user_id = $_SESSION['user_id'];
$tasks = DB::getInstance()->select("SELECT * FROM tasks WHERE user_id = :user_id", ['user_id' => $user_id]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $task_id = $_POST['id'] ?? 0;

    if ($action === 'delete' && $task_id) {
        $delete_status = DB::getInstance()->delete('tasks', 'id', $task_id);
        if ($delete_status) {
            $_SESSION['success'] = "Task deleted successfully.";
        } else {
            $_SESSION['error'] = "Failed to delete task.";
        }
        header('Location: tasks.php');
        exit;
    } elseif ($action === 'add' || $action === 'edit') {
        $description = trim($_POST['description']);
        $due_date = trim($_POST['due_date']);
        $priority = trim($_POST['priority']);
        $status = trim($_POST['status']);
        $related_id = trim($_POST['related_id']);
        $related_type = trim($_POST['related_type']);

        $fields = [
            'user_id' => $user_id,
            'description' => $description,
            'due_date' => $due_date,
            'priority' => $priority,
            'status' => $status,
            'related_id' => $related_id,
            'related_type' => $related_type
        ];

        if ($action === 'add') {
            $insert_status = DB::getInstance()->insert('tasks', $fields);
            if ($insert_status) {
                $_SESSION['success'] = "Task added successfully.";
            } else {
                $_SESSION['error'] = "Failed to add task.";
            }
        } elseif ($action === 'edit' && $task_id) {
            $update_status = DB::getInstance()->update('tasks', 'id', $task_id, $fields);
            if ($update_status) {
                $_SESSION['success'] = "Task updated successfully.";
            } else {
                $_SESSION['error'] = "Failed to update task.";
            }
        }
        header('Location: tasks.php');
        exit;
    }
}

ob_end_flush();

?>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <!-- Display success or error messages -->
            <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success" data-bs-toggle="tooltip" title="Success message">
                <i class="bi bi-check-circle"></i> <?= htmlspecialchars($_SESSION['success']) ?>
                <?php unset($_SESSION['success']); ?>
            </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger" data-bs-toggle="tooltip" title="Error message">
                <i class="bi bi-exclamation-circle"></i> <?= htmlspecialchars($_SESSION['error']) ?>
                <?php unset($_SESSION['error']); ?>
            </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-12">
                    <div class="card mt-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3>Tasks</h3>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEditTaskModal" data-bs-toggle="tooltip" title="Click to add a new task" style="position: absolute; right: 20px;"><i class="bi bi-plus-circle"></i> Add Task</button>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Description</th>
                                        <th>Due Date</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Related To</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tasks as $task): ?>
                                    <tr>
                                        <td data-bs-toggle="tooltip" title="Task ID"><?= htmlspecialchars($task['id']); ?></td>
                                        <td data-bs-toggle="tooltip" title="Task description"><?= htmlspecialchars($task['description']); ?></td>
                                        <td data-bs-toggle="tooltip" title="Task due date"><?= htmlspecialchars($task['due_date']); ?></td>
                                        <td data-bs-toggle="tooltip" title="Task priority">
                                            <?php
                                            $priority_color = '';
                                            switch ($task['priority']) {
                                                case 'low':
                                                    $priority_color = 'badge bg-success';
                                                    break;
                                                case 'medium':
                                                    $priority_color = 'badge bg-warning';
                                                    break;
                                                case 'high':
                                                    $priority_color = 'badge bg-danger';
                                                    break;
                                                default:
                                                    $priority_color = 'badge bg-secondary';
                                            }
                                            ?>
                                            <span class="<?= $priority_color; ?>"><?= htmlspecialchars($task['priority']); ?></span>
                                        </td>
                                        <td data-bs-toggle="tooltip" title="Task status">
                                            <?php
                                            $status_color = '';
                                            switch ($task['status']) {
                                                case 'pending':
                                                    $status_color = 'badge bg-warning';
                                                    break;
                                                case 'in-progress':
                                                    $status_color = 'badge bg-primary';
                                                    break;
                                                case 'completed':
                                                    $status_color = 'badge bg-success';
                                                    break;
                                                case 'cancelled':
                                                    $status_color = 'badge bg-danger';
                                                    break;
                                                default:
                                                    $status_color = 'badge bg-secondary';
                                            }
                                            ?>
                                            <span class="<?= $status_color; ?>"><?= htmlspecialchars($task['status']); ?></span>
                                        </td>
                                        <td data-bs-toggle="tooltip" title="Related entity"><?= htmlspecialchars($task['related_type'] . ' - ' . $task['related_id']); ?></td>
                                        <td>
                                            <button onclick="editTask(<?= $task['id']; ?>)" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit this task"><i class="bi bi-pencil"></i> Edit</button>
                                            <form action="tasks.php" method="post" style="display:inline-block;">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?= $task['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Delete this task"><i class="bi bi-trash"></i> Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Add/Edit Task Modal -->
<div class="modal fade" id="addEditTaskModal" tabindex="-1" aria-labelledby="addEditTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEditTaskModalLabel">Add/Edit Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addEditTaskForm" action="tasks.php" method="post">
                <div class="modal-body">
                    <input type="hidden" name="action" id="taskAction" value="add">
                    <input type="hidden" name="id" id="taskId">
                    <div class="mb-3">
                        <label for="description" class="form-label" data-bs-toggle="tooltip" title="Enter task description">Description</label>
                        <input type="text" class="form-control" id="description" name="description" required>
                    </div>
                    <div class="mb-3">
                        <label for="due_date" class="form-label" data-bs-toggle="tooltip" title="Enter task due date">Due Date</label>
                        <input type="date" class="form-control" id="due_date" name="due_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="priority" class="form-label" data-bs-toggle="tooltip" title="Select task priority">Priority</label>
                        <select class="form-control" id="priority" name="priority" required>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label" data-bs-toggle="tooltip" title="Select task status">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="in-progress">In Progress</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="related_type" class="form-label" data-bs-toggle="tooltip" title="Select related entity type">Related To</label>
                        <select class="form-control" id="related_type" name="related_type" required>
                            <option value="none">None</option>
                            <option value="customer">Customer</option>
                            <option value="lead">Lead</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="related_id" class="form-label" data-bs-toggle="tooltip" title="Enter related ID (if applicable)">Related ID</label>
                        <input type="number" class="form-control" id="related_id" name="related_id" placeholder="Enter related ID (if applicable)">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" data-bs-toggle="tooltip" title="Save the task">Save Task</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editTask(id) {
    const tasks = <?= json_encode($tasks); ?>;
    const task = tasks.find(task => task.id == id);
    if (task) {
        document.getElementById('taskAction').value = 'edit';
        document.getElementById('taskId').value = task.id;
        document.getElementById('description').value = task.description;
        document.getElementById('due_date').value = task.due_date;
        document.getElementById('priority').value = task.priority;
        document.getElementById('status').value = task.status;
        document.getElementById('related_type').value = task.related_type;
        document.getElementById('related_id').value = task.related_id;
        document.getElementById('addEditTaskModalLabel').innerText = 'Edit Task';
        new bootstrap.Modal(document.getElementById('addEditTaskModal')).show();
    }
}
</script>

<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/app/includes/inc-app-footer.php");
	ob_end_flush();
?>
