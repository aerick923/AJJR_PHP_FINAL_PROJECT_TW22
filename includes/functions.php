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

<body class="bg-slate-950 text-white min-h-screen">

<div class="min-h-screen flex">

    <aside class="hidden lg:flex lg:w-72 bg-slate-900 border-r border-slate-800 flex-col fixed inset-y-0">

        <div class="p-6 border-b border-slate-800">
            <a href="dashboard.php" class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-cyan-400 text-slate-950 flex items-center justify-center font-black text-xl">
                    AJ
                </div>

                <div>
                    <h1 class="font-black text-lg leading-none">AJJR Seller</h1>
                    <p class="text-xs text-slate-400 mt-1">Admin Panel</p>
                </div>
            </a>
        </div>


        <nav class="flex-1 p-4 space-y-2">

            <?php foreach ($links as $link) { 
                $is_active = $active_page === $link[0];
            ?>

                <a href="<?= $link[1]; ?>" 
                class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold transition
                <?= $is_active ? 'bg-cyan-400 text-slate-950' : 'text-slate-300 hover:bg-slate-800 hover:text-cyan-400'; ?>">

                    <span><?= $link[3]; ?></span>
                    <span><?= $link[2]; ?></span>

                </a>

            <?php } ?>

        </nav>


        <div class="p-4 border-t border-slate-800">

            <a href="../store.php" 
            class="block text-center border border-slate-700 hover:border-cyan-400 px-4 py-3 rounded-xl font-bold transition mb-3">
                View Store
            </a>

            <a href="../logout.php" 
            class="block text-center bg-red-500 hover:bg-red-400 text-white px-4 py-3 rounded-xl font-bold transition">
                Logout
            </a>

        </div>

    </aside>



    <main class="flex-1 lg:ml-72 flex justify-center">


        <header class="sticky top-0 z-40 bg-slate-950/80 backdrop-blur-lg border-b border-slate-800">

            <div class="px-6 py-4 w-full max-w-7xl mx-auto flex items-center justify-between">

                <div>
                    <h2 class="text-2xl font-black">
                        <?= htmlspecialchars($page_title); ?>
                    </h2>

                    <p class="text-sm text-slate-400">
                        Welcome, <?= htmlspecialchars($admin_name); ?>
                    </p>
                </div>


                <div class="flex items-center gap-3">

                    <a href="../store.php" 
                    class="hidden md:inline-block text-slate-300 hover:text-cyan-400 font-bold">
                        Store
                    </a>

                    <a href="../logout.php" 
                    class="bg-red-500 hover:bg-red-400 text-white px-4 py-2 rounded-xl font-bold transition">
                        Logout
                    </a>

                </div>

            </div>


            <div class="lg:hidden px-6 pb-4 flex gap-2 overflow-x-auto">

                <?php foreach ($links as $link) { 

                    $is_active = $active_page === $link[0];

                ?>

                <a href="<?= $link[1]; ?>" 
                class="whitespace-nowrap px-4 py-2 rounded-xl text-sm font-bold
                <?= $is_active ? 'bg-cyan-400 text-slate-950' : 'bg-slate-900 border border-slate-800 text-slate-300'; ?>">

                    <?= $link[3]; ?> <?= $link[2]; ?>

                </a>

                <?php } ?>

            </div>

        </header>


       <div class="p-6 w-full max-w-6xl">

<?php
}


function seller_footer() {
?>
        </div>

    </main>

</div>

</body>
</html>

<?php
}
?>
