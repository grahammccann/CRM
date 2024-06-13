<?php
	session_start();
	ob_start();
    include($_SERVER['DOCUMENT_ROOT'] . "/app/includes/inc-app-header.php");
?>

<?php

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    ob_end_clean(); // Clean the output buffer
    header('Location: /login.php');
    exit;
}

$db = DB::getInstance();
$users = $db->select("SELECT * FROM users");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $user_id = $_POST['user_id'] ?? 0;

    if ($action === 'delete' && $user_id) {
        $delete_status = $db->delete('users', 'id', $user_id);
        if ($delete_status) {
            $_SESSION['success'] = "User deleted successfully.";
        } else {
            $_SESSION['error'] = "Failed to delete user.";
        }
        ob_end_clean(); // Clean the output buffer
        header('Location: admin-users.php');
        exit;
    } elseif ($action === 'edit' && $user_id) {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $company_name = trim($_POST['company_name']);
        $phone = trim($_POST['phone']);
        $job_title = trim($_POST['job_title']);
        $address = trim($_POST['address']);
        $role = trim($_POST['role']);

        $fields = [
            'name' => $name,
            'email' => $email,
            'company_name' => $company_name,
            'phone' => $phone,
            'job_title' => $job_title,
            'address' => $address,
            'role' => $role
        ];

        $update_status = $db->update('users', 'id', $user_id, $fields);
        if ($update_status) {
            $_SESSION['success'] = "User updated successfully.";
        } else {
            $_SESSION['error'] = "Failed to update user.";
        }
        ob_end_clean(); // Clean the output buffer
        header('Location: admin-users.php');
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

            <div class="card mt-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Manage Users</h3>
                </div>
                <div class="card-body">
                    <table id="usersTable" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Company</th>
                                <th>Phone</th>
                                <th>Job Title</th>
                                <th>Address</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                            <tr>
                                <td data-bs-toggle="tooltip" title="User ID"><?= htmlspecialchars($user['id']); ?></td>
                                <td data-bs-toggle="tooltip" title="User Name"><?= htmlspecialchars($user['name']); ?></td>
                                <td><a href="mailto:<?= htmlspecialchars($user['email']); ?>" style="text-decoration: none;" data-bs-toggle="tooltip" title="User Email"><?= htmlspecialchars($user['email']); ?></a></td>
                                <td data-bs-toggle="tooltip" title="Company Name"><?= htmlspecialchars($user['company_name']); ?></td>
                                <td data-bs-toggle="tooltip" title="Phone Number"><?= htmlspecialchars($user['phone']); ?></td>
                                <td data-bs-toggle="tooltip" title="Job Title"><?= htmlspecialchars($user['job_title']); ?></td>
                                <td data-bs-toggle="tooltip" title="Address"><?= htmlspecialchars($user['address']); ?></td>
                                <td data-bs-toggle="tooltip" title="Role"><?= htmlspecialchars($user['role']); ?></td>
                                <td>
                                    <button onclick="editUser(<?= $user['id']; ?>)" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit User"><i class="bi bi-pencil"></i></button>
                                    <form action="admin-users.php" method="post" style="display:inline-block;">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Delete User"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Add/Edit User Modal -->
<div class="modal fade" id="addEditUserModal" tabindex="-1" aria-labelledby="addEditUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEditUserModalLabel">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addEditUserForm" action="admin-users.php" method="post">
                <div class="modal-body">
                    <input type="hidden" name="action" id="userAction" value="edit">
                    <input type="hidden" name="user_id" id="userId">
                    <div class="mb-3">
                        <label for="name" class="form-label" data-bs-toggle="tooltip" title="Enter the user's name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label" data-bs-toggle="tooltip" title="Enter the user's email address">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="company_name" class="form-label" data-bs-toggle="tooltip" title="Enter the company name">Company</label>
                        <input type="text" class="form-control" id="company_name" name="company_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label" data-bs-toggle="tooltip" title="Enter the phone number">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" required>
                    </div>
                    <div class="mb-3">
                        <label for="job_title" class="form-label" data-bs-toggle="tooltip" title="Enter the job title">Job Title</label>
                        <input type="text" class="form-control" id="job_title" name="job_title" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label" data-bs-toggle="tooltip" title="Enter the address">Address</label>
                        <input type="text" class="form-control" id="address" name="address" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label" data-bs-toggle="tooltip" title="Select the user's role">Role</label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" data-bs-toggle="tooltip" title="Save the user details">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editUser(id) {
    const user = <?= json_encode($users); ?>.find(user => user.id == id);
    if (user) {
        document.getElementById('userAction').value = 'edit';
        document.getElementById('userId').value = user.id;
        document.getElementById('name').value = user.name;
        document.getElementById('email').value = user.email;
        document.getElementById('company_name').value = user.company_name;
        document.getElementById('phone').value = user.phone;
        document.getElementById('job_title').value = user.job_title;
        document.getElementById('address').value = user.address;
        document.getElementById('role').value = user.role;
        document.getElementById('addEditUserModalLabel').innerText = 'Edit User';
        new bootstrap.Modal(document.getElementById('addEditUserModal')).show();
    }
}
</script>

<?php
    include($_SERVER['DOCUMENT_ROOT'] . "/app/includes/inc-app-footer.php");
?>