<?php
    ob_start();
    include($_SERVER['DOCUMENT_ROOT'] . "/app/includes/inc-app-header.php");

    // Fetch customers for the logged-in user
    $user_id = $_SESSION['user_id'];
    $customers = DB::getInstance()->select("SELECT * FROM customers WHERE user_id = :user_id", ['user_id' => $user_id]);

    $customer_to_edit = null;
    if (isset($_GET['edit_id'])) {
        $customer_to_edit = DB::getInstance()->selectOne("SELECT * FROM customers WHERE id = :id AND user_id = :user_id", ['id' => $_GET['edit_id'], 'user_id' => $user_id]);
    }
?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = $_POST['id'] ?? 0;

    if ($action === 'delete' && $id) {
        $delete_status = DB::getInstance()->delete('customers', 'id', $id);
        if ($delete_status) {
            $_SESSION['success'] = "Customer deleted successfully.";
        } else {
            $_SESSION['error'] = "Failed to delete customer.";
        }
        header('Location: customers.php');
        exit;
    } elseif ($action === 'add' || $action === 'edit') {
        $company_name = trim($_POST['company_name']);
        $primary_contact = trim($_POST['primary_contact']);
        $primary_email = trim($_POST['primary_email']);
        $phone = trim($_POST['phone']);
        $groups = trim($_POST['groups']);
        $active = isset($_POST['active']) ? 1 : 0;

        $fields = [
            'user_id' => $user_id,
            'company_name' => $company_name,
            'primary_contact' => $primary_contact,
            'primary_email' => $primary_email,
            'phone' => $phone,
            'groups' => $groups,
            'active' => $active
        ];

        if ($action === 'add') {
            $insert_status = DB::getInstance()->insert('customers', $fields);
            if ($insert_status) {
                $_SESSION['success'] = "Customer added successfully.";
            } else {
                $_SESSION['error'] = "Failed to add customer.";
            }
        } elseif ($action === 'edit' && $id) {
            $update_status = DB::getInstance()->update('customers', 'id', $id, $fields);
            if ($update_status) {
                $_SESSION['success'] = "Customer updated successfully.";
            } else {
                $_SESSION['error'] = "Failed to update customer.";
            }
        }
        header('Location: customers.php');
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
                <i class="bi bi-check-circle"></i> <?= htmlspecialchars($_SESSION['success']) ?>
                <?php unset($_SESSION['success']); ?>
            </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-circle"></i> <?= htmlspecialchars($_SESSION['error']) ?>
                <?php unset($_SESSION['error']); ?>
            </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-12">
                    <div class="card mt-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3>Customers</h3>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEditCustomerModal" style="position: absolute; right: 20px;"><i class="bi bi-person-plus"></i> Add Customer</button>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Company</th>
                                        <th>Primary Contact</th>
                                        <th>Primary Email</th>
                                        <th>Phone</th>
                                        <th>Active</th>
                                        <th>Groups</th>
                                        <th>Date Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($customers as $customer): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($customer['id']); ?></td>
                                        <td><?= htmlspecialchars($customer['company_name']); ?></td>
                                        <td><?= htmlspecialchars($customer['primary_contact']); ?></td>
                                        <td><a href="mailto:<?= htmlspecialchars($customer['primary_email']); ?>" style="text-decoration: none;"><?= htmlspecialchars($customer['primary_email']); ?></a></td>
                                        <td><?= htmlspecialchars($customer['phone']); ?></td>
                                        <td>
                                            <?php if ($customer['active']): ?>
                                            <span class="badge bg-success">Active</span>
                                            <?php else: ?>
                                            <span class="badge bg-danger">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($customer['groups']); ?></td>
                                        <td><?= htmlspecialchars($customer['date_created']); ?></td>
                                        <td>
                                            <button onclick="editCustomer(<?= $customer['id']; ?>)" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit Customer"><i class="bi bi-pencil"></i> Edit</button>
                                            <form action="customers.php" method="post" style="display:inline-block;">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?= $customer['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Delete Customer"><i class="bi bi-trash"></i> Delete</button>
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

<!-- Add/Edit Customer Modal -->
<div class="modal fade" id="addEditCustomerModal" tabindex="-1" aria-labelledby="addEditCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEditCustomerModalLabel">Add/Edit Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addEditCustomerForm" action="customers.php" method="post">
                <div class="modal-body">
                    <input type="hidden" name="action" id="customerAction" value="add">
                    <input type="hidden" name="id" id="customerId">
                    <div class="mb-3">
                        <label for="company_name" class="form-label">Company</label>
                        <input type="text" class="form-control" id="company_name" name="company_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="primary_contact" class="form-label">Primary Contact</label>
                        <input type="text" class="form-control" id="primary_contact" name="primary_contact" required>
                    </div>
                    <div class="mb-3">
                        <label for="primary_email" class="form-label">Primary Email</label>
                        <input type="email" class="form-control" id="primary_email" name="primary_email" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone">
                    </div>
                    <div class="mb-3">
                        <label for="groups" class="form-label">Groups</label>
                        <input type="text" class="form-control" id="groups" name="groups">
                    </div>
                    <div class="mb-3">
                        <label for="active" class="form-label">Active</label>
                        <input type="checkbox" id="active" name="active" checked>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Customer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editCustomer(id) {
    const customer = <?= json_encode($customers); ?>.find(customer => customer.id == id);
    if (customer) {
        document.getElementById('customerAction').value = 'edit';
        document.getElementById('customerId').value = customer.id;
        document.getElementById('company_name').value = customer.company_name;
        document.getElementById('primary_contact').value = customer.primary_contact;
        document.getElementById('primary_email').value = customer.primary_email;
        document.getElementById('phone').value = customer.phone;
        document.getElementById('groups').value = customer.groups;
        document.getElementById('active').checked = customer.active;
        document.getElementById('addEditCustomerModalLabel').innerText = 'Edit Customer';
        new bootstrap.Modal(document.getElementById('addEditCustomerModal')).show();
    }
}
</script>

<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/app/includes/inc-app-footer.php");
	ob_end_flush();
?>
