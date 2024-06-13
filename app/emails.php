<?php
	ob_start();
	include($_SERVER['DOCUMENT_ROOT'] . "/app/includes/inc-app-header.php");
?>

<?php

// Fetch emails for the logged-in user
$user_id = $_SESSION['user_id'];
$emails = DB::getInstance()->select("SELECT * FROM emails WHERE user_id = :user_id", ['user_id' => $user_id]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipient = trim($_POST['recipient']);
    $subject = trim($_POST['subject']);
    $body = trim($_POST['body']);
    $status = 'sent';
    $date_sent = date('Y-m-d H:i:s');
    
    // Send email
    sendEmail($recipient, $subject, $body);

    $fields = [
        'user_id' => $user_id,
        'recipient' => $recipient,
        'subject' => $subject,
        'body' => $body,
        'status' => $status,
        'date_sent' => $date_sent
    ];

    $insert_status = DB::getInstance()->insert('emails', $fields);
    if ($insert_status) {
        $_SESSION['success'] = "Email sent successfully.";
    } else {
        $_SESSION['error'] = "Failed to send email.";
    }
    header('Location: emails.php');
    exit;
}

function sendEmail($to, $subject, $body) {
    $headers = 'From: no-reply@syncfas.com' . "\r\n" .
               'Reply-To: no-reply@syncfas.com' . "\r\n" .
               'X-Mailer: PHP/' . phpversion();
    
    if (!mail($to, $subject, $body, $headers)) {
        echo "Email sending failed.";
    }
}

$status_badge_classes = [
    'sent' => 'badge bg-success',
    'failed' => 'badge bg-danger',
    'pending' => 'badge bg-warning',
];

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

            <div class="row">
                <div class="col-12">
                    <div class="card mt-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3>Emails</h3>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEmailModal" data-bs-toggle="tooltip" title="Click to send a new email" style="position: absolute; right: 20px;"><i class="bi bi-envelope-plus"></i> Send Email</button>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Recipient Email</th>
                                        <th>Subject</th>
                                        <th>Body</th>
                                        <th>Status</th>
                                        <th>Date Sent</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($emails as $email): ?>
                                    <tr>
                                        <td data-bs-toggle="tooltip" title="Email ID"><?= htmlspecialchars($email['id']); ?></td>
                                        <td><a href="mailto:<?= htmlspecialchars($email['recipient']); ?>" style="color: blue; text-decoration: none;" data-bs-toggle="tooltip" title="Send email to <?= htmlspecialchars($email['recipient']); ?>"><?= htmlspecialchars($email['recipient']); ?></a></td>
                                        <td data-bs-toggle="tooltip" title="Email subject"><?= htmlspecialchars($email['subject']); ?></td>
                                        <td data-bs-toggle="tooltip" title="Email body"><?= htmlspecialchars($email['body']); ?></td>
                                        <td><span class="<?= isset($status_badge_classes[$email['status']]) ? $status_badge_classes[$email['status']] : 'badge bg-secondary'; ?>" data-bs-toggle="tooltip" title="Email status"><?= htmlspecialchars($email['status']); ?></span></td>
                                        <td data-bs-toggle="tooltip" title="Date the email was sent"><?= htmlspecialchars($email['date_sent']); ?></td>
                                        <td>
                                            <button onclick="editEmail(<?= $email['id']; ?>)" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit this email"><i class="bi bi-pencil"></i> Edit</button>
                                            <form action="emails.php" method="post" style="display:inline-block;">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?= $email['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Delete this email"><i class="bi bi-trash"></i> Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Add Email Modal -->
<div class="modal fade" id="addEmailModal" tabindex="-1" aria-labelledby="addEmailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEmailModalLabel">Send Email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addEmailForm" action="emails.php" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="recipient" class="form-label" data-bs-toggle="tooltip" title="Enter the recipient's email address">Recipient Email</label>
                        <input type="email" class="form-control" id="recipient" name="recipient" required>
                    </div>
                    <div class="mb-3">
                        <label for="subject" class="form-label" data-bs-toggle="tooltip" title="Enter the email subject">Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject" required>
                    </div>
                    <div class="mb-3">
                        <label for="body" class="form-label" data-bs-toggle="tooltip" title="Enter the email body">Body</label>
                        <textarea class="form-control" id="body" name="body" rows="5" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" data-bs-toggle="tooltip" title="Send the email">Send Email</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editEmail(id) {
    const email = <?= json_encode($emails); ?>.find(email => email.id == id);
    if (email) {
        document.getElementById('recipient').value = email.recipient;
        document.getElementById('subject').value = email.subject;
        document.getElementById('body').value = email.body;
        new bootstrap.Modal(document.getElementById('addEmailModal')).show();
    }
}
</script>

<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/app/includes/inc-app-footer.php");
	ob_end_flush();
?>