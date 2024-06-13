<?php
ob_start();
include($_SERVER['DOCUMENT_ROOT'] . "/app/includes/inc-app-header.php");

// Fetch contacts for the logged-in user
$user_id = $_SESSION['user_id'];
$contacts = DB::getInstance()->select("SELECT * FROM contacts WHERE user_id = :user_id", ['user_id' => $user_id]);

$contact_to_edit = null;
if (isset($_GET['edit_id'])) {
    $contact_to_edit = DB::getInstance()->selectOne("SELECT * FROM contacts WHERE id = :id AND user_id = :user_id", ['id' => $_GET['edit_id'], 'user_id' => $user_id]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = $_POST['id'] ?? 0;

    if ($action === 'delete' && $id) {
        $delete_status = DB::getInstance()->delete('contacts', 'id', $id);
        if ($delete_status) {
            $_SESSION['success'] = "Contact deleted successfully.";
        } else {
            $_SESSION['error'] = "Failed to delete contact.";
        }
        header('Location: contacts.php');
        exit;
    } elseif ($action === 'add' || $action === 'edit') {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $address = trim($_POST['address']);
        $company = trim($_POST['company']);
        $job_title = trim($_POST['job_title']);

        $fields = [
            'user_id' => $user_id,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'company' => $company,
            'job_title' => $job_title
        ];

        if ($action === 'add') {
            $insert_status = DB::getInstance()->insert('contacts', $fields);
            if ($insert_status) {
                $_SESSION['success'] = "Contact added successfully.";
            } else {
                $_SESSION['error'] = "Failed to add contact.";
            }
        } elseif ($action === 'edit' && $id) {
            $update_status = DB::getInstance()->update('contacts', 'id', $id, $fields);
            if ($update_status) {
                $_SESSION['success'] = "Contact updated successfully.";
            } else {
                $_SESSION['error'] = "Failed to update contact.";
            }
        }
        header('Location: contacts.php');
        exit;
    }
}
ob_end_flush();
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
                            <h3>Contacts</h3>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEditContactModal" data-bs-toggle="tooltip" title="Click to add a new contact" style="position: absolute; right: 20px;"><i class="bi bi-person-plus"></i> Add Contact</button>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <th>Company</th>
                                        <th>Job Title</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($contacts as $contact): ?>
                                    <tr>
                                        <td data-bs-toggle="tooltip" title="Contact ID"><?= htmlspecialchars($contact['id']); ?></td>
                                        <td data-bs-toggle="tooltip" title="Contact name"><?= htmlspecialchars($contact['name']); ?></td>
                                        <td><a href="mailto:<?= htmlspecialchars($contact['email']); ?>" style="text-decoration: none;" data-bs-toggle="tooltip" title="Send email to <?= htmlspecialchars($contact['email']); ?>"><?= htmlspecialchars($contact['email']); ?></a></td>
                                        <td data-bs-toggle="tooltip" title="Contact phone"><?= htmlspecialchars($contact['phone']); ?></td>
                                        <td data-bs-toggle="tooltip" title="Contact address"><?= htmlspecialchars($contact['address']); ?></td>
                                        <td data-bs-toggle="tooltip" title="Company name"><?= htmlspecialchars($contact['company']); ?></td>
                                        <td data-bs-toggle="tooltip" title="Job title"><?= htmlspecialchars($contact['job_title']); ?></td>
                                        <td>
                                            <button onclick="editContact(<?= $contact['id']; ?>)" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit Contact"><i class="bi bi-pencil"></i> Edit</button>
                                            <form action="contacts.php" method="post" style="display:inline-block;">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?= $contact['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Delete Contact"><i class="bi bi-trash"></i> Delete</button>
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

<!-- Add/Edit Contact Modal -->
<div class="modal fade" id="addEditContactModal" tabindex="-1" aria-labelledby="addEditContactModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEditContactModalLabel">Add/Edit Contact</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addEditContactForm" action="contacts.php" method="post">
                <div class="modal-body">
                    <input type="hidden" name="action" id="contactAction" value="add">
                    <input type="hidden" name="id" id="contactId">
                    <div class="mb-3">
                        <label for="name" class="form-label" data-bs-toggle="tooltip" title="Enter the contact's name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label" data-bs-toggle="tooltip" title="Enter the contact's email address">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label" data-bs-toggle="tooltip" title="Enter the contact's phone number">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone">
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label" data-bs-toggle="tooltip" title="Enter the contact's address">Address</label>
                        <input type="text" class="form-control" id="address" name="address">
                    </div>
                    <div class="mb-3">
                        <label for="company" class="form-label" data-bs-toggle="tooltip" title="Enter the contact's company">Company</label>
                        <input type="text" class="form-control" id="company" name="company">
                    </div>
                    <div class="mb-3">
                        <label for="job_title" class="form-label" data-bs-toggle="tooltip" title="Enter the contact's job title">Job Title</label>
                        <input type="text" class="form-control" id="job_title" name="job_title">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" data-bs-toggle="tooltip" title="Save contact details">Save Contact</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editContact(id) {
    const contact = <?= json_encode($contacts); ?>.find(contact => contact.id == id);
    if (contact) {
        document.getElementById('contactAction').value = 'edit';
        document.getElementById('contactId').value = contact.id;
        document.getElementById('name').value = contact.name;
        document.getElementById('email').value = contact.email;
        document.getElementById('phone').value = contact.phone;
        document.getElementById('address').value = contact.address;
        document.getElementById('company').value = contact.company;
        document.getElementById('job_title').value = contact.job_title;
        document.getElementById('addEditContactModalLabel').innerText = 'Edit Contact';
        new bootstrap.Modal(document.getElementById('addEditContactModal')).show();
    }
}
</script>

<?php
    include($_SERVER['DOCUMENT_ROOT'] . "/app/includes/inc-app-footer.php");
    ob_end_flush();
?>