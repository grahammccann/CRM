<?php
    include($_SERVER['DOCUMENT_ROOT'] . "/includes/inc-db-connection.php");
    include($_SERVER['DOCUMENT_ROOT'] . "/includes/inc-functions.php");
    include($_SERVER['DOCUMENT_ROOT'] . "/includes/inc-header.php");
?>

<main class="container my-5">
    <!-- Hero Section -->
    <div class="hero text-white text-center py-5" style="background-image: url('/images/img-hero-image.png'); background-size: cover;">
        <h1 class="display-4">Welcome to SyncFàs CRM</h1>
        <p class="lead">The ultimate solution for managing customer relationships and driving business growth.</p>
        <a class="btn btn-light btn-lg" href="features.php" role="button">Learn More</a>
    </div>

    <!-- Features Section -->
    <section class="my-5">
        <div class="row text-center">
            <div class="col-md-4 d-flex flex-column">
                <div class="feature-icon mb-3" style="color: #007bff;"><i class="fas fa-cogs fa-3x"></i></div>
                <h3>Powerful Features</h3>
                <p class="flex-grow-1">Explore the features of SyncFàs CRM that help you manage your business efficiently.</p>
                <a class="btn btn-secondary mt-auto" href="features.php" role="button">View details &raquo;</a>
            </div>
            <div class="col-md-4 d-flex flex-column">
                <div class="feature-icon mb-3" style="color: #28a745;"><i class="fas fa-dollar-sign fa-3x"></i></div>
                <h3>Competitive Pricing</h3>
                <p class="flex-grow-1">Choose a pricing plan that suits your business needs.</p>
                <a class="btn btn-secondary mt-auto" href="pricing.php" role="button">View details &raquo;</a>
            </div>
            <div class="col-md-4 d-flex flex-column">
                <div class="feature-icon mb-3" style="color: #ffc107;"><i class="fas fa-headset fa-3x"></i></div>
                <h3>Get in Touch</h3>
                <p class="flex-grow-1">Contact us for more information or support regarding SyncFàs CRM.</p>
                <a class="btn btn-secondary mt-auto" href="contact.php" role="button">View details &raquo;</a>
            </div>
        </div>
    </section>

    <!-- AI-generated Images Section -->
    <section class="my-5">
        <div class="row">
            <div class="col-md-6">
                <img src="<?= fullUrl(); ?>images/img-a-professional-business-team-collaborating.png" class="img-fluid" alt="Professional Business Team">
            </div>
            <div class="col-md-6">
                <img src="<?= fullUrl(); ?>images/img-crm-software-dashboard.png" class="img-fluid" alt="CRM Software Dashboard">
            </div>
        </div>
    </section>
</main>

<?php
    include($_SERVER['DOCUMENT_ROOT'] . "/includes/inc-footer.php");
?>