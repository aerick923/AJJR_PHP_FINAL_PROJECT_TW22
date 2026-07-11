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

// -----------------------------------------------------------------
// Product catalog (same data store.php uses). store.php's products
// are NOT rows in the `products` DB table, so looking them up with
// "SELECT * FROM products WHERE product_id IN (...)" always returned
// nothing and the cart looked empty. Instead we keep the catalog here
// too and look items up from this array.
// -----------------------------------------------------------------

$categories = [
    1  => 'Processors',
    2  => 'Graphics Cards',
    3  => 'Motherboards',
    4  => 'Memory',
    5  => 'Storage SSDs',
    6  => 'Storage HDDs',
    7  => 'Power Supplies',
    8  => 'PC Cases',
    9  => 'CPU Coolers',
    10 => 'Case Fans',
    11 => 'Keyboards',
    12 => 'Mice',
    13 => 'Monitors',
    14 => 'Headsets',
];

$product_specs = [
    ['product_id' => 1001, 'category_id' => 1,  'product_name' => 'Intel Core i3-14100F', 'price' => 6999,  'stock' => 18],
    ['product_id' => 1002, 'category_id' => 1,  'product_name' => 'Intel Core i5-14400F', 'price' => 11499, 'stock' => 16],
    ['product_id' => 1003, 'category_id' => 1,  'product_name' => 'Intel Core i5-14600K',  'price' => 16999, 'stock' => 12],
    ['product_id' => 1004, 'category_id' => 1,  'product_name' => 'Intel Core i7-14700K',  'price' => 23999, 'stock' => 9],
    ['product_id' => 1005, 'category_id' => 1,  'product_name' => 'AMD Ryzen 5 5600',      'price' => 7999,  'stock' => 20],
    ['product_id' => 1006, 'category_id' => 1,  'product_name' => 'AMD Ryzen 5 7600',      'price' => 14999, 'stock' => 15],
    ['product_id' => 1007, 'category_id' => 1,  'product_name' => 'AMD Ryzen 7 7700X',     'price' => 20999, 'stock' => 11],
    ['product_id' => 1008, 'category_id' => 1,  'product_name' => 'AMD Ryzen 7 7800X3D',   'price' => 28999, 'stock' => 8],

    ['product_id' => 2001, 'category_id' => 2,  'product_name' => 'NVIDIA GeForce RTX 3050',      'price' => 12999, 'stock' => 14],
    ['product_id' => 2002, 'category_id' => 2,  'product_name' => 'NVIDIA GeForce RTX 4060',       'price' => 17999, 'stock' => 13],
    ['product_id' => 2003, 'category_id' => 2,  'product_name' => 'NVIDIA GeForce RTX 4070 SUPER', 'price' => 34999, 'stock' => 8],
    ['product_id' => 2004, 'category_id' => 2,  'product_name' => 'NVIDIA GeForce RTX 4080 SUPER', 'price' => 59999, 'stock' => 4],
    ['product_id' => 2005, 'category_id' => 2,  'product_name' => 'AMD Radeon RX 6600',           'price' => 11999, 'stock' => 15],
    ['product_id' => 2006, 'category_id' => 2,  'product_name' => 'AMD Radeon RX 7600',           'price' => 15999, 'stock' => 12],
    ['product_id' => 2007, 'category_id' => 2,  'product_name' => 'AMD Radeon RX 7800 XT',        'price' => 32999, 'stock' => 7],
    ['product_id' => 2008, 'category_id' => 2,  'product_name' => 'AMD Radeon RX 7900 XTX',       'price' => 56999, 'stock' => 3],

    ['product_id' => 3001, 'category_id' => 3,  'product_name' => 'ASUS PRIME B760M-A D4',            'price' => 7999,  'stock' => 14],
    ['product_id' => 3002, 'category_id' => 3,  'product_name' => 'MSI PRO Z790-A WIFI',              'price' => 18999, 'stock' => 7],
    ['product_id' => 3003, 'category_id' => 3,  'product_name' => 'GIGABYTE B650M DS3H',              'price' => 8999,  'stock' => 16],
    ['product_id' => 3004, 'category_id' => 3,  'product_name' => 'ASUS TUF GAMING X670E-PLUS WIFI',  'price' => 21999, 'stock' => 6],
    ['product_id' => 3005, 'category_id' => 3,  'product_name' => 'ASRock B550M Steel Legend',        'price' => 7499,  'stock' => 13],

    ['product_id' => 4001, 'category_id' => 4,  'product_name' => 'Kingston Fury Beast 16GB DDR4 3200MHz',         'price' => 2499,  'stock' => 24],
    ['product_id' => 4002, 'category_id' => 4,  'product_name' => 'Corsair Vengeance LPX 32GB DDR4 3600MHz',       'price' => 4499,  'stock' => 19],
    ['product_id' => 4003, 'category_id' => 4,  'product_name' => 'G.Skill Ripjaws S5 16GB DDR5 5600MHz',          'price' => 3499,  'stock' => 18],
    ['product_id' => 4004, 'category_id' => 4,  'product_name' => 'TeamGroup T-Force Delta RGB 32GB DDR5 6000MHz', 'price' => 6999,  'stock' => 14],
    ['product_id' => 4005, 'category_id' => 4,  'product_name' => 'Crucial Pro 64GB DDR5 5600MHz',                 'price' => 12999, 'stock' => 8],

    ['product_id' => 5001, 'category_id' => 5,  'product_name' => 'Kingston NV2 500GB NVMe SSD',   'price' => 1999, 'stock' => 28],
    ['product_id' => 5002, 'category_id' => 5,  'product_name' => 'WD Blue SN580 1TB NVMe SSD',     'price' => 3499, 'stock' => 25],
    ['product_id' => 5003, 'category_id' => 5,  'product_name' => 'Samsung 990 PRO 1TB NVMe SSD',   'price' => 5499, 'stock' => 16],
    ['product_id' => 5004, 'category_id' => 5,  'product_name' => 'Lexar NM790 2TB NVMe SSD',       'price' => 6499, 'stock' => 12],
    ['product_id' => 5005, 'category_id' => 5,  'product_name' => 'Crucial MX500 1TB SATA SSD',     'price' => 2699, 'stock' => 22],

    ['product_id' => 6001, 'category_id' => 6,  'product_name' => 'Seagate BarraCuda 1TB HDD', 'price' => 2299, 'stock' => 20],
    ['product_id' => 6002, 'category_id' => 6,  'product_name' => 'Seagate BarraCuda 2TB HDD', 'price' => 3199, 'stock' => 18],
    ['product_id' => 6003, 'category_id' => 6,  'product_name' => 'WD Blue 4TB HDD',           'price' => 4999, 'stock' => 12],
    ['product_id' => 6004, 'category_id' => 6,  'product_name' => 'Toshiba P300 2TB HDD',      'price' => 3099, 'stock' => 14],
    ['product_id' => 6005, 'category_id' => 6,  'product_name' => 'WD Purple 6TB HDD',         'price' => 6999, 'stock' => 6],

    ['product_id' => 7001, 'category_id' => 7,  'product_name' => 'Cooler Master MWE 550 Bronze V2',      'price' => 2999, 'stock' => 22],
    ['product_id' => 7002, 'category_id' => 7,  'product_name' => 'Corsair CX650M 650W Bronze',           'price' => 3999, 'stock' => 18],
    ['product_id' => 7003, 'category_id' => 7,  'product_name' => 'MSI MAG A750GL PCIE5 750W Gold',       'price' => 5999, 'stock' => 13],
    ['product_id' => 7004, 'category_id' => 7,  'product_name' => 'Seasonic Focus GX-850 850W Gold',      'price' => 7999, 'stock' => 9],
    ['product_id' => 7005, 'category_id' => 7,  'product_name' => 'be quiet! Pure Power 12 M 1000W Gold', 'price' => 9999, 'stock' => 5],

    ['product_id' => 8001, 'category_id' => 8,  'product_name' => 'Montech X3 Mesh',            'price' => 3499, 'stock' => 16],
    ['product_id' => 8002, 'category_id' => 8,  'product_name' => 'NZXT H5 Flow',                'price' => 4999, 'stock' => 12],
    ['product_id' => 8003, 'category_id' => 8,  'product_name' => 'Corsair 4000D Airflow',       'price' => 5499, 'stock' => 10],
    ['product_id' => 8004, 'category_id' => 8,  'product_name' => 'Lian Li LANCOOL 216',         'price' => 6999, 'stock' => 8],
    ['product_id' => 8005, 'category_id' => 8,  'product_name' => 'Fractal Design Pop Air',      'price' => 5999, 'stock' => 9],

    ['product_id' => 9001, 'category_id' => 9,  'product_name' => 'DeepCool AK400',                          'price' => 1499, 'stock' => 20],
    ['product_id' => 9002, 'category_id' => 9,  'product_name' => 'Thermalright Peerless Assassin 120 SE',   'price' => 2299, 'stock' => 15],
    ['product_id' => 9003, 'category_id' => 9,  'product_name' => 'Noctua NH-D15',                           'price' => 4999, 'stock' => 6],
    ['product_id' => 9004, 'category_id' => 9,  'product_name' => 'Cooler Master Hyper 212 Halo',            'price' => 1899, 'stock' => 18],
    ['product_id' => 9005, 'category_id' => 9,  'product_name' => 'Arctic Liquid Freezer III 240',           'price' => 4999, 'stock' => 7],

    ['product_id' => 10001, 'category_id' => 10, 'product_name' => 'ARCTIC P12 PWM PST 120mm',       'price' => 499,  'stock' => 30],
    ['product_id' => 10002, 'category_id' => 10, 'product_name' => 'Noctua NF-A12x25 PWM',           'price' => 1299, 'stock' => 14],
    ['product_id' => 10003, 'category_id' => 10, 'product_name' => 'DeepCool FC120 3-Pack',          'price' => 1999, 'stock' => 12],
    ['product_id' => 10004, 'category_id' => 10, 'product_name' => 'Lian Li UNI FAN SL120 V2',       'price' => 3499, 'stock' => 8],
    ['product_id' => 10005, 'category_id' => 10, 'product_name' => 'Corsair iCUE AF120 RGB Elite',   'price' => 2999, 'stock' => 10],

    ['product_id' => 11001, 'category_id' => 11, 'product_name' => 'Logitech G Pro Mechanical Keyboard',      'price' => 4999, 'stock' => 15],
    ['product_id' => 11002, 'category_id' => 11, 'product_name' => 'Razer Huntsman Mini',                     'price' => 5999, 'stock' => 11],
    ['product_id' => 11003, 'category_id' => 11, 'product_name' => 'Keychron K2 Wireless Mechanical Keyboard','price' => 5499, 'stock' => 13],
    ['product_id' => 11004, 'category_id' => 11, 'product_name' => 'SteelSeries Apex 3 TKL',                  'price' => 3499, 'stock' => 16],
    ['product_id' => 11005, 'category_id' => 11, 'product_name' => 'HyperX Alloy Origins Core',                'price' => 4599, 'stock' => 12],

    ['product_id' => 12001, 'category_id' => 12, 'product_name' => 'Logitech G502 X',                  'price' => 3499, 'stock' => 18],
    ['product_id' => 12002, 'category_id' => 12, 'product_name' => 'Logitech G Pro X Superlight 2',    'price' => 6999, 'stock' => 9],
    ['product_id' => 12003, 'category_id' => 12, 'product_name' => 'Razer DeathAdder V3',              'price' => 4999, 'stock' => 14],
    ['product_id' => 12004, 'category_id' => 12, 'product_name' => 'Glorious Model O 2',               'price' => 3999, 'stock' => 12],
    ['product_id' => 12005, 'category_id' => 12, 'product_name' => 'SteelSeries Rival 3 Wireless',     'price' => 2999, 'stock' => 17],

    ['product_id' => 13001, 'category_id' => 13, 'product_name' => 'ASUS TUF Gaming VG249Q1R', 'price' => 8999,  'stock' => 10],
    ['product_id' => 13002, 'category_id' => 13, 'product_name' => 'LG UltraGear 27GN800',      'price' => 12999, 'stock' => 8],
    ['product_id' => 13003, 'category_id' => 13, 'product_name' => 'MSI G274QPF-QD',             'price' => 14999, 'stock' => 7],
    ['product_id' => 13004, 'category_id' => 13, 'product_name' => 'Gigabyte M27Q',               'price' => 15999, 'stock' => 6],
    ['product_id' => 13005, 'category_id' => 13, 'product_name' => 'Samsung Odyssey G5 34',       'price' => 24999, 'stock' => 5],

    ['product_id' => 14001, 'category_id' => 14, 'product_name' => 'HyperX Cloud III',              'price' => 4499, 'stock' => 16],
    ['product_id' => 14002, 'category_id' => 14, 'product_name' => 'SteelSeries Arctis Nova 7',      'price' => 9999, 'stock' => 8],
    ['product_id' => 14003, 'category_id' => 14, 'product_name' => 'Logitech G Pro X Lightspeed',    'price' => 7999, 'stock' => 9],
    ['product_id' => 14004, 'category_id' => 14, 'product_name' => 'Razer BlackShark V2 X',          'price' => 2499, 'stock' => 20],
    ['product_id' => 14005, 'category_id' => 14, 'product_name' => 'Corsair HS80 RGB Wireless',      'price' => 7999, 'stock' => 7],
];

// Index by product_id for quick lookup
$all_products = [];
foreach ($product_specs as $spec) {
    if (isset($categories[$spec['category_id']])) {
        $spec['category_name'] = $categories[$spec['category_id']];
    }
    $all_products[$spec['product_id']] = $spec;
}

$cart_items=[];
$total=0;

if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $id => $qty) {
        $id = intval($id);

        // Product no longer exists in the catalog — drop it silently.
        if (!isset($all_products[$id])) {
            unset($_SESSION['cart'][$id]);
            continue;
        }

        $product = $all_products[$id];
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

<a href="checkout.php"
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
