<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function clean_input($data) {
    return htmlspecialchars(trim($data));
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function format_price($price) {
    return "₱" . number_format($price, 2);
}

function cart_count() {
    if (!isset($_SESSION['cart'])) {
        return 0;
    }

    $count = 0;

    foreach ($_SESSION['cart'] as $quantity) {
        $count += $quantity;
    }

    return $count;
}

function require_admin() {
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header("Location: ../login.php");
        exit();
    }
}

function log_activity($conn, $action, $description) {
    $user_id = $_SESSION['user_id'] ?? null;

    if ($user_id === null) {
        $stmt = mysqli_prepare($conn, "INSERT INTO audit_logs (action, description) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, "ss", $action, $description);
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO audit_logs (user_id, action, description) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "iss", $user_id, $action, $description);
    }

    mysqli_stmt_execute($stmt);
}

function seller_header($page_title, $active_page) {
    $admin_name = $_SESSION['complete_name'] ?? 'Admin';

    $links = [
        ['dashboard', 'dashboard.php', 'Dashboard', '🏠'],
        ['products', 'manageProducts.php', 'Products', '🧩'],
        ['users', 'manageUsers.php', 'Users', '👥'],
        ['inventory', 'inventoryReport.php', 'Inventory', '📦'],
        ['audit', 'auditLog.php', 'Audit Log', '📝']
    ];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title); ?> | AJJR Seller</title>
    <link href="../output.css" rel="stylesheet">
</head>

<body class="bg-slate-950 text-white min-h-screen flex flex-col">


<header class="bg-slate-900 border-b border-slate-800">

    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

        <div class="flex items-center gap-3">

            <div class="w-12 h-12 rounded-2xl bg-cyan-400 text-slate-950 flex items-center justify-center font-black text-xl">
                AJ
            </div>

            <div>
                <h1 class="font-black text-lg leading-none">
                    AJJR Seller
                </h1>

                <p class="text-xs text-slate-400 mt-1">
                    Admin Panel
                </p>
            </div>

        </div>


        <div class="flex items-center gap-4">

            <div class="hidden md:block text-right">
                <p class="font-bold">
                    <?= htmlspecialchars($admin_name); ?>
                </p>

                <p class="text-xs text-slate-400">
                    Administrator
                </p>
            </div>


            <a href="../logout.php"
            class="bg-red-500 hover:bg-red-400 text-white px-4 py-2 rounded-xl font-bold transition">
                Logout
            </a>

        </div>

    </div>



    <nav class="max-w-7xl mx-auto px-6 pb-4 flex gap-3 overflow-x-auto">

        <?php foreach ($links as $link) { 

            $is_active = $active_page === $link[0];

        ?>

        <a href="<?= $link[1]; ?>"
        class="whitespace-nowrap px-5 py-3 rounded-xl font-bold transition
        <?= $is_active 
            ? 'bg-cyan-400 text-slate-950' 
            : 'bg-slate-800 text-slate-300 hover:bg-slate-700 hover:text-cyan-400'; ?>">

            <?= $link[3]; ?>
            <?= $link[2]; ?>

        </a>

        <?php } ?>

    </nav>

</header>



<main class="w-full flex-grow">

    <div class="max-w-7xl mx-auto p-6">

<?php
}


function seller_footer() {
?>
    </div>

    <footer class="mt-20 border-t border-slate-800 bg-slate-950">

        <div class="max-w-7xl mx-auto px-6 py-10 text-center">

            <h2 class="text-xl font-black text-cyan-400">
                AJJR PC Parts
            </h2>

            <p class="text-slate-400 text-sm mt-2">
                This website is for educational purposes only and is a requirement for our final project.
            </p>

            <p class="text-slate-600 text-xs mt-5">
                © <?php echo date("Y"); ?> AJJR PC Parts. All rights reserved.
            </p>

        </div>

    </footer>

</main>


</body>
</html>

<?php
}
?>
