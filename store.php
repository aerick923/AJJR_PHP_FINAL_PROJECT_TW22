<?php
include 'includes/databaseConnect.php';
include 'includes/header.php';

$category_filter = isset($_GET['category']) ? intval($_GET['category']) : 0;

$categories = mysqli_query($conn, "SELECT * FROM categories");

if ($category_filter > 0) {
    $stmt = mysqli_prepare($conn, "SELECT products.*, categories.category_name 
                                  FROM products 
                                  JOIN categories ON products.category_id = categories.category_id
                                  WHERE products.category_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $category_filter);
    mysqli_stmt_execute($stmt);
    $products = mysqli_stmt_get_result($stmt);
} else {
    $products = mysqli_query($conn, "SELECT products.*, categories.category_name 
                                     FROM products 
                                     JOIN categories ON products.category_id = categories.category_id");
}
?>

<section class="max-w-7xl mx-auto px-6 py-14">

    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-5 mb-10">
        <div>
            <p class="text-cyan-400 font-semibold">AJJR Store</p>
            <h1 class="text-5xl font-black mt-2">PC Parts Catalog</h1>
            <p class="text-slate-400 mt-4 max-w-2xl">
                Choose from processors, RAM, graphics cards, motherboards, storage, power supplies, and peripherals.
            </p>
        </div>

        <a href="cart.php" class="bg-cyan-400 hover:bg-cyan-300 text-slate-950 px-6 py-3 rounded-xl font-black transition">
            View Cart
        </a>
    </div>

    <div class="flex flex-wrap gap-3 mb-10">
        <a href="store.php" class="px-5 py-2.5 rounded-full text-sm font-bold <?php echo $category_filter == 0 ? 'bg-cyan-400 text-slate-950' : 'bg-slate-900 border border-slate-800 text-slate-300 hover:border-cyan-400'; ?>">
            All
        </a>

        <?php while ($category = mysqli_fetch_assoc($categories)) { ?>
            <a href="store.php?category=<?php echo $category['category_id']; ?>" class="px-5 py-2.5 rounded-full text-sm font-bold <?php echo $category_filter == $category['category_id'] ? 'bg-cyan-400 text-slate-950' : 'bg-slate-900 border border-slate-800 text-slate-300 hover:border-cyan-400'; ?>">
                <?php echo $category['category_name']; ?>
            </a>
        <?php } ?>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">

        <?php while ($product = mysqli_fetch_assoc($products)) { ?>
            <div class="group bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden hover:border-cyan-400 transition shadow-xl">
                
                <div class="h-52 bg-gradient-to-br from-slate-800 to-slate-700 flex items-center justify-center relative overflow-hidden">
                    <div class="absolute inset-0 bg-cyan-400/5 group-hover:bg-cyan-400/10 transition"></div>
                    <span class="text-6xl">🖥️</span>
                </div>

                <div class="p-6">
                    <p class="text-sm text-cyan-400 font-bold"><?php echo $product['category_name']; ?></p>
                    <h2 class="text-xl font-black mt-2"><?php echo $product['product_name']; ?></h2>
                    <p class="text-slate-400 text-sm mt-3 min-h-12"><?php echo $product['description']; ?></p>

                    <div class="flex items-center justify-between mt-5">
                        <p class="text-2xl font-black text-white"><?php echo format_price($product['price']); ?></p>
                        <p class="text-sm <?php echo $product['stock'] > 0 ? 'text-green-400' : 'text-red-400'; ?>">
                            Stock: <?php echo $product['stock']; ?>
                        </p>
                    </div>

                    <form action="cart.php" method="POST" class="mt-6">
                        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                        <input type="hidden" name="action" value="add">

                        <button type="submit" <?php echo $product['stock'] <= 0 ? 'disabled' : ''; ?> class="w-full py-3 rounded-xl font-black transition <?php echo $product['stock'] > 0 ? 'bg-cyan-400 hover:bg-cyan-300 text-slate-950' : 'bg-slate-700 text-slate-400 cursor-not-allowed'; ?>">
                            <?php echo $product['stock'] > 0 ? 'Add to Cart' : 'Out of Stock'; ?>
                        </button>
                    </form>
                </div>
            </div>
        <?php } ?>

    </div>

</section>

<?php include 'includes/footer.php'; ?>