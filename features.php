<?php
    include($_SERVER['DOCUMENT_ROOT'] . "/includes/inc-db-connection.php");
    include($_SERVER['DOCUMENT_ROOT'] . "/includes/inc-functions.php");
    include($_SERVER['DOCUMENT_ROOT'] . "/includes/inc-header.php");
?>

<main class="container my-5">
    <div class="text-center mb-5">
        <h1 class="display-4">Our Features</h1>
        <p class="lead">Discover the powerful features that make SyncFàs CRM the perfect choice for your business.</p>
    </div>

    <section class="row text-center mb-5">
        <div class="col-md-4 d-flex flex-column feature-card">
            <div class="feature-icon mb-3" style="color: #007bff;"><i class="fas fa-users fa-3x"></i></div>
            <h3>Contact Management</h3>
            <p class="flex-grow-1">Manage your contacts efficiently with our comprehensive contact management system.</p>
        </div>
        <div class="col-md-4 d-flex flex-column feature-card">
            <div class="feature-icon mb-3" style="color: #28a745;"><i class="fas fa-chart-line fa-3x"></i></div>
            <h3>Sales Tracking</h3>
            <p class="flex-grow-1">Track your sales pipeline and close deals faster with our intuitive sales tracking tools.</p>
        </div>
        <div class="col-md-4 d-flex flex-column feature-card">
            <div class="feature-icon mb-3" style="color: #ffc107;"><i class="fas fa-calendar-check fa-3x"></i></div>
            <h3>Task Management</h3>
            <p class="flex-grow-1">Stay on top of your tasks and deadlines with our efficient task management features.</p>
        </div>
    </section>

    <section class="row text-center mb-5">
        <div class="col-md-4 d-flex flex-column feature-card">
            <div class="feature-icon mb-3" style="color: #17a2b8;"><i class="fas fa-envelope fa-3x"></i></div>
            <h3>Email Integration</h3>
            <p class="flex-grow-1">Integrate your email seamlessly and manage all communications from one platform.</p>
        </div>
        <div class="col-md-4 d-flex flex-column feature-card">
            <div class="feature-icon mb-3" style="color: #6f42c1;"><i class="fas fa-chart-pie fa-3x"></i></div>
            <h3>Analytics & Reports</h3>
            <p class="flex-grow-1">Gain insights with detailed analytics and custom reports to drive your business decisions.</p>
        </div>
        <div class="col-md-4 d-flex flex-column feature-card">
            <div class="feature-icon mb-3" style="color: #dc3545;"><i class="fas fa-mobile-alt fa-3x"></i></div>
            <h3>Mobile Access</h3>
            <p class="flex-grow-1">Access your CRM on the go with our fully responsive mobile interface.</p>
        </div>
    </section>

    <section class="text-center mb-5">
        <h2 class="mb-4">Additional Features</h2>
        <div class="row">
            <div class="col-md-6 mb-4 d-flex flex-column">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="feature-icon mb-3" style="color: #007bff;"><i class="fas fa-lock fa-2x"></i></div>
                        <h4 class="card-title">Data Security</h4>
                        <p class="card-text flex-grow-1">Ensure your data is safe with our top-notch security features and encryption.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4 d-flex flex-column">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="feature-icon mb-3" style="color: #28a745;"><i class="fas fa-sync-alt fa-2x"></i></div>
                        <h4 class="card-title">Automated Workflows</h4>
                        <p class="card-text flex-grow-1">Automate repetitive tasks and streamline your workflows to save time and increase productivity.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php
    include($_SERVER['DOCUMENT_ROOT'] . "/includes/inc-footer.php");
?>