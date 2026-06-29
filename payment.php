<?php
include 'includes/db_connect.php';
include 'includes/header.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit();
}

if (empty($_SESSION['cart'])) {
    header("Location: store.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $payment_method = clean_input($_POST['payment_method']);
    $user_id = $_SESSION['user_id'];

    $cart_items = [];
    $total = 0;

    $ids = implode(",", array_map('intval', array_keys($_SESSION['cart'])));
    $result = mysqli_query($conn, "SELECT * FROM products WHERE product_id IN ($ids)");

    while ($product = mysqli_fetch_assoc($result)) {
        $quantity = $_SESSION['cart'][$product['product_id']];

        if ($quantity > $product['stock']) {
            $message = "Not enough stock for " . $product['product_name'];
            break;
        }

        $subtotal = $product['price'] * $quantity;
        $total += $subtotal;

        $cart_items[] = [
            'product' => $product,
            'quantity' => $quantity,
            'subtotal' => $subtotal
        ];
    }

    if ($message == "") {
        $order_stmt = mysqli_prepare($conn, "INSERT INTO orders (user_id, total_amount, payment_method) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($order_stmt, "ids", $user_id, $total, $payment_method);
        mysqli_stmt_execute($order_stmt);

        $order_id = mysqli_insert_id($conn);

        foreach ($cart_items as $item) {
            $product_id = $item['product']['product_id'];
            $quantity = $item['quantity'];
            $price = $item['product']['price'];

            $item_stmt = mysqli_prepare($conn, "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($item_stmt, "iiid", $order_id, $product_id, $quantity, $price);
            mysqli_stmt_execute($item_stmt);

            $stock_stmt = mysqli_prepare($conn, "UPDATE products SET stock = stock - ? WHERE product_id = ?");
            mysqli_stmt_bind_param($stock_stmt, "ii", $quantity, $product_id);
            mysqli_stmt_execute($stock_stmt);
        }

        $_SESSION['cart'] = [];
        $_SESSION['last_order_id'] = $order_id;

        header("Location: order_success.php");
        exit();
    }
}
?>

<section class="max-w-5xl mx-auto px-6 py-14">

    <div class="text-center mb-10">
        <p class="text-cyan-400 font-semibold">Payment</p>
        <h1 class="text-5xl font-black mt-2">Choose Payment Method</h1>
        <p class="text-slate-400 mt-4">
            No real payment API is used. This is only for final project demonstration.
        </p>
    </div>

    <form method="POST" class="max-w-xl mx-auto bg-slate-900 border border-slate-800 rounded-3xl p-8 shadow-2xl">

        <?php if ($message != "") { ?>
            <div class="mb-5 bg-red-500/10 border border-red-500/30 text-red-300 px-4 py-3 rounded-xl text-sm">
                <?php echo $message; ?>
            </div>
        <?php } ?>

        <h2 class="text-2xl font-black mb-6">Payment Options</h2>

        <label class="block bg-slate-950 border border-slate-800 rounded-2xl p-5 mb-4 cursor-pointer hover:border-cyan-400 transition">
            <input type="radio" name="payment_method" value="Cash on Delivery" required class="mr-2">
            <span class="font-bold">Cash on Delivery</span>
            <p class="text-slate-400 text-sm mt-1">Pay when your order arrives.</p>
        </label>

        <label class="block bg-slate-950 border border-slate-800 rounded-2xl p-5 mb-4 cursor-pointer hover:border-cyan-400 transition">
            <input type="radio" name="payment_method" value="GCash" required class="mr-2">
            <span class="font-bold">GCash</span>
            <p class="text-slate-400 text-sm mt-1">For demo only. No real payment connection.</p>
        </label>

        <label class="block bg-slate-950 border border-slate-800 rounded-2xl p-5 mb-6 cursor-pointer hover:border-cyan-400 transition">
            <input type="radio" name="payment_method" value="Bank Transfer" required class="mr-2">
            <span class="font-bold">Bank Transfer</span>
            <p class="text-slate-400 text-sm mt-1">For demo only. No bank API used.</p>
        </label>

        <button type="submit" class="w-full bg-cyan-400 hover:bg-cyan-300 text-slate-950 py-3 rounded-xl font-black transition">
            Place Order
        </button>

        <a href="checkout.php" class="block text-center mt-4 text-slate-400 hover:text-cyan-400">
            Back to Checkout
        </a>
    </form>

</section>

<?php include 'includes/footer.php'; ?>