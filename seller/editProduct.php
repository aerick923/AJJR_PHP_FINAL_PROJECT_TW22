<?php
require_once __DIR__ . '/../includes/databaseConnect.php';
require_once __DIR__ . '/../includes/functions.php';

require_admin();

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$message = "";

$stmt = mysqli_prepare($conn, "SELECT * FROM products WHERE product_id = ?");
mysqli_stmt_bind_param($stmt, "i", $product_id);
mysqli_stmt_execute($stmt);
$product = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$product) {
    die("Product not found.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $product_name = clean_input($_POST['product_name']);
    $category_id = intval($_POST['category_id']);
    $description = clean_input($_POST['description']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $image = clean_input($_POST['image']);

    if ($image === "") {
        $image = "default.png";
    }

    $update = mysqli_prepare($conn, "
        UPDATE products
        SET product_name = ?, category_id = ?, description = ?, price = ?, stock = ?, image = ?
        WHERE product_id = ?
    ");

    mysqli_stmt_bind_param($update, "sisdisi", $product_name, $category_id, $description, $price, $stock, $image, $product_id);

    if (mysqli_stmt_execute($update)) {
        $message = "Product updated successfully.";
        log_activity($conn, "Edit Product", "Updated product: " . $product_name);

        $stmt = mysqli_prepare($conn, "SELECT * FROM products WHERE product_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $product_id);
        mysqli_stmt_execute($stmt);
        $product = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    } else {
        $message = "Failed to update product.";
    }
}

$categories = mysqli_query($conn, "SELECT * FROM categories ORDER BY category_name ASC");

seller_header("Edit Product", "products");
?>

<div class="mb-8">
    <p class="text-cyan-400 font-semibold">Product Management</p>
    <h1 class="text-4xl font-black mt-2">Edit Product</h1>
    <p class="text-slate-400 mt-3">Modify product details, stock, and price.</p>
</div>

<div class="max-w-3xl bg-slate-900 border border-slate-800 rounded-3xl p-8">
    <?php if ($message !== "") { ?>
        <div class="mb-6 bg-cyan-400/10 border border-cyan-400/30 text-cyan-300 px-5 py-4 rounded-2xl">
            <?= htmlspecialchars($message); ?>
        </div>
    <?php } ?>

    <form method="POST" class="space-y-5">
        <div>
            <label class="text-sm text-slate-300">Product Name</label>
            <input type="text" name="product_name" value="<?= htmlspecialchars($product['product_name']); ?>" required class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400">
        </div>

        <div>
            <label class="text-sm text-slate-300">Category</label>
            <select name="category_id" required class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400">
                <?php while ($category = mysqli_fetch_assoc($categories)) { ?>
                    <option value="<?= $category['category_id']; ?>" <?= $category['category_id'] == $product['category_id'] ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($category['category_name']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div>
            <label class="text-sm text-slate-300">Description</label>
            <textarea name="description" rows="4" class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400"><?= htmlspecialchars($product['description']); ?></textarea>
        </div>

        <div class="grid md:grid-cols-2 gap-5">
            <div>
                <label class="text-sm text-slate-300">Price</label>
                <input type="number" name="price" step="0.01" min="0" value="<?= $product['price']; ?>" required class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400">
            </div>

            <div>
                <label class="text-sm text-slate-300">Stock</label>
                <input type="number" name="stock" min="0" value="<?= $product['stock']; ?>" required class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400">
            </div>
        </div>

        <div>
            <label class="text-sm text-slate-300">Image Filename</label>
            <input type="text" name="image" value="<?= htmlspecialchars($product['image']); ?>" class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400">
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-cyan-400 hover:bg-cyan-300 text-slate-950 px-6 py-3 rounded-xl font-black transition">
                Update Product
            </button>

            <a href="manageProducts.php" class="border border-slate-700 hover:border-cyan-400 px-6 py-3 rounded-xl font-bold transition">
                Back
            </a>
        </div>
    </form>
</div>

<?php seller_footer(); ?>
