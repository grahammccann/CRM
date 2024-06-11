<?php
    include($_SERVER['DOCUMENT_ROOT'] . "/app/includes/inc-app-header.php");
?>

<div class="container-fluid">

    <!-- Main content for the index page -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3>Welcome to your Dashboard</h3>
                </div>
                <div class="card-body">
                    <p>You are successfully logged in as <strong><?php echo htmlspecialchars($user_info['name']); ?></strong>. Member since: <?php echo date('F j, Y', strtotime($user_info['created_at'])); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard widgets -->
    <div class="row mt-3">
        <div class="col-lg-3 col-md-6">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">
                    <h4 class="card-title"><i class="bi bi-graph-up-arrow"></i> Performance</h4>
                </div>
                <div class="card-body">
                    <p class="card-text">View your recent performance metrics.</p>
                    <a href="#" class="btn btn-light">View Details</a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card text-white bg-info mb-3">
                <div class="card-header">
                    <h4 class="card-title"><i class="bi bi-people"></i> Team</h4>
                </div>
                <div class="card-body">
                    <p class="card-text">See your team's progress and updates.</p>
                    <a href="#" class="btn btn-light">View Details</a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">
                    <h4 class="card-title"><i class="bi bi-calendar-event"></i> Events</h4>
                </div>
                <div class="card-body">
                    <p class="card-text">Check out upcoming events and meetings.</p>
                    <a href="#" class="btn btn-light">View Details</a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card text-white bg-danger mb-3">
                <div class="card-header">
                    <h4 class="card-title"><i class="bi bi-bell"></i> Notifications</h4>
                </div>
                <div class="card-body">
                    <p class="card-text">Review your latest notifications.</p>
                    <a href="#" class="btn btn-light">View Details</a>
                </div>
            </div>
        </div>
    </div>

</div>

<?php
    include($_SERVER['DOCUMENT_ROOT'] . "/app/includes/inc-app-footer.php");
?>
