<?php
    include($_SERVER['DOCUMENT_ROOT'] . "/includes/inc-db-connection.php");
    include($_SERVER['DOCUMENT_ROOT'] . "/includes/inc-functions.php");
    include($_SERVER['DOCUMENT_ROOT'] . "/includes/inc-header.php");
?>

<main class="container my-5">
    <!-- Introduction Section -->
    <div class="text-center mb-5">
        <h1 class="display-4">Support</h1>
        <p class="lead">Need help? Find the support you need here.</p>
    </div>

    <div class="row">
        <!-- Contact Form Section -->
        <div class="col-md-6 mb-5">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="card-title">Submit a Support Request</h2>
                    <form action="support_form_handler.php" method="post">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="issue" class="form-label">Issue</label>
                            <textarea class="form-control" id="issue" name="issue" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Submit
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <!-- FAQ Section -->
        <div class="col-md-6 mb-5">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="card-title">Frequently Asked Questions</h2>
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    How do I reset my password?
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    To reset your password, click on the 'Forgot Password' link on the login page and follow the instructions.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    How can I contact support?
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    You can contact support by filling out the form on this page or emailing us at contact@syncfas.com.
                                </div>
                            </div>
                        </div>
                        <!-- Add more FAQs as needed -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Documentation Links Section -->
    <section class="my-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="card-title">Documentation and Resources</h2>
                <p>Find detailed guides, tutorials, and documentation to help you get the most out of SyncFàs CRM.</p>
                <ul class="list-unstyled">
                    <li><a href="user-guide.pdf" class="btn btn-link"><i class="fas fa-book"></i> User Guide</a></li>
                    <li><a href="api-documentation.html" class="btn btn-link"><i class="fas fa-code"></i> API Documentation</a></li>
                    <li><a href="tutorials.html" class="btn btn-link"><i class="fas fa-video"></i> Tutorials</a></li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Live Chat Section -->
    <section class="my-5 text-center">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="card-title">Live Chat Support</h2>
                <p>Need immediate assistance? Chat with one of our support agents now.</p>
                <a href="live-chat.html" class="btn btn-primary"><i class="fas fa-comments"></i> Start Live Chat</a>
            </div>
        </div>
    </section>
</main>

<?php
    include($_SERVER['DOCUMENT_ROOT'] . "/includes/inc-footer.php");
?>