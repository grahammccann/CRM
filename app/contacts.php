<?php
ob_start(); // Ensure no output before headers are modified
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
        $company_name = trim($_POST['company_name']);
        $job_title = trim($_POST['job_title']);
        $address = trim($_POST['address']);

        $fields = [
            'user_id' => $user_id,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'company_name' => $company_name,
            'job_title' => $job_title,
            'address' => $address,
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

?>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <!-- Display success or error messages -->
            <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <i class="bi bi-check-circle"></i> <?= $_SESSION['success'] ?>
                <?php unset($_SESSION['success']); ?>
            </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-circle"></i> <?= $_SESSION['error'] ?>
                <?php unset($_SESSION['error']); ?>
            </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-12">
                    <div class="card mt-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3>Contacts</h3>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEditContactModal" style="position: absolute; right: 20px;"><i class="bi bi-person-plus"></i> Add Contact</button>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Company</th>
                                        <th>Job Title</th>
                                        <th>Address</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($contacts as $contact): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($contact['name']); ?></td>
                                        <td><?= htmlspecialchars($contact['email']); ?></td>
                                        <td><?= htmlspecialchars($contact['phone']); ?></td>
                                        <td><?= htmlspecialchars($contact['company_name']); ?></td>
                                        <td><?= htmlspecialchars($contact['job_title']); ?></td>
                                        <td><?= htmlspecialchars($contact['address']); ?></td>
                                        <td>
                                            <button onclick="editContact(<?= $contact['id']; ?>)" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Edit</button>
                                            <form action="contacts.php" method="post" style="display:inline-block;">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?= $contact['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Delete</button>
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
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone">
                    </div>
                    <div class="mb-3">
                        <label for="company_name" class="form-label">Company</label>
                        <input type="text" class="form-control" id="company_name" name="company_name">
                    </div>
                    <div class="mb-3">
                        <label for="job_title" class="form-label">Job Title</label>
                        <input type="text" class="form-control" id="job_title" name="job_title">
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Contact</button>
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
        document.getElementById('company_name').value = contact.company_name;
        document.getElementById('job_title').value = contact.job_title;
        document.getElementById('address').value = contact.address;
        document.getElementById('addEditContactModalLabel').innerText = 'Edit Contact';
        new bootstrap.Modal(document.getElementById('addEditContactModal')).show();
    }
}
</script>

<?php
include($_SERVER['DOCUMENT_ROOT'] . "/app/includes/inc-app-footer.php");
ob_end_flush(); // Ensure output buffering is flushed after all processing
?>
