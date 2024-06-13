<?php
    ob_start();
    include($_SERVER['DOCUMENT_ROOT'] . "/app/includes/inc-app-header.php");

    // Fetch leads for the logged-in user
    $user_id = $_SESSION['user_id'];
    $leads = DB::getInstance()->select("SELECT * FROM leads WHERE user_id = :user_id", ['user_id' => $user_id]);

    $lead_to_edit = null;
    if (isset($_GET['edit_id'])) {
        $lead_to_edit = DB::getInstance()->selectOne("SELECT * FROM leads WHERE id = :id AND user_id = :user_id", ['id' => $_GET['edit_id'], 'user_id' => $user_id]);
    }
?>

<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = $_POST['id'] ?? 0;

    if ($action === 'delete' && $id) {
        $delete_status = DB::getInstance()->delete('leads', 'id', $id);
        if ($delete_status) {
            $_SESSION['success'] = "Lead deleted successfully.";
        } else {
            $_SESSION['error'] = "Failed to delete lead.";
        }
        header('Location: leads.php');
        exit;
    } elseif ($action === 'add' || $action === 'edit') {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $status = trim($_POST['status']);
        $source = trim($_POST['source']);

        $fields = [
            'user_id' => $user_id,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'status' => $status,
            'source' => $source
        ];

        if ($action === 'add') {
            $insert_status = DB::getInstance()->insert('leads', $fields);
            if ($insert_status) {
                $_SESSION['success'] = "Lead added successfully.";
            } else {
                $_SESSION['error'] = "Failed to add lead.";
            }
        } elseif ($action === 'edit' && $id) {
            $update_status = DB::getInstance()->update('leads', 'id', $id, $fields);
            if ($update_status) {
                $_SESSION['success'] = "Lead updated successfully.";
            } else {
                $_SESSION['error'] = "Failed to update lead.";
            }
        }
        header('Location: leads.php');
        exit;
    }
}

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
                            <h3>Leads</h3>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEditLeadModal" data-bs-toggle="tooltip" title="Click to add a new lead" style="position: absolute; right: 20px;"><i class="bi bi-person-plus"></i> Add Lead</button>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        <th>Notes</th>
                                        <th>Date Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($leads as $lead): ?>
                                    <tr>
                                        <td data-bs-toggle="tooltip" title="Lead ID"><?= htmlspecialchars($lead['id']); ?></td>
                                        <td data-bs-toggle="tooltip" title="Lead name"><?= htmlspecialchars($lead['name']); ?></td>
                                        <td><a href="mailto:<?= htmlspecialchars($lead['email']); ?>" style="text-decoration: none;" data-bs-toggle="tooltip" title="Send email to <?= htmlspecialchars($lead['email']); ?>"><?= htmlspecialchars($lead['email']); ?></a></td>
                                        <td data-bs-toggle="tooltip" title="Lead phone"><?= htmlspecialchars($lead['phone']); ?></td>
                                        <td>
                                            <?php
                                                $statusClass = '';
                                                switch ($lead['status']) {
                                                    case 'new':
                                                        $statusClass = 'badge bg-primary';
                                                        break;
                                                    case 'contacted':
                                                        $statusClass = 'badge bg-info';
                                                        break;
                                                    case 'qualified':
                                                        $statusClass = 'badge bg-success';
                                                        break;
                                                    case 'lost':
                                                        $statusClass = 'badge bg-danger';
                                                        break;
                                                    case 'unqualified':
                                                        $statusClass = 'badge bg-warning';
                                                        break;
                                                    default:
                                                        $statusClass = 'badge bg-secondary';
                                                }
                                            ?>
                                            <span class="<?= $statusClass; ?>" data-bs-toggle="tooltip" title="Lead status"><?= htmlspecialchars($lead['status']); ?></span>
                                        </td>
                                        <td data-bs-toggle="tooltip" title="Lead notes"><?= htmlspecialchars($lead['notes'] ?? ''); ?></td>
                                        <td data-bs-toggle="tooltip" title="Date lead was created"><?= htmlspecialchars($lead['created_at'] ?? ''); ?></td>
                                        <td>
                                            <button onclick="editLead(<?= $lead['id']; ?>)" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit Lead"><i class="bi bi-pencil"></i> Edit</button>
                                            <form action="leads.php" method="post" style="display:inline-block;">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?= $lead['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Delete Lead"><i class="bi bi-trash"></i> Delete</button>
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

<!-- Add/Edit Lead Modal -->
<div class="modal fade" id="addEditLeadModal" tabindex="-1" aria-labelledby="addEditLeadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEditLeadModalLabel">Add/Edit Lead</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addEditLeadForm" action="leads.php" method="post">
                <div class="modal-body">
                    <input type="hidden" name="action" id="leadAction" value="add">
                    <input type="hidden" name="id" id="leadId">
                    <div class="mb-3">
                        <label for="name" class="form-label" data-bs-toggle="tooltip" title="Enter the lead's name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label" data-bs-toggle="tooltip" title="Enter the lead's email address">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label" data-bs-toggle="tooltip" title="Enter the lead's phone number">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone">
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label" data-bs-toggle="tooltip" title="Select the lead's status">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="new">New</option>
                            <option value="contacted">Contacted</option>
                            <option value="qualified">Qualified</option>
                            <option value="lost">Lost</option>
                            <option value="unqualified">Unqualified</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label" data-bs-toggle="tooltip" title="Add any notes about the lead">Notes</label>
                        <textarea class="form-control" id="notes" name="notes"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" data-bs-toggle="tooltip" title="Save lead details">Save Lead</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add/Edit Lead Modal -->
<div class="modal fade" id="addEditLeadModal" tabindex="-1" aria-labelledby="addEditLeadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEditLeadModalLabel">Add/Edit Lead</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addEditLeadForm" action="leads.php" method="post">
                <div class="modal-body">
                    <input type="hidden" name="action" id="leadAction" value="add">
                    <input type="hidden" name="id" id="leadId">
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
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="new">New</option>
                            <option value="contacted">Contacted</option>
                            <option value="qualified">Qualified</option>
                            <option value="lost">Lost</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="source" class="form-label">Source</label>
                        <input type="text" class="form-control" id="source" name="source">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Lead</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editLead(id) {
    const lead = <?= json_encode($leads); ?>.find(lead => lead.id == id);
    if (lead) {
        document.getElementById('leadAction').value = 'edit';
        document.getElementById('leadId').value = lead.id;
        document.getElementById('name').value = lead.name;
        document.getElementById('email').value = lead.email;
        document.getElementById('phone').value = lead.phone;
        document.getElementById('status').value = lead.status;
        document.getElementById('source').value = lead.source;
        document.getElementById('addEditLeadModalLabel').innerText = 'Edit Lead';
        new bootstrap.Modal(document.getElementById('addEditLeadModal')).show();
    }
}
</script>

<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/app/includes/inc-app-footer.php");
	ob_end_flush();
?>