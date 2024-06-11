<?php
ob_start();
include($_SERVER['DOCUMENT_ROOT'] . "/app/includes/inc-app-header.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $company_name = trim($_POST['company_name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $job_title = trim($_POST['job_title']);

    $errors = [];

    if (empty($name) || empty($email)) {
        $errors[] = "Name and email are required.";
    }

    // Check if the new email already exists for another user
    $existing_user = DB::getInstance()->selectOne("SELECT id FROM users WHERE email = :email AND id != :user_id", ['email' => $email, 'user_id' => $user_id]);
    if ($existing_user) {
        $errors[] = "The email address is already taken.";
    }

    if (!empty($password) && ($password !== $confirm_password)) {
        $errors[] = "Passwords do not match.";
    }

    if (empty($errors)) {
        $update_fields = [
            'name' => $name,
            'email' => $email,
            'company_name' => $company_name,
            'phone' => $phone,
            'address' => $address,
            'job_title' => $job_title
        ];

        if (!empty($password)) {
            $update_fields['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $update_status = DB::getInstance()->update('users', 'id', $user_id, $update_fields);

        if ($update_status) {
            $avatar_update_status = handleAvatarUpload($user_id);
            $_SESSION['success'] = "Profile updated successfully.";
            if ($avatar_update_status) {
                $_SESSION['success'] .= " Avatar updated successfully.";
            }
            header('Location: profile.php');
            exit;
        } else {
            $errors[] = "Failed to update profile. Please try again.";
        }
    }
}
?>

<div class="content-wrapper">

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Display success or error messages -->
            <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <i class="bi bi-check-circle"></i> <?= $_SESSION['success'] ?>
                <?php unset($_SESSION['success']); ?>
            </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-circle"></i> <?= implode('<br>', $errors) ?>
            </div>
            <?php endif; ?>

            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Edit Profile</h3>
                        </div>
                        <!-- form start -->
                        <form action="profile.php" method="post" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user_info['name'] ?? ''); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user_info['email'] ?? ''); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="company_name">Company Name</label>
                                    <input type="text" class="form-control" id="company_name" name="company_name" value="<?php echo htmlspecialchars($user_info['company_name'] ?? ''); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($user_info['phone'] ?? ''); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <textarea class="form-control" id="address" name="address"><?php echo htmlspecialchars($user_info['address'] ?? ''); ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="job_title">Job Title</label>
                                    <input type="text" class="form-control" id="job_title" name="job_title" value="<?php echo htmlspecialchars($user_info['job_title'] ?? ''); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>
                                <div class="form-group">
                                    <label for="confirm_password">Confirm Password</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                                </div>
                                <div class="form-group">
                                    <label for="avatar">Avatar</label>
                                    <input type="file" class="form-control" id="avatar" name="avatar">
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer text-center">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update Profile</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<?php
include($_SERVER['DOCUMENT_ROOT'] . "/app/includes/inc-app-footer.php");
?>
