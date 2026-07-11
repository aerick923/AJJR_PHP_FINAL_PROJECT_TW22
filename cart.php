<?php
include 'includes/databaseConnect.php';
include 'includes/header.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    if ($action == "add") {
        $product_id = intval($_POST['product_id']);

        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]++;
        } else {
            $_SESSION['cart'][$product_id] = 1;
        }
    }

    if ($action == "update") {
        foreach ($_POST['quantities'] as $product_id => $quantity) {
            $product_id = intval($product_id);
            $quantity = intval($quantity);

            if ($quantity <= 0) {
                unset($_SESSION['cart'][$product_id]);
            } else {
                $_SESSION['cart'][$product_id] = $quantity;
            }
        }
    }

    if ($action == "remove") {
        $product_id = intval($_POST['product_id']);
        unset($_SESSION['cart'][$product_id]);
    }
}

$cart_items = [];
$total = 0;

if (!empty($_SESSION['cart'])) {
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
}
?>

<section class="max-w-6xl mx-auto px-6 py-14">

    <div class="mb-10">
        <p class="text-cyan-400 font-semibold">Shopping Cart</p>
        <h1 class="text-5xl font-black mt-2">Your Cart</h1>
        <p class="text-slate-400 mt-4">Review your items before checkout.</p>
    </div>

    <?php if (empty($cart_items)) { ?>

        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-12 text-center">
            <div class="text-7xl mb-5">🛒</div>
            <h2 class="text-3xl font-black">Your cart is empty</h2>
            <p class="text-slate-400 mt-3">Browse the store and add products to your cart.</p>

            <a href="store.php" class="inline-block mt-8 bg-cyan-400 hover:bg-cyan-300 text-slate-950 px-6 py-3 rounded-xl font-black transition">
                Go to Store
            </a>
        </div>

    <?php } else { ?>

        <form method="POST">
            <input type="hidden" name="action" value="update">

            <div class="bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden">
                <div class="hidden md:grid grid-cols-5 bg-slate-800 px-6 py-4 text-sm font-bold text-slate-300">
                    <p class="col-span-2">Product</p>
                    <p>Price</p>
                    <p>Quantity</p>
                    <p class="text-right">Subtotal</p>
                </div>

                <?php foreach ($cart_items as $item) { 
                    $product = $item['product'];
                ?>
                    <div class="grid md:grid-cols-5 gap-4 px-6 py-5 border-t border-slate-800 items-center">
                        <div class="md:col-span-2">
                            <h2 class="font-black"><?php echo $product['product_name']; ?></h2>
                            <p class="text-sm text-slate-400">Product ID: <?php echo $product['product_id']; ?></p>
                        </div>

                        <p class="font-bold"><?php echo format_price($product['price']); ?></p>

                        <input type="number" min="1" max="<?php echo $product['stock']; ?>" 
                               name="quantities[<?php echo $product['product_id']; ?>]" 
                               value="<?php echo $item['quantity']; ?>" 
                               class="w-24 bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 outline-none focus:border-cyan-400">

                        <div class="text-right">
                            <p class="font-black text-cyan-400"><?php echo format_price($item['subtotal']); ?></p>

                            <!-- Delete button -->
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="remove">
                                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                <button type="submit" 
                                        class="mt-2 bg-red-500 hover:bg-red-400 text-white px-4 py-2 rounded-xl font-bold transition">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                <?php } ?>

                <div class="border-t border-slate-800 px-6 py-6 flex flex-col md:flex-row gap-4 md:items-center md:justify-between">
                    <button type="submit" class="border border-slate-700 hover:border-cyan-400 px-6 py-3 rounded-xl font-bold transition">
                        Update Cart
                    </button>

                    <div class="text-right">
                        <p class="text-slate-400">Total Amount</p>
                        <p class="text-4xl font-black text-cyan-400"><?php echo format_price($total); ?></p>
                    </div>
                </div>
            </div>
        </form>

        <div class="flex flex-col md:flex-row justify-between gap-4 mt-8">
            <a href="store.php" class="text-center border border-slate-700 hover:border-cyan-400 px-6 py-3 rounded-xl font-bold transition">
                Continue Shopping
            </a>

            <a href="checkout.php" class="text-center bg-cyan-400 hover:bg-cyan-300 text-slate-950 px-6 py-3 rounded-xl font-black transition">
                Proceed to Checkout
            </a>
        </div>

    <?php } ?>

</section>

<?php include 'includes/footer.php'; ?>
