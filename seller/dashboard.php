<?php
require_once __DIR__ . '/../includes/databaseConnect.php';
require_once __DIR__ . '/../includes/functions.php';

require_admin();

function get_count($conn, $sql) {
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        return 0;
    }

    $row = mysqli_fetch_assoc($result);
    return $row['total'] ?? 0;
}

$total_products = get_count($conn, "SELECT COUNT(*) AS total FROM products");
$total_users = get_count($conn, "SELECT COUNT(*) AS total FROM users");
$total_categories = get_count($conn, "SELECT COUNT(*) AS total FROM categories");
$total_orders = get_count($conn, "SELECT COUNT(*) AS total FROM orders");
$low_stock = get_count($conn, "SELECT COUNT(*) AS total FROM products WHERE stock <= 5");

$recent_logs = mysqli_query($conn, "
    SELECT audit_logs.*, TRIM(CONCAT_WS(' ', users.first_name, NULLIF(users.middle_name, ''), users.last_name)) AS complete_name
    FROM audit_logs
    LEFT JOIN users ON audit_logs.user_id = users.user_id
    ORDER BY audit_logs.created_at DESC
    LIMIT 6
");

seller_header("Dashboard", "dashboard");
?>

<div class="mb-8">
    <p class="text-cyan-400 font-semibold">Seller Overview</p>
    <h1 class="text-4xl font-black mt-2">Admin Dashboard</h1>
    <p class="text-slate-400 mt-3">Manage products, users, inventory, and audit logs.</p>
</div>

<div class="grid sm:grid-cols-2 xl:grid-cols-5 gap-5 mb-10">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6">
        <p class="text-slate-400 text-sm">Products</p>
        <h2 class="text-4xl font-black text-cyan-400 mt-2"><?= $total_products; ?></h2>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6">
        <p class="text-slate-400 text-sm">Users</p>
        <h2 class="text-4xl font-black text-cyan-400 mt-2"><?= $total_users; ?></h2>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6">
        <p class="text-slate-400 text-sm">Categories</p>
        <h2 class="text-4xl font-black text-cyan-400 mt-2"><?= $total_categories; ?></h2>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6">
        <p class="text-slate-400 text-sm">Orders</p>
        <h2 class="text-4xl font-black text-cyan-400 mt-2"><?= $total_orders; ?></h2>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6">
        <p class="text-slate-400 text-sm">Low Stock</p>
        <h2 class="text-4xl font-black text-red-400 mt-2"><?= $low_stock; ?></h2>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 bg-slate-900 border border-slate-800 rounded-3xl p-6">
        <h2 class="text-2xl font-black mb-5">Quick Actions</h2>

        <div class="grid sm:grid-cols-2 gap-4">
            <a href="addProduct.php" class="bg-slate-950 border border-slate-800 hover:border-cyan-400 rounded-2xl p-5 transition">
                <p class="text-3xl mb-3">➕</p>
                <h3 class="font-black text-lg">Add Product</h3>
                <p class="text-slate-400 text-sm mt-1">Create a new product listing.</p>
            </a>

            <a href="addUser.php" class="bg-slate-950 border border-slate-800 hover:border-cyan-400 rounded-2xl p-5 transition">
                <p class="text-3xl mb-3">👤</p>
                <h3 class="font-black text-lg">Add User</h3>
                <p class="text-slate-400 text-sm mt-1">Create buyer or admin accounts.</p>
            </a>

            <a href="inventoryReport.php" class="bg-slate-950 border border-slate-800 hover:border-cyan-400 rounded-2xl p-5 transition">
                <p class="text-3xl mb-3">📦</p>
                <h3 class="font-black text-lg">Inventory Report</h3>
                <p class="text-slate-400 text-sm mt-1">View remaining stocks.</p>
            </a>

            <a href="auditLog.php" class="bg-slate-950 border border-slate-800 hover:border-cyan-400 rounded-2xl p-5 transition">
                <p class="text-3xl mb-3">📝</p>
                <h3 class="font-black text-lg">Audit Log</h3>
                <p class="text-slate-400 text-sm mt-1">View system activities.</p>
            </a>
        </div>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6">
        <h2 class="text-2xl font-black mb-5">Recent Activities</h2>

        <div class="space-y-4">
            <?php if ($recent_logs && mysqli_num_rows($recent_logs) > 0) { ?>
                <?php while ($log = mysqli_fetch_assoc($recent_logs)) { ?>
                    <div class="border-b border-slate-800 pb-4">
                        <p class="font-bold text-cyan-400"><?= htmlspecialchars($log['action']); ?></p>
                        <p class="text-sm text-slate-400 mt-1"><?= htmlspecialchars($log['description']); ?></p>
                        <p class="text-xs text-slate-600 mt-2">
                            <?= htmlspecialchars($log['complete_name'] ?? 'Unknown User'); ?> • <?= $log['created_at']; ?>
                        </p>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <p class="text-slate-400">No recent activities yet.</p>
            <?php } ?>
        </div>
    </div>
</div>

<?php seller_footer(); ?>