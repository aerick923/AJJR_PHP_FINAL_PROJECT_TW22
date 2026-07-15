<?php
require_once __DIR__ . '/../includes/databaseConnect.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin();

$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_product'])) {
    $product_id = intval($_POST['product_id']);
    $stmt = mysqli_prepare($conn, "DELETE FROM products WHERE product_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    if (mysqli_stmt_execute($stmt)) {
        $message = "Product deleted successfully.";
        log_activity($conn, "Delete Product", "Deleted product ID: $product_id");
    } else {
        $message = "Cannot delete product connected to orders.";
    }
}

$products = mysqli_query($conn, "
    SELECT products.*, categories.category_name
    FROM products
    INNER JOIN categories ON products.category_id = categories.category_id
    ORDER BY products.product_id DESC
");

seller_header("Manage Products", "products");
?>

<div class="max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-5 mb-8">
        <div>
            <p class="text-cyan-400 font-semibold">Product Management</p>
            <h1 class="text-4xl font-black mt-2">Manage Products</h1>
            <p class="text-slate-400 mt-3">Add, edit, delete, and update product stocks and prices.</p>
        </div>
        <a href="addProduct.php" class="bg-cyan-400 hover:bg-cyan-300 text-slate-950 px-6 py-3 rounded-xl font-black transition">Add Product</a>
    </div>

    <?php if ($message !== "") { ?>
        <div class="mb-6 bg-cyan-400/10 border border-cyan-400/30 text-cyan-300 px-5 py-4 rounded-xl">
            <?= htmlspecialchars($message); ?>
        </div>
    <?php } ?>

    <div class="bg-slate-900/80 border border-slate-800 rounded-3xl overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] text-left">
                <thead class="bg-slate-800 text-slate-300 text-sm">
                    <tr>
                        <th class="p-4">ID</th>
                        <th class="p-4">Product</th>
                        <th class="p-4">Category</th>
                        <th class="p-4">Price</th>
                        <th class="p-4">Stock</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($product = mysqli_fetch_assoc($products)) { ?>
                        <tr class="border-t border-slate-800">
                            <td class="p-4 text-slate-400">#<?= $product['product_id']; ?></td>
                            <td class="p-4 font-black"><?= htmlspecialchars($product['product_name']); ?></td>
                            <td class="p-4 text-cyan-400 font-bold"><?= htmlspecialchars($product['category_name']); ?></td>
                            <td class="p-4 font-bold"><?= format_price($product['price']); ?></td>
                            <td class="p-4">
                                <span class="px-3 py-1 rounded-full text-sm font-bold <?= $product['stock'] <= 5 ? 'bg-red-400/10 text-red-400' : 'bg-green-400/10 text-green-400'; ?>">
                                    <?= $product['stock']; ?>
                                </span>
                            </td>
                            <td class="p-4">
                                <div class="flex justify-end gap-2">
                                    <a href="editProduct.php?id=<?= $product['product_id']; ?>" class="bg-blue-500 hover:bg-blue-400 text-white px-4 py-2 rounded-xl font-bold transition">Edit</a>
                                    <form method="POST" onsubmit="return confirm('Delete this product?');">
                                        <input type="hidden" name="product_id" value="<?= $product['product_id']; ?>">
                                        <button type="submit" name="delete_product" class="bg-red-500 hover:bg-red-400 text-white px-4 py-2 rounded-xl font-bold transition">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php seller_footer(); ?>