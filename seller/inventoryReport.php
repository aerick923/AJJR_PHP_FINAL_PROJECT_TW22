<?php
require_once __DIR__ . '/../includes/databaseConnect.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin();

$products = mysqli_query($conn, "
    SELECT products.*, categories.category_name
    FROM products
    INNER JOIN categories ON products.category_id = categories.category_id
    ORDER BY categories.category_name ASC, products.product_name ASC
");

$summary = mysqli_query($conn, "
    SELECT COUNT(*) AS total_products, SUM(stock) AS total_stock, SUM(price*stock) AS inventory_value
    FROM products
");

$summary_row = mysqli_fetch_assoc($summary);
seller_header("Inventory Report", "inventory");
?>

<div class="max-w-7xl mx-auto">

    <h2 class="text-3xl font-black mb-6">Inventory Summary</h2>
    <div class="grid md:grid-cols-3 gap-6 mb-10">
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6">
            <p class="text-slate-400 text-sm">Total Products</p>
            <h2 class="text-4xl font-black text-cyan-400 mt-2"><?= $summary_row['total_products'] ?? 0; ?></h2>
        </div>
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6">
            <p class="text-slate-400 text-sm">Total Stock</p>
            <h2 class="text-4xl font-black text-cyan-400 mt-2"><?= $summary_row['total_stock'] ?? 0; ?></h2>
        </div>
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6">
            <p class="text-slate-400 text-sm">Inventory Value</p>
            <h2 class="text-4xl font-black text-cyan-400 mt-2"><?= format_price($summary_row['inventory_value'] ?? 0); ?></h2>
        </div>
    </div>
    
    <div class="bg-slate-900/80 border border-slate-800 rounded-3xl overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[950px] text-left">
                <thead class="bg-slate-800 text-slate-300 text-sm">
                    <tr>
                        <th class="p-4">Product</th>
                        <th class="p-4">Category</th>
                        <th class="p-4">Price</th>
                        <th class="p-4">Stock</th>
                        <th class="p-4">Value</th>
                        <th class="p-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($product = mysqli_fetch_assoc($products)) { 
                        $value = $product['price'] * $product['stock'];
                        if ($product['stock'] == 0) {
                            $status = "Out of Stock";
                            $status_class = "bg-red-400/10 text-red-400";
                        } elseif ($product['stock'] <= 5) {
                            $status = "Low Stock";
                            $status_class = "bg-yellow-400/10 text-yellow-400";
                        } else {
                            $status = "Available";
                            $status_class = "bg-green-400/10 text-green-400";
                        }
                    ?>
                        <tr class="border-t border-slate-800 hover:bg-slate-900/50 transition">
                            <td class="p-4 font-black"><?= htmlspecialchars($product['product_name']); ?></td>
                            <td class="p-4 text-cyan-400 font-bold"><?= htmlspecialchars($product['category_name']); ?></td>
                            <td class="p-4"><?= format_price($product['price']); ?></td>
                            <td class="p-4 font-bold"><?= $product['stock']; ?></td>
                            <td class="p-4 font-bold"><?= format_price($value); ?></td>
                            <td class="p-4">
                                <span class="px-3 py-1 rounded-full text-sm font-bold <?= $status_class; ?>">
                                    <?= $status; ?>
                                </span>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php seller_footer(); ?>