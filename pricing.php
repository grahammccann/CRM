<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/includes/inc-db-connection.php");
	include($_SERVER['DOCUMENT_ROOT'] . "/includes/inc-functions.php");
	include($_SERVER['DOCUMENT_ROOT'] . "/includes/inc-header.php");
?>

<main class="container my-5">
    <div class="text-center mb-5">
        <h1 class="display-4">Pricing Plans</h1>
        <p class="lead">Choose a plan that fits your business needs and budget.</p>
    </div>

    <section class="row text-center">
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3>Basic Plan</h3>
                </div>
                <div class="card-body d-flex flex-column">
                    <h2 class="card-title">$0/month</h2>
                    <p class="card-text">Ideal for small businesses and startups.</p>
                    <ul class="list-unstyled mb-4">
                        <li><i class="fas fa-check text-success"></i> Contact Management</li>
                        <li><i class="fas fa-check text-success"></i> Task Management</li>
                        <li><i class="fas fa-check text-success"></i> Email Integration</li>
                    </ul>
                    <div class="mt-auto">
                        <a href="login.php" class="btn btn-primary">
                            <i class="fas fa-user-plus"></i> Sign Up
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h3>Standard Plan</h3>
                </div>
                <div class="card-body d-flex flex-column">
                    <h2 class="card-title">$49/month</h2>
                    <p class="card-text">Best for growing businesses with expanding needs.</p>
                    <ul class="list-unstyled mb-4">
                        <li><i class="fas fa-check text-success"></i> All Basic Plan Features</li>
                        <li><i class="fas fa-check text-success"></i> Sales Tracking</li>
                        <li><i class="fas fa-check text-success"></i> Analytics & Reports</li>
                    </ul>
                    <div class="mt-auto">
                        <a href="#" class="btn btn-success">
                            <i class="fas fa-user-plus"></i> Sign Up
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-warning text-white">
                    <h3>Premium Plan</h3>
                </div>
                <div class="card-body d-flex flex-column">
                    <h2 class="card-title">$99/month</h2>
                    <p class="card-text">Comprehensive solution for large businesses.</p>
                    <ul class="list-unstyled mb-4">
                        <li><i class="fas fa-check text-success"></i> All Standard Plan Features</li>
                        <li><i class="fas fa-check text-success"></i> Automated Workflows</li>
                        <li><i class="fas fa-check text-success"></i> Priority Support</li>
                    </ul>
                    <div class="mt-auto">
                        <a href="#" class="btn btn-warning">
                            <i class="fas fa-user-plus"></i> Sign Up
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php
    include($_SERVER['DOCUMENT_ROOT'] . "/includes/inc-footer.php");
?>