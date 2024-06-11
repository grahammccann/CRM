<?php
    include($_SERVER['DOCUMENT_ROOT'] . "/includes/inc-db-connection.php");
    include($_SERVER['DOCUMENT_ROOT'] . "/includes/inc-functions.php");
    include($_SERVER['DOCUMENT_ROOT'] . "/includes/inc-header.php");
?>

<main class="container my-5">
    <!-- Introduction Section -->
    <div class="text-center mb-5">
        <h1 class="display-4">About SyncFàs CRM</h1>
        <p class="lead">Discover our journey, values, vision, and mission.</p>
    </div>

    <!-- Our Story Section -->
    <section class="mb-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="card-title">Our Story</h2>
                <p class="card-text">SyncFàs CRM was founded with a mission to help businesses manage their customer relationships more effectively. From our humble beginnings to becoming a leading CRM solution provider, our journey has been fueled by innovation, dedication, and a commitment to excellence. We continuously strive to bring cutting-edge technology and innovative solutions to our clients, ensuring they stay ahead in a competitive market.</p>
            </div>
        </div>
    </section>

    <!-- Our Values Section -->
    <section class="mb-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="card-title">Our Values</h2>
                <ul class="list-unstyled">
                    <li><i class="fas fa-check-circle text-success"></i> <strong>Customer-centricity:</strong> We place our customers at the heart of everything we do.</li>
                    <li><i class="fas fa-check-circle text-success"></i> <strong>Innovation:</strong> We embrace change and constantly seek new ways to improve our products and services.</li>
                    <li><i class="fas fa-check-circle text-success"></i> <strong>Integrity:</strong> We uphold the highest standards of integrity in all our actions.</li>
                    <li><i class="fas fa-check-circle text-success"></i> <strong>Teamwork:</strong> We work together to meet the needs of our customers and to help our company win.</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Our Vision and Mission Section -->
    <section class="mb-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="card-title">Our Vision and Mission</h2>
                <p class="card-text"><strong>Vision:</strong> To be the leading CRM solution provider globally, empowering businesses to achieve their full potential through innovative technology and unparalleled service.</p>
                <p class="card-text"><strong>Mission:</strong> To deliver state-of-the-art CRM solutions that help businesses build stronger relationships with their customers and drive growth through innovation and excellence.</p>
            </div>
        </div>
    </section>

    <!-- Customer Testimonials Section -->
    <section class="mb-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="card-title">Customer Testimonials</h2>
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <p class="card-text">"SyncFàs CRM has transformed how we manage our customer relationships. It's a game-changer with its innovative features!"</p>
                                <footer class="blockquote-footer">Sarah Williams, <cite title="Company Name">XYZ Corp</cite></footer>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <p class="card-text">"The features and support provided by SyncFàs CRM are unmatched. Their innovative approach is truly impressive."</p>
                                <footer class="blockquote-footer">Michael Brown, <cite title="Company Name">ABC Ltd</cite></footer>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="text-center mb-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="card-title">Get in Touch</h2>
                <p>If you have any questions or would like to learn more about SyncFàs CRM, feel free to contact us or follow us on social media.</p>
                <a href="contact.php" class="btn btn-primary"><i class="fas fa-envelope"></i> Contact Us</a>
            </div>
        </div>
    </section>
</main>

<?php
    include($_SERVER['DOCUMENT_ROOT'] . "/includes/inc-footer.php");
?>