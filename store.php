<?php
include 'includes/databaseConnect.php';
include 'includes/header.php';

$category_filter = isset($_GET['category']) ? intval($_GET['category']) : 0;

function slugify_filename(string $text): string
{
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    $text = trim($text, '-');
    return $text;
}

function placeholder_image(string $label): string
{
    $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="400" height="300">'
         . '<rect width="100%" height="100%" fill="#1e293b"/>'
         . '<text x="50%" y="50%" fill="#64748b" font-family="sans-serif" font-size="16" '
         . 'text-anchor="middle" dominant-baseline="middle">' . htmlspecialchars($label) . '</text>'
         . '</svg>';
    return 'data:image/svg+xml;base64,' . base64_encode($svg);
}

$category_folders = [
    1  => 'cpu',
    2  => 'gpu',
    3  => 'motherboard',
    4  => 'ram',
    5  => 'ssd',
    6  => 'hdd',
    7  => 'psu',
    8  => 'case',
    9  => 'cooler',
    10 => 'fan',
    11 => 'keyboard',
    12 => 'mouse',
    13 => 'monitor',
    14 => 'headset',
];

function resolve_product_image(string $folder, ?string $image_filename, string $product_name): string
{
    $relative_dir = 'assets/images/products/' . $folder . '/';
    $absolute_dir = __DIR__ . '/' . $relative_dir;

    if (!empty($image_filename) && $image_filename !== 'default.png'
        && file_exists($absolute_dir . $image_filename)) {
        return $relative_dir . $image_filename;
    }
    $slug = slugify_filename($product_name);
    foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) {
        if (file_exists($absolute_dir . $slug . '.' . $ext)) {
            return $relative_dir . $slug . '.' . $ext;
        }
    }
    return placeholder_image($product_name);
}

function generate_specs(array $product): array
{
    $known_brands = [
        'Cooler Master', 'be quiet!', 'G.Skill', 'TeamGroup', 'Lian Li',
        'Fractal Design', 'Thermalright', 'ARCTIC', 'Arctic', 'NZXT',
        'DeepCool', 'Seasonic', 'Montech', 'Keychron', 'SteelSeries',
        'HyperX', 'Glorious', 'Logitech', 'Razer', 'Samsung', 'Kingston',
        'Corsair', 'Crucial', 'Seagate', 'Toshiba', 'GIGABYTE', 'ASRock',
        'ASUS', 'MSI', 'Intel', 'AMD', 'NVIDIA', 'WD', 'LG', 'Noctua',
    ];

    $brand = 'Generic';
    $model = $product['product_name'];

    foreach ($known_brands as $known) {
        if (stripos($product['product_name'], $known) === 0) {
            $brand = $known;
            $model = trim(substr($product['product_name'], strlen($known)));
            break;
        }
    }

    if ($model === '') {
        $model = $product['product_name'];
    }

    $warranty_by_category = [
        1  => '3 Years Limited Warranty',
        2  => '3 Years Limited Warranty',
        3  => '3 Years Limited Warranty',
        4  => 'Limited Lifetime Warranty',
        5  => '5 Years Limited Warranty',
        6  => '2 Years Limited Warranty',
        7  => '5 Years Limited Warranty',
        8  => '1 Year Limited Warranty',
        9  => '2 Years Limited Warranty',
        10 => '2 Years Limited Warranty',
        11 => '2 Years Limited Warranty',
        12 => '2 Years Limited Warranty',
        13 => '3 Years Limited Warranty',
        14 => '2 Years Limited Warranty',
    ];

    $compatibility_by_category = [
        1  => 'Compatible with matching CPU socket motherboards (check chipset support)',
        2  => 'Requires an available PCIe x16 slot and sufficient PSU wattage',
        3  => 'Compatible with matching CPU socket and DDR memory generation',
        4  => 'Compatible with motherboards supporting the listed DDR generation',
        5  => 'Requires an available M.2 NVMe slot or SATA port',
        6  => 'Requires an available SATA port and 3.5" drive bay',
        7  => 'Compatible with standard ATX power supply mounting in most cases',
        8  => 'Supports ATX, Micro-ATX, and Mini-ITX motherboards',
        9  => 'Compatible with major CPU sockets (check specific socket support)',
        10 => 'Fits standard 120mm/140mm case fan mounts',
        11 => 'Connects via USB, compatible with Windows and Mac',
        12 => 'Connects via USB, compatible with Windows and Mac',
        13 => 'Connects via HDMI/DisplayPort to any modern graphics card',
        14 => 'Connects via USB or 3.5mm jack, compatible with PC and console',
    ];

    return [
        'brand'         => $brand,
        'model'         => $model,
        'category'      => $product['category_name'],
        'availability'  => $product['stock'] > 0 ? 'In Stock' : 'Out of Stock',
        'warranty'      => $warranty_by_category[$product['category_id']] ?? '1 Year Limited Warranty',
        'compatibility' => $compatibility_by_category[$product['category_id']] ?? 'Compatible with standard PC builds',
    ];
}

$categories = [];
$cat_result = mysqli_query($conn, "SELECT * FROM categories ORDER BY category_id");
while ($cat = mysqli_fetch_assoc($cat_result)) {
    $categories[(int)$cat['category_id']] = [
        'category_name' => $cat['category_name'],
        'folder'         => $category_folders[(int)$cat['category_id']] ?? 'misc',
    ];
}

if ($category_filter > 0) {
    $stmt = mysqli_prepare(
        $conn,
        "SELECT p.*, c.category_name FROM products p
         JOIN categories c ON p.category_id = c.category_id
         WHERE p.category_id = ?
         ORDER BY p.product_id"
    );
    mysqli_stmt_bind_param($stmt, "i", $category_filter);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    $result = mysqli_query(
        $conn,
        "SELECT p.*, c.category_name FROM products p
         JOIN categories c ON p.category_id = c.category_id
         ORDER BY p.category_id, p.product_id"
    );
}

$products = [];
while ($row = mysqli_fetch_assoc($result)) {
    $row['category_id'] = (int)$row['category_id'];
    $row['product_id']  = (int)$row['product_id'];
    $row['stock']       = (int)$row['stock'];
    $row['price']       = (float)$row['price'];

    $folder = $category_folders[$row['category_id']] ?? 'misc';
    $row['image_folder'] = $folder;
    $row['image'] = resolve_product_image($folder, $row['image'] ?? null, $row['product_name']);
    $row['specs'] = generate_specs($row);

    $products[] = $row;
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

        <?php foreach ($categories as $category_id => $category) { ?>
            <a href="store.php?category=<?php echo $category_id; ?>" class="px-5 py-2.5 rounded-full text-sm font-bold <?php echo $category_filter == $category_id ? 'bg-cyan-400 text-slate-950' : 'bg-slate-900 border border-slate-800 text-slate-300 hover:border-cyan-400'; ?>">
                <?php echo htmlspecialchars($category['category_name']); ?>
            </a>
        <?php } ?>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">

        <?php foreach ($products as $product) { ?>
            <div
                class="group bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden hover:border-cyan-400 transition shadow-xl cursor-pointer product-card"
                data-product="<?php echo htmlspecialchars(json_encode($product), ENT_QUOTES, 'UTF-8'); ?>"
                onclick="openProductModal(this)"
            >

                <div class="h-64 bg-white flex items-center justify-center relative overflow-hidden p-6 box-border">
                    <img
                        src="<?php echo htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8'); ?>"
                        alt="<?php echo htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8'); ?>"
                        class="relative w-full h-full object-contain transition duration-500 group-hover:scale-105"
                        loading="lazy"
                        onerror="this.onerror=null;this.src='<?php echo placeholder_image($product['product_name']); ?>';"
                    >
                </div>

                <div class="p-6">
                    <p class="text-sm text-cyan-400 font-bold"><?php echo htmlspecialchars($product['category_name'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <h2 class="text-xl font-black mt-2"><?php echo htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8'); ?></h2>
                    <p class="text-slate-400 text-sm mt-3 min-h-12"><?php echo htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8'); ?></p>

                    <div class="flex items-center justify-between mt-5">
                        <p class="text-2xl font-black text-white"><?php echo format_price($product['price']); ?></p>
                        <p class="text-sm <?php echo $product['stock'] > 0 ? 'text-green-400' : 'text-red-400'; ?>">
                            Stock: <?php echo (int)$product['stock']; ?>
                        </p>
                    </div>

                    <form action="cart.php" method="POST" class="mt-6" onclick="event.stopPropagation()">
                        <input type="hidden" name="product_id" value="<?php echo (int)$product['product_id']; ?>">
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

<style>
    #productModalOverlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 24px;
        background-color: rgba(2, 6, 23, 0.85);
        opacity: 0;
        transition: opacity 0.25s ease;
        box-sizing: border-box;
    }

    #productModalOverlay.is-open {
        display: flex;
    }

    #productModalOverlay.is-visible {
        opacity: 1;
    }

    #productModalBox {
        background-color: #0f172a;
        border: 1px solid #1e293b;
        border-radius: 24px;
        box-shadow: 0 30px 80px rgba(0, 0, 0, 0.65);
        width: 100%;
        max-width: 1000px;
        max-height: 90vh;
        overflow-y: auto;
        position: relative;
        margin: 0 auto;
        transform: scale(0.94);
        opacity: 0;
        transition: transform 0.25s ease, opacity 0.25s ease;
        box-sizing: border-box;
    }

    #productModalOverlay.is-visible #productModalBox {
        transform: scale(1);
        opacity: 1;
    }

    .pm-content {
        display: flex;
        flex-direction: column;
    }

    .pm-image-col {
        width: 100%;
        height: 320px;
        position: relative;
        overflow: hidden;
        background-color: #ffffff;
        border-radius: 24px 24px 0 0;
        flex-shrink: 0;
        box-sizing: border-box;
        padding: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .pm-image-col img {
        position: relative;
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .pm-details-col {
        width: 100%;
        padding: 32px;
        box-sizing: border-box;
    }

    .pm-specs-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        row-gap: 12px;
        column-gap: 16px;
        font-size: 0.875rem;
        margin: 0;
    }

    .pm-divider {
        border-top: 1px solid #1e293b;
        padding-top: 24px;
        margin-top: 32px;
    }

    .pm-spec-title {
        font-size: 1.125rem;
        font-weight: 900;
        margin: 0 0 16px 0;
    }

    .pm-close-btn {
        position: absolute;
        top: 16px;
        right: 16px;
        z-index: 10;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9999px;
        background-color: #1e293b;
        color: #cbd5e1;
        font-weight: 900;
        font-size: 1.25rem;
        line-height: 1;
        border: none;
        cursor: pointer;
        transition: background-color 0.2s ease, color 0.2s ease;
    }

    .pm-close-btn:hover {
        background-color: #22d3ee;
        color: #020617;
    }

    .pm-cta-btn,
    .pm-cta-btn-disabled {
        width: 100%;
        padding: 16px;
        border-radius: 12px;
        font-weight: 900;
        font-size: 1.125rem;
        border: none;
        transition: background-color 0.2s ease, color 0.2s ease;
    }

    .pm-cta-btn {
        background-color: #22d3ee;
        color: #020617;
        cursor: pointer;
    }

    .pm-cta-btn:hover {
        background-color: #67e8f9;
    }

    .pm-cta-btn-disabled {
        background-color: #334155;
        color: #94a3b8;
        cursor: not-allowed;
    }

    @media (min-width: 768px) {
        .pm-content {
            flex-direction: row;
            min-height: 460px;
        }

        .pm-image-col {
            width: 50%;
            height: auto;
            border-radius: 24px 0 0 24px;
        }

        .pm-details-col {
            width: 50%;
        }
    }

    body.modal-open {
        overflow: hidden;
    }
</style>

<div id="productModalOverlay" onclick="handleOverlayClick(event)">
    <div id="productModalBox">

        <button
            type="button"
            onclick="closeProductModal()"
            class="pm-close-btn"
            aria-label="Close"
        >
            &times;
        </button>

        <div class="pm-content">

            <div class="pm-image-col">
                <img id="modalProductImage" src="" alt="">
            </div>

            <div class="pm-details-col">
                <p id="modalProductCategory" class="text-sm text-cyan-400 font-bold"></p>
                <h2 id="modalProductName" class="text-3xl font-black mt-2"></h2>
                <p id="modalProductDescription" class="text-slate-400 text-sm mt-4"></p>

                <div class="flex items-center justify-between mt-6">
                    <p id="modalProductPrice" class="text-3xl font-black text-white"></p>
                    <p id="modalProductStock" class="text-sm"></p>
                </div>

                <div class="pm-divider">
                    <h3 class="pm-spec-title">Specifications</h3>
                    <dl class="pm-specs-grid">
                        <dt class="text-slate-400 font-semibold">Brand</dt>
                        <dd id="modalSpecBrand" class="text-slate-200"></dd>

                        <dt class="text-slate-400 font-semibold">Model</dt>
                        <dd id="modalSpecModel" class="text-slate-200"></dd>

                        <dt class="text-slate-400 font-semibold">Category</dt>
                        <dd id="modalSpecCategory" class="text-slate-200"></dd>

                        <dt class="text-slate-400 font-semibold">Availability</dt>
                        <dd id="modalSpecAvailability" class="text-slate-200"></dd>

                        <dt class="text-slate-400 font-semibold">Warranty</dt>
                        <dd id="modalSpecWarranty" class="text-slate-200"></dd>

                        <dt class="text-slate-400 font-semibold">Compatibility</dt>
                        <dd id="modalSpecCompatibility" class="text-slate-200"></dd>
                    </dl>
                </div>

                <form action="cart.php" method="POST" class="mt-6">
                    <input type="hidden" name="product_id" id="modalProductId" value="">
                    <input type="hidden" name="action" value="add">

                    <button
                        type="submit"
                        id="modalAddToCartButton"
                        class="pm-cta-btn"
                    >
                        Add to Cart
                    </button>
                </form>
            </div>

        </div>

    </div>
</div>

<script>
    (function () {
        var overlay = document.getElementById('productModalOverlay');
        var modalBox = document.getElementById('productModalBox');

        window.openProductModal = function (cardElement) {
            var raw = cardElement.getAttribute('data-product');
            if (!raw) {
                return;
            }

            var product;
            try {
                product = JSON.parse(raw);
            } catch (e) {
                return;
            }

            document.getElementById('modalProductImage').src = product.image;
            document.getElementById('modalProductImage').alt = product.product_name;
            document.getElementById('modalProductImage').onerror = function () {
                this.onerror = null;
                this.src = product.image; 
            };

            document.getElementById('modalProductCategory').textContent = product.category_name;
            document.getElementById('modalProductName').textContent = product.product_name;
            document.getElementById('modalProductDescription').textContent = product.description;

            document.getElementById('modalProductPrice').textContent = formatModalPrice(product.price);

            var stockEl = document.getElementById('modalProductStock');
            var stock = parseInt(product.stock, 10) || 0;
            stockEl.textContent = 'Stock: ' + stock;
            stockEl.className = stock > 0 ? 'text-sm text-green-400' : 'text-sm text-red-400';

            var specs = product.specs || {};
            document.getElementById('modalSpecBrand').textContent = specs.brand || 'N/A';
            document.getElementById('modalSpecModel').textContent = specs.model || 'N/A';
            document.getElementById('modalSpecCategory').textContent = specs.category || product.category_name || 'N/A';
            document.getElementById('modalSpecAvailability').textContent = specs.availability || (stock > 0 ? 'In Stock' : 'Out of Stock');
            document.getElementById('modalSpecWarranty').textContent = specs.warranty || 'N/A';
            document.getElementById('modalSpecCompatibility').textContent = specs.compatibility || 'N/A';

            document.getElementById('modalProductId').value = product.product_id;

            var addToCartButton = document.getElementById('modalAddToCartButton');
            if (stock > 0) {
                addToCartButton.disabled = false;
                addToCartButton.textContent = 'Add to Cart';
                addToCartButton.className = 'pm-cta-btn';
            } else {
                addToCartButton.disabled = true;
                addToCartButton.textContent = 'Out of Stock';
                addToCartButton.className = 'pm-cta-btn-disabled';
            }

            overlay.classList.add('is-open');
            document.body.classList.add('modal-open');

            requestAnimationFrame(function () {
                overlay.classList.add('is-visible');
            });
        };

        window.closeProductModal = function () {
            overlay.classList.remove('is-visible');
            document.body.classList.remove('modal-open');

            setTimeout(function () {
                overlay.classList.remove('is-open');
            }, 250);
        };

        window.handleOverlayClick = function (event) {
            if (event.target === overlay) {
                closeProductModal();
            }
        };

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && overlay.classList.contains('is-open')) {
                closeProductModal();
            }
        });

        function formatModalPrice(price) {
            var value = parseFloat(price) || 0;
            return '₱' + value.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
    })();
</script>

<?php include 'includes/footer.php'; ?>