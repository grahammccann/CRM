<?php
    ob_start();
    include($_SERVER['DOCUMENT_ROOT'] . "/app/includes/inc-app-header.php");
?>

<?php
    // Fetch reports for the logged-in user
    $user_id = $_SESSION['user_id'];
    $reports = DB::getInstance()->select("SELECT * FROM reports WHERE user_id = :user_id", ['user_id' => $user_id]);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';
        $id = $_POST['id'] ?? 0;

        if ($action === 'delete' && $id) {
            $delete_status = DB::getInstance()->delete('reports', 'id', $id);
            if ($delete_status) {
                $_SESSION['success'] = "Report deleted successfully.";
            } else {
                $_SESSION['error'] = "Failed to delete report.";
            }
            header('Location: reports.php');
            exit;
        } else {
            $report_name = trim($_POST['report_name']);
            $report_type = trim($_POST['report_type']);
            
            $fields = [
                'user_id' => $user_id,
                'report_name' => $report_name,
                'report_type' => $report_type
            ];

            $insert_status = DB::getInstance()->insert('reports', $fields);
            if ($insert_status) {
                $_SESSION['success'] = "Report generated successfully.";
            } else {
                $_SESSION['error'] = "Failed to generate report.";
            }
            header('Location: reports.php');
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
                            <h3>Reports</h3>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEditReportModal" data-bs-toggle="tooltip" title="Generate a new report" style="position: absolute; right: 20px;"><i class="bi bi-file-earmark-plus"></i> Generate Report</button>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Report Name</th>
                                        <th>Report Type</th>
                                        <th>Date Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($reports as $report): ?>
                                    <tr>
                                        <td data-bs-toggle="tooltip" title="Report ID"><?= htmlspecialchars($report['id']); ?></td>
                                        <td data-bs-toggle="tooltip" title="Name of the report"><?= htmlspecialchars($report['report_name']); ?></td>
                                        <td data-bs-toggle="tooltip" title="Type of the report"><?= htmlspecialchars($report['report_type']); ?></td>
                                        <td data-bs-toggle="tooltip" title="Date the report was created"><?= htmlspecialchars($report['created_at']); ?></td>
                                        <td>
                                            <a href="view_report.php?id=<?= $report['id']; ?>" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="View Report"><i class="bi bi-eye"></i> View</a>
                                            <form action="reports.php" method="post" style="display:inline-block;">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?= $report['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Delete Report"><i class="bi bi-trash"></i> Delete</button>
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

<!-- Add/Edit Report Modal -->
<div class="modal fade" id="addEditReportModal" tabindex="-1" aria-labelledby="addEditReportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEditReportModalLabel">Generate Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addEditReportForm" action="reports.php" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="report_name" class="form-label" data-bs-toggle="tooltip" title="Enter the name of the report">Report Name</label>
                        <input type="text" class="form-control" id="report_name" name="report_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="report_type" class="form-label" data-bs-toggle="tooltip" title="Select the type of report">Report Type</label>
                        <select class="form-control" id="report_type" name="report_type" required>
                            <option value="sales">Sales Report</option>
                            <option value="customers">Customer Report</option>
                            <option value="leads">Leads Report</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary">Close</button>
                    <button type="submit" class="btn btn-primary" data-bs-toggle="tooltip" title="Generate the report">Generate Report</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
    include($_SERVER['DOCUMENT_ROOT'] . "/app/includes/inc-app-footer.php");
    ob_end_flush();
?>