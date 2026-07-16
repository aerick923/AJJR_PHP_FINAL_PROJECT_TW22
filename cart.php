<?php
include 'includes/databaseConnect.php';
include 'includes/header.php';

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

if ($_SERVER["REQUEST_METHOD"]=="POST") {
    $action = $_POST['action'] ?? '';

    if ($action=="add") {
        $id = intval($_POST['product_id']);
        $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
    }

    if ($action=="update" && isset($_POST['quantities'])) {
        foreach ($_POST['quantities'] as $id=>$qty) {
            $id=intval($id);
            $qty=intval($qty);
            if ($qty<=0) {
                unset($_SESSION['cart'][$id]);
            } else {
                $_SESSION['cart'][$id]=$qty;
            }
        }
    }

    if ($action=="remove") {
        $id=intval($_POST['product_id']);
        unset($_SESSION['cart'][$id]);
    }

    if ($action=="clear") {
        $_SESSION['cart']=[];
    }
}

$cart_items = [];
$total = 0;

if (!empty($_SESSION['cart'])) {

    $ids = implode(",", array_map('intval', array_keys($_SESSION['cart'])));
    $result = mysqli_query($conn, "SELECT * FROM products WHERE product_id IN ($ids)");

    $found_products = [];
    while ($product = mysqli_fetch_assoc($result)) {
        $found_products[(int)$product['product_id']] = $product;
    }

    foreach ($_SESSION['cart'] as $id => $qty) {
        $id = intval($id);

        if (!isset($found_products[$id])) {
            unset($_SESSION['cart'][$id]);
            continue;
        }

        $product = $found_products[$id];
        $subtotal = $product['price'] * $qty;
        $total += $subtotal;

        $cart_items[] = [
            'product'  => $product,
            'quantity' => $qty,
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

<?php if(empty($cart_items)){ ?>

<div class="bg-slate-900 border border-slate-800 rounded-3xl p-12 text-center">
<div class="text-7xl mb-5">🛒</div>
<h2 class="text-3xl font-black">Your cart is empty</h2>
<p class="text-slate-400 mt-3">Browse the store and add products.</p>

<a href="store.php" class="inline-block mt-8 bg-cyan-400 hover:bg-cyan-300 text-slate-950 px-6 py-3 rounded-xl font-black">
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

<?php foreach($cart_items as $item):
$product=$item['product'];
?>

<div class="grid md:grid-cols-5 gap-4 px-6 py-5 border-t border-slate-800 items-center">

<div class="md:col-span-2">
<h2 class="font-black"><?php echo htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8'); ?></h2>
<p class="text-sm text-slate-400">Product ID: <?php echo (int)$product['product_id']; ?></p>
</div>

<p class="font-bold"><?php echo format_price($product['price']); ?></p>

<input
type="number"
min="1"
max="<?php echo (int)$product['stock']; ?>"
name="quantities[<?php echo (int)$product['product_id']; ?>]"
value="<?php echo (int)$item['quantity']; ?>"
class="w-24 bg-slate-950 border border-slate-700 rounded-xl px-3 py-2">

<div class="text-right">
<p class="font-black text-cyan-400 mb-2"><?php echo format_price($item['subtotal']); ?></p>
</div>

</div>

<?php endforeach; ?>

<div class="border-t border-slate-800 px-6 py-6 flex justify-between items-center">

<button type="submit"
class="bg-cyan-400 hover:bg-cyan-300 text-slate-950 px-6 py-3 rounded-xl font-bold">
Update Cart
</button>

<div class="text-right">
<p class="text-slate-400">Total Amount</p>
<p class="text-4xl font-black text-cyan-400"><?php echo format_price($total); ?></p>
</div>

</div>

</div>

</form>

<div class="flex flex-wrap gap-3 justify-between mt-6">

<a href="store.php"
class="border border-slate-700 hover:border-cyan-400 px-6 py-3 rounded-xl font-bold">
Continue Shopping
</a>

<div class="flex gap-3">

<form method="POST">
<input type="hidden" name="action" value="clear">
<button type="submit"
onclick="return confirm('Clear all items?')"
class="bg-red-700 hover:bg-red-600 text-white px-6 py-3 rounded-xl font-bold">
Clear Cart
</button>
</form>

<a href="checkOut.php"
class="bg-cyan-400 hover:bg-cyan-300 text-slate-950 px-6 py-3 rounded-xl font-black">
Proceed to Checkout
</a>

</div>

</div>

<div class="mt-6 bg-slate-900 border border-slate-800 rounded-3xl p-6">
<h3 class="text-xl font-bold mb-4">Delete Individual Items</h3>

<?php foreach($cart_items as $item):
$product=$item['product']; ?>

<form method="POST" class="flex justify-between items-center border-b border-slate-800 py-3">
<div>
<strong><?php echo htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8'); ?></strong>
</div>

<input type="hidden" name="action" value="remove">
<input type="hidden" name="product_id" value="<?php echo (int)$product['product_id']; ?>">

<button
type="submit"
onclick="return confirm('Remove this item?')"
class="bg-red-600 hover:bg-red-500 text-white px-4 py-2 rounded-lg font-bold">
Delete
</button>

</form>

<?php endforeach; ?>

</div>

<?php } ?>

</section>

<?php include 'includes/footer.php'; ?>
