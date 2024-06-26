<?php
ob_start(); // Ensure no output before headers are modified
include($_SERVER['DOCUMENT_ROOT'] . "/app/includes/inc-app-header.php");

// Fetch sales for the logged-in user
$user_id = $_SESSION['user_id'];
$sales = DB::getInstance()->select("SELECT * FROM sales WHERE user_id = :user_id", ['user_id' => $user_id]);

// Fetch contacts for the dropdown
$contacts = DB::getInstance()->select("SELECT id, name FROM contacts WHERE user_id = :user_id", ['user_id' => $user_id]);

$sale_to_edit = null;
if (isset($_GET['edit_id'])) {
    $sale_to_edit = DB::getInstance()->selectOne("SELECT * FROM sales WHERE id = :id AND user_id = :user_id", ['id' => $_GET['edit_id'], 'user_id' => $user_id]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = $_POST['id'] ?? 0;

    if ($action === 'delete' && $id) {
        $delete_status = DB::getInstance()->delete('sales', 'id', $id);
        if ($delete_status) {
            $_SESSION['success'] = "Sale deleted successfully.";
        } else {
            $_SESSION['error'] = "Failed to delete sale.";
        }
        header('Location: sales.php');
        exit;
    } elseif ($action === 'add' || $action === 'edit') {
        $sale_name = trim($_POST['sale_name']);
        $contact_id = trim($_POST['contact_id']);
        $sale_value = trim($_POST['sale_value']);
        $sale_stage = trim($_POST['sale_stage']);
        $close_date = trim($_POST['close_date']);
        $notes = trim($_POST['notes']);

        $fields = [
            'user_id' => $user_id,
            'sale_name' => $sale_name,
            'contact_id' => $contact_id,
            'sale_value' => $sale_value,
            'sale_stage' => $sale_stage,
            'close_date' => $close_date,
            'notes' => $notes,
        ];

        if ($action === 'add') {
            $insert_status = DB::getInstance()->insert('sales', $fields);
            if ($insert_status) {
                $_SESSION['success'] = "Sale added successfully.";
            } else {
                $_SESSION['error'] = "Failed to add sale.";
            }
        } elseif ($action === 'edit' && $id) {
            $update_status = DB::getInstance()->update('sales', 'id', $id, $fields);
            if ($update_status) {
                $_SESSION['success'] = "Sale updated successfully.";
            } else {
                $_SESSION['error'] = "Failed to update sale.";
            }
        }
        header('Location: sales.php');
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
                            <h3>Sales</h3>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEditSaleModal" data-bs-toggle="tooltip" title="Click to add a new sale" style="position: absolute; right: 20px;"><i class="bi bi-plus-lg"></i> Add Sale</button>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Sale Name</th>
                                        <th>Contact</th>
                                        <th>Value</th>
                                        <th>Stage</th>
                                        <th>Close Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($sales as $sale): ?>
                                    <tr>
                                        <td data-bs-toggle="tooltip" title="Sale ID"><?= htmlspecialchars($sale['id']); ?></td>
                                        <td data-bs-toggle="tooltip" title="Sale name"><?= htmlspecialchars($sale['sale_name']); ?></td>
                                        <td data-bs-toggle="tooltip" title="Contact ID"><?= htmlspecialchars($sale['contact_id']); ?></td>
                                        <td data-bs-toggle="tooltip" title="Sale value"><?= htmlspecialchars($sale['sale_value']); ?></td>
                                        <td data-bs-toggle="tooltip" title="Sale stage"><?= htmlspecialchars($sale['sale_stage']); ?></td>
                                        <td data-bs-toggle="tooltip" title="Close date"><?= htmlspecialchars($sale['close_date']); ?></td>
                                        <td>
                                            <button onclick="editSale(<?= $sale['id']; ?>)" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit Sale"><i class="bi bi-pencil"></i> Edit</button>
                                            <form action="sales.php" method="post" style="display:inline-block;">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?= $sale['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Delete Sale"><i class="bi bi-trash"></i> Delete</button>
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

<!-- Add/Edit Sale Modal -->
<div class="modal fade" id="addEditSaleModal" tabindex="-1" aria-labelledby="addEditSaleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEditSaleModalLabel">Add/Edit Sale</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addEditSaleForm" action="sales.php" method="post">
                <div class="modal-body">
                    <input type="hidden" name="action" id="saleAction" value="add">
                    <input type="hidden" name="id" id="saleId">
                    <div class="mb-3">
                        <label for="sale_name" class="form-label" data-bs-toggle="tooltip" title="Enter the sale name">Sale Name</label>
                        <input type="text" class="form-control" id="sale_name" name="sale_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="contact_id" class="form-label" data-bs-toggle="tooltip" title="Select a contact">Contact</label>
                        <select class="form-control" id="contact_id" name="contact_id" required>
                            <?php foreach ($contacts as $contact): ?>
                                <option value="<?= $contact['id']; ?>"><?= htmlspecialchars($contact['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="sale_value" class="form-label" data-bs-toggle="tooltip" title="Enter the sale value">Sale Value</label>
                        <input type="number" class="form-control" id="sale_value" name="sale_value" required>
                    </div>
                    <div class="mb-3">
                        <label for="sale_stage" class="form-label" data-bs-toggle="tooltip" title="Enter the sale stage">Sale Stage</label>
                        <input type="text" class="form-control" id="sale_stage" name="sale_stage" required>
                    </div>
                    <div class="mb-3">
                        <label for="close_date" class="form-label" data-bs-toggle="tooltip" title="Enter the close date">Close Date</label>
                        <input type="date" class="form-control" id="close_date" name="close_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label" data-bs-toggle="tooltip" title="Enter any notes">Notes</label>
                        <textarea class="form-control" id="notes" name="notes"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" data-bs-toggle="tooltip" title="Save the sale details">Save Sale</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editSale(id) {
    const sales = <?= json_encode($sales); ?>;
    const sale = sales.find(sale => sale.id == id);
    if (sale) {
        document.getElementById('saleAction').value = 'edit';
        document.getElementById('saleId').value = sale.id;
        document.getElementById('sale_name').value = sale.sale_name;
        document.getElementById('contact_id').value = sale.contact_id;
        document.getElementById('sale_value').value = sale.sale_value;
        document.getElementById('sale_stage').value = sale.sale_stage;
        document.getElementById('close_date').value = sale.close_date;
        document.getElementById('notes').value = sale.notes;
        document.getElementById('addEditSaleModalLabel').innerText = 'Edit Sale';
        new bootstrap.Modal(document.getElementById('addEditSaleModal')).show();
    }
}
</script>
 
<?php
include($_SERVER['DOCUMENT_ROOT'] . "/app/includes/inc-app-footer.php");
ob_end_flush();
?>