<?php
    include($_SERVER['DOCUMENT_ROOT'] . "/app/includes/inc-app-header.php");
?>

<?php
    // Fetch user's subscription plan
    $user_id = $_SESSION['user_id'];
    $subscription = DB::getInstance()->selectOne("SELECT plan_id FROM subscriptions WHERE user_id = :user_id AND status = 'active'", ['user_id' => $user_id]);

    // Determine the subscription package and corresponding colour
    $plan = $subscription ? $subscription['plan_id'] : 'No active subscription';
    $plan_color = '';
    switch ($plan) {
        case 'Basic':
            $plan_color = 'bg-primary';
            break;
        case 'Standard':
            $plan_color = 'bg-success';
            break;
        case 'Premium':
            $plan_color = 'bg-warning';
            break;
        default:
            $plan_color = 'bg-secondary';
            break;
    }
?>

<div class="container-fluid">

    <!-- Main content for the index page -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3>Welcome to your Dashboard</h3>
                </div>
                <div class="card-body">
                    <p>You are successfully logged in as <strong><?php echo htmlspecialchars($user_info['name']); ?></strong>. Member since: <?php echo date('F j, Y', strtotime($user_info['created_at'])); ?></p>
                    <div class="alert <?php echo $plan_color; ?> text-white" role="alert">
                        Your current subscription package: <strong><?php echo htmlspecialchars($plan); ?></strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if ($plan !== 'No active subscription'): ?>
        <!-- User actions list -->
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h4>User Actions</h4>
                    </div>
                    <div class="card-body">
                        <!-- User actions content -->
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php
    include($_SERVER['DOCUMENT_ROOT'] . "/app/includes/inc-app-footer.php");
?>