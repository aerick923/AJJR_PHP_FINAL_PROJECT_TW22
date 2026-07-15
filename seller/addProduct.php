<?php
require_once __DIR__ . '/../includes/databaseConnect.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin();

$message = "";

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

    if ($product_name === "" || $category_id <= 0 || $price < 0 || $stock < 0) {
        $message = "Please fill in all fields correctly.";
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO products (product_name, category_id, description, price, stock, image) VALUES (?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sisdis", $product_name, $category_id, $description, $price, $stock, $image);
        if (mysqli_stmt_execute($stmt)) {
            $message = "Product added successfully.";
            log_activity($conn, "Add Product", "Added product: $product_name");
        } else {
            $message = "Failed to add product.";
        }
    }
}

$categories = mysqli_query($conn, "SELECT * FROM categories ORDER BY category_name ASC");
seller_header("Add Product", "products");
?>

<div class="max-w-4xl mx-auto bg-slate-900 border border-slate-800 rounded-3xl p-8 shadow-lg">
    <h2 class="text-3xl font-black mb-4">Add New Product</h2>
    <?php if ($message !== "") { ?>
        <div class="mb-6 bg-cyan-400/10 border border-cyan-400/30 text-cyan-300 px-5 py-4 rounded-xl">
            <?= htmlspecialchars($message); ?>
        </div>
    <?php } ?>
    <form method="POST" class="space-y-5">
        <div>
            <label class="text-sm text-slate-300">Product Name</label>
            <input type="text" name="product_name" required class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-950 border border-slate-700 focus:outline-none focus:border-cyan-400">
        </div>
        <div>
            <label class="text-sm text-slate-300">Category</label>
            <select name="category_id" required class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-950 border border-slate-700 focus:outline-none focus:border-cyan-400">
                <option value="">Select category</option>
                <?php while ($category = mysqli_fetch_assoc($categories)) { ?>
                    <option value="<?= $category['category_id']; ?>"><?= htmlspecialchars($category['category_name']); ?></option>
                <?php } ?>
            </select>
        </div>
        <div>
            <label class="text-sm text-slate-300">Description</label>
            <textarea name="description" rows="4" class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-950 border border-slate-700 focus:outline-none focus:border-cyan-400"></textarea>
        </div>
        <div class="grid md:grid-cols-2 gap-5">
            <div>
                <label class="text-sm text-slate-300">Price</label>
                <input type="number" name="price" step="0.01" min="0" required class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-950 border border-slate-700 focus:outline-none focus:border-cyan-400">
            </div>
            <div>
                <label class="text-sm text-slate-300">Stock</label>
                <input type="number" name="stock" min="0" required class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-950 border border-slate-700 focus:outline-none focus:border-cyan-400">
            </div>
        </div>
        <div>
            <label class="text-sm text-slate-300">Image Filename</label>
            <input type="text" name="image" placeholder="example.jpg" class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-950 border border-slate-700 focus:outline-none focus:border-cyan-400">
        </div>
        <div class="flex gap-3">
            <button type="submit" class="bg-cyan-400 hover:bg-cyan-300 text-slate-950 px-6 py-3 rounded-xl font-black transition">Add Product</button>
            <a href="manageProducts.php" class="border border-slate-700 hover:border-cyan-400 px-6 py-3 rounded-xl font-bold transition">Back</a>
        </div>
    </form>
</div>

<?php seller_footer(); ?>