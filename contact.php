<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/includes/inc-db-connection.php");
	include($_SERVER['DOCUMENT_ROOT'] . "/includes/inc-functions.php");
	include($_SERVER['DOCUMENT_ROOT'] . "/includes/inc-header.php");
?>

<main class="container my-5">
    <div class="text-center mb-5">
        <h1 class="display-4">Contact Us</h1>
        <p class="lead">We'd love to hear from you! Fill out the form below to get in touch.</p>
    </div>
    <div class="row equal-height">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h2 class="card-title">Send Us a Message</h2>
                    <form>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Submit</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h2 class="card-title">Contact Information</h2>
                    <p>Email: info@syncfas.com</p>
                    <p>Phone: +44 1234 567 890</p>
                    <p>Address:</p>
                    <address>
                        SyncFàs CRM<br>
                        123 Business Street<br>
                        Edinburgh, EH1 1AB<br>
                        Scotland
                    </address>
                    <h3>Follow Us</h3>
                    <a href="#" class="btn btn-outline-primary"><i class="fab fa-facebook"></i> Facebook</a>
                    <a href="#" class="btn btn-outline-primary"><i class="fab fa-twitter"></i> Twitter</a>
                    <a href="#" class="btn btn-outline-primary"><i class="fab fa-linkedin"></i> LinkedIn</a>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
    include($_SERVER['DOCUMENT_ROOT'] . "/includes/inc-footer.php"); 
?>