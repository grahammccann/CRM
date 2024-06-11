<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . "/includes/inc-db-connection.php");
include($_SERVER['DOCUMENT_ROOT'] . "/includes/inc-functions.php");

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['form_type']) && $_POST['form_type'] === 'login') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $db = DB::getInstance();
            $user = $db->selectOneByField('users', 'email', $email);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                header('Location: app/index.php');
                exit;
            } else {
                $loginError = "Invalid email or password.";
            }
        } elseif (isset($_POST['form_type']) && $_POST['form_type'] === 'register') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $company = $_POST['company'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];
            $job_title = $_POST['job_title'];

            $db = DB::getInstance();
            $user = $db->selectOneByField('users', 'email', $email);

            if ($user) {
                $registerError = "Email already registered.";
            } else {
                // Check if this is the first user
                $totalUsers = $db->selectValue("SELECT COUNT(*) FROM users");
                $role = ($totalUsers == 0) ? 'admin' : 'user';

                $userId = $db->insert('users', [
                    'name' => $name,
                    'email' => $email,
                    'password' => $password,
                    'role' => $role,
                    'company' => $company,
                    'phone' => $phone,
                    'address' => $address,
                    'job_title' => $job_title
                ]);

                // Assign free basic plan
                $db->insert('subscriptions', [
                    'user_id' => $userId,
                    'plan_id' => 'Basic', // Using ENUM value
                    'start_date' => date('Y-m-d'),
                    'end_date' => date('Y-m-d', strtotime('+1 month')),
                    'status' => 'active'
                ]);

                $_SESSION['user_id'] = $userId;
                $_SESSION['user_name'] = $name;
                header('Location: app/index.php');
                exit;
            }
        }
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    $error = "An error occurred. Please try again later.";
}
?>

<?php
include($_SERVER['DOCUMENT_ROOT'] . "/includes/inc-header.php");
?>

<main class="container my-5">
    <section class="my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="card-title text-center">Login</h2>
                        <?php if (isset($loginError)): ?>
                            <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($loginError) ?></div>
                        <?php endif; ?>
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>
                        <form action="login.php" method="post">
                            <input type="hidden" name="form_type" value="login">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" id="email" placeholder="Email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-sign-in-alt"></i> Sign In</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="card-title text-center">Register</h2>
                        <?php if (isset($registerError)): ?>
                            <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($registerError) ?></div>
                        <?php endif; ?>
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>
                        <form action="login.php" method="post">
                            <input type="hidden" name="form_type" value="register">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" id="email" placeholder="Email" required>
                            </div>
                            <div class="mb-3">
                                <label for="company" class="form-label">Company</label>
                                <input type="text" name="company" class="form-control" id="company" placeholder="Company">
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control" id="phone" placeholder="Phone">
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea name="address" class="form-control" id="address" placeholder="Address"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="job_title" class="form-label">Job Title</label>
                                <input type="text" name="job_title" class="form-control" id="job_title" placeholder="Job Title">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success btn-block"><i class="fas fa-user-plus"></i> Register</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php
include($_SERVER['DOCUMENT_ROOT'] . "/includes/inc-footer.php");
?>