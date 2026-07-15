<?php
include 'includes/databaseConnect.php';
include 'includes/header.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit();
}

if (empty($_SESSION['last_order_id'])) {
    header("Location: store.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$order_id = (int) $_SESSION['last_order_id'];

$order_stmt = mysqli_prepare($conn, "SELECT * FROM orders WHERE order_id = ? AND user_id = ?");
mysqli_stmt_bind_param($order_stmt, "ii", $order_id, $user_id);
mysqli_stmt_execute($order_stmt);
$order = mysqli_fetch_assoc(mysqli_stmt_get_result($order_stmt));

if (!$order) {
    header("Location: store.php");
    exit();
}

$user_stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE user_id = ?");
mysqli_stmt_bind_param($user_stmt, "i", $user_id);
mysqli_stmt_execute($user_stmt);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($user_stmt));

$full_name = trim(implode(' ', array_filter([
    $user['first_name'] ?? '',
    $user['middle_name'] ?? '',
    $user['last_name'] ?? ''
])));

$full_address = implode(', ', array_filter([
    $user['barangay'] ?? '',
    $user['municipality'] ?? '',
    $user['province'] ?? '',
    $user['region'] ?? ''
]));

$items_stmt = mysqli_prepare($conn, "
    SELECT oi.quantity, oi.price, p.product_name
    FROM order_items oi
    JOIN products p ON p.product_id = oi.product_id
    WHERE oi.order_id = ?
");
mysqli_stmt_bind_param($items_stmt, "i", $order_id);
mysqli_stmt_execute($items_stmt);
$items_result = mysqli_stmt_get_result($items_stmt);

$order_items = [];
while ($item = mysqli_fetch_assoc($items_result)) {
    $item['subtotal'] = $item['price'] * $item['quantity'];
    $order_items[] = $item;
}

unset($_SESSION['last_order_id']);
?>

<section class="max-w-5xl mx-auto px-6 py-14">

    <div class="text-center mb-10">
        <div class="w-16 h-16 rounded-full bg-cyan-400/10 border border-cyan-400/30 flex items-center justify-center mx-auto mb-5" style="width:4rem;height:4rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" class="text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" style="width:1.75rem;height:1.75rem;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <p class="text-cyan-400 font-semibold">Order Confirmed</p>
        <h1 class="text-5xl font-black mt-2">Thank You for Your Order!</h1>
        <p class="text-slate-400 mt-4">
            Order #<?php echo (int) $order['order_id']; ?> has been placed successfully.
        </p>
    </div>

    <div class="grid lg:grid-cols-3 gap-8">

        <div class="lg:col-span-2 bg-slate-900 border border-slate-800 rounded-3xl p-8">
            <h2 class="text-2xl font-black mb-6">Buyer Information</h2>

            <div class="grid md:grid-cols-2 gap-5">
                <div>
                    <p class="text-sm text-slate-400">Complete Name</p>
                    <p class="font-bold mt-1"><?php echo htmlspecialchars($full_name); ?></p>
                </div>

                <div>
                    <p class="text-sm text-slate-400">Email Address</p>
                    <p class="font-bold mt-1"><?php echo htmlspecialchars($user['email']); ?></p>
                </div>

                <div>
                    <p class="text-sm text-slate-400">Contact Number</p>
                    <p class="font-bold mt-1"><?php echo htmlspecialchars($user['contact_number']); ?></p>
                </div>

                <div>
                    <p class="text-sm text-slate-400">Address</p>
                    <p class="font-bold mt-1"><?php echo htmlspecialchars($full_address); ?></p>
                </div>
            </div>

            <h2 class="text-2xl font-black mt-10 mb-6">Order Items</h2>

            <div class="space-y-4">
                <?php foreach ($order_items as $item) { ?>
                    <div class="flex justify-between bg-slate-950 border border-slate-800 rounded-2xl p-4">
                        <div>
                            <h3 class="font-bold"><?php echo htmlspecialchars($item['product_name']); ?></h3>
                            <p class="text-sm text-slate-400">Quantity: <?php echo (int) $item['quantity']; ?></p>
                        </div>

                        <p class="font-black text-cyan-400"><?php echo format_price($item['subtotal']); ?></p>
                    </div>
                <?php } ?>
            </div>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-8 h-fit">
            <h2 class="text-2xl font-black mb-6">Order Summary</h2>

            <div class="flex justify-between text-slate-400 mb-3">
                <span>Order Number</span>
                <span>#<?php echo (int) $order['order_id']; ?></span>
            </div>

            <div class="flex justify-between text-slate-400 mb-3">
                <span>Payment Method</span>
                <span><?php echo htmlspecialchars($order['payment_method']); ?></span>
            </div>

            <div class="flex justify-between text-slate-400 mb-6">
                <span>Order Status</span>
                <span><?php echo htmlspecialchars($order['order_status']); ?></span>
            </div>

            <div class="border-t border-slate-800 pt-6">
                <div class="flex justify-between items-center">
                    <span class="text-xl font-black">Total</span>
                    <span class="text-3xl font-black text-cyan-400"><?php echo format_price($order['total_amount']); ?></span>
                </div>
            </div>

            <a href="store.php" class="block text-center mt-8 bg-cyan-400 hover:bg-cyan-300 text-slate-950 py-3 rounded-xl font-black transition">
                Continue Shopping
            </a>
        </div>

    </div>
</section>

<?php include 'includes/footer.php'; ?>