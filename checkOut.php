<?php
include 'includes/databaseConnect.php';
include 'includes/header.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit();
}

if (empty($_SESSION['cart'])) {
    header("Location: store.php");
    exit();
}

$user_id = $_SESSION['user_id'];

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

$cart_items = [];
$total = 0;

$ids = implode(",", array_map('intval', array_keys($_SESSION['cart'])));
$result = mysqli_query($conn, "SELECT * FROM products WHERE product_id IN ($ids)");

while ($product = mysqli_fetch_assoc($result)) {
    $quantity = $_SESSION['cart'][$product['product_id']];
    $subtotal = $product['price'] * $quantity;
    $total += $subtotal;

    $cart_items[] = [
        'product' => $product,
        'quantity' => $quantity,
        'subtotal' => $subtotal
    ];
}
?>

<section class="max-w-6xl mx-auto px-6 py-14">

    <div class="mb-10">
        <p class="text-cyan-400 font-semibold">Checkout</p>
        <h1 class="text-5xl font-black mt-2">Confirm Your Order</h1>
        <p class="text-slate-400 mt-4">Check your details before going to payment.</p>
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
                <?php foreach ($cart_items as $item) { ?>
                    <div class="flex justify-between bg-slate-950 border border-slate-800 rounded-2xl p-4">
                        <div>
                            <h3 class="font-bold"><?php echo htmlspecialchars($item['product']['product_name']); ?></h3>
                            <p class="text-sm text-slate-400">Quantity: <?php echo (int) $item['quantity']; ?></p>
                        </div>

                        <p class="font-black text-cyan-400"><?php echo format_price($item['subtotal']); ?></p>
                    </div>
                <?php } ?>
            </div>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-8 h-fit">
            <h2 class="text-2xl font-black mb-6">Summary</h2>

            <div class="flex justify-between text-slate-400 mb-3">
                <span>Items</span>
                <span><?php echo cart_count(); ?></span>
            </div>

            <div class="flex justify-between text-slate-400 mb-6">
                <span>Payment API</span>
                <span>Not Required</span>
            </div>

            <div class="border-t border-slate-800 pt-6">
                <div class="flex justify-between items-center">
                    <span class="text-xl font-black">Total</span>
                    <span class="text-3xl font-black text-cyan-400"><?php echo format_price($total); ?></span>
                </div>
            </div>

            <a href="payment.php" class="block text-center mt-8 bg-cyan-400 hover:bg-cyan-300 text-slate-950 py-3 rounded-xl font-black transition">
                Continue to Payment
            </a>
        </div>

    </div>
</section>

<?php include 'includes/footer.php'; ?>