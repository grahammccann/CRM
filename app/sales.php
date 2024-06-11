<?php
include($_SERVER['DOCUMENT_ROOT'] . "/app/includes/inc-app-header.php");

if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$sales = DB::getInstance()->select("SELECT * FROM sales WHERE user_id = :user_id", ['user_id' => $user_id]);

// Handle form submissions for add, edit, and delete
?>

<div class="container">
    <h2>Sales Pipeline</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEditSaleModal">Add Sale</button>
    <table class="table">
        <thead>
            <tr>
                <th>Deal Name</th>
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
                    <td><?= htmlspecialchars($sale['deal_name']); ?></td>
                    <td><?= htmlspecialchars($sale['contact_id']); ?></td>
                    <td><?= htmlspecialchars($sale['deal_value']); ?></td>
                    <td><?= htmlspecialchars($sale['deal_stage']); ?></td>
                    <td><?= htmlspecialchars($sale['close_date']); ?></td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="editSale(<?= $sale['id']; ?>)">Edit</button>
                        <form method="POST" style="display:inline-block;">
                            <input type="hidden" name="id" value="<?= $sale['id']; ?>">
                            <input type="hidden" name="action" value="delete">
                            <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal for adding/editing sales -->
<div class="modal fade" id="addEditSaleModal" tabindex="-1" aria-labelledby="addEditSaleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEditSaleModalLabel">Add Sale</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="add" id="saleAction">
                    <input type="hidden" name="id" id="saleId">
                    <div class="mb-3">
                        <label for="deal_name" class="form-label">Deal Name</label>
                        <input type="text" class="form-control" id="deal_name" name="deal_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="contact_id" class="form-label">Contact</label>
                        <select class="form-control" id="contact_id" name="contact_id" required>
                            <?php foreach ($contacts as $contact): ?>
                                <option value="<?= $contact['id']; ?>"><?= $contact['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="deal_value" class="form-label">Deal Value</label>
                        <input type="number" class="form-control" id="deal_value" name="deal_value" required>
                    </div>
                    <div class="mb-3">
                        <label for="deal_stage" class="form-label">Deal Stage</label>
                        <input type="text" class="form-control" id="deal_stage" name="deal_stage" required>
                    </div>
                    <div class="mb-3">
                        <label for="close_date" class="form-label">Close Date</label>
                        <input type="date" class="form-control" id="close_date" name="close_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Sale</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editSale(id) {
    const sale = <?= json_encode($sales); ?>.find(sale => sale.id == id);
    if (sale) {
        document.getElementById('saleAction').value = 'edit';
        document.getElementById('saleId').value = sale.id;
        document.getElementById('deal_name').value = sale.deal_name;
        document.getElementById('contact_id').value = sale.contact_id;
        document.getElementById('deal_value').value = sale.deal_value;
        document.getElementById('deal_stage').value = sale.deal_stage;
        document.getElementById('close_date').value = sale.close_date;
        document.getElementById('notes').value = sale.notes;
        document.getElementById('addEditSaleModalLabel').innerText = 'Edit Sale';
        new bootstrap.Modal(document.getElementById('addEditSaleModal')).show();
    }
}
</script>

<?php
include($_SERVER['DOCUMENT_ROOT'] . "/app/includes/inc-app-footer.php");
?>