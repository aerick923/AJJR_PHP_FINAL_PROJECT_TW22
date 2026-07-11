<?php
include 'includes/databaseConnect.php';
include 'includes/header.php';

$category_filter = isset($_GET['category']) ? intval($_GET['category']) : 0;

function slugify_filename(string $text): string
{
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    $text = trim($text, '-');
    return $text . '.jpg';
}

$categories = [
    1  => ['category_name' => 'Processors',    'folder' => 'cpu'],
    2  => ['category_name' => 'Graphics Cards','folder' => 'gpu'],
    3  => ['category_name' => 'Motherboards',  'folder' => 'motherboard'],
    4  => ['category_name' => 'Memory',        'folder' => 'ram'],
    5  => ['category_name' => 'Storage SSDs',   'folder' => 'ssd'],
    6  => ['category_name' => 'Storage HDDs',   'folder' => 'hdd'],
    7  => ['category_name' => 'Power Supplies', 'folder' => 'psu'],
    8  => ['category_name' => 'PC Cases',       'folder' => 'case'],
    9  => ['category_name' => 'CPU Coolers',    'folder' => 'cooler'],
    10 => ['category_name' => 'Case Fans',      'folder' => 'fan'],
    11 => ['category_name' => 'Keyboards',      'folder' => 'keyboard'],
    12 => ['category_name' => 'Mice',           'folder' => 'mouse'],
    13 => ['category_name' => 'Monitors',       'folder' => 'monitor'],
    14 => ['category_name' => 'Headsets',       'folder' => 'headset'],
];

$product_specs = [
    ['product_id' => 1001, 'category_id' => 1,  'product_name' => 'Intel Core i3-14100F', 'description' => '4-core entry-level CPU for reliable everyday gaming and productivity builds.', 'price' => 6999,  'stock' => 18],
    ['product_id' => 1002, 'category_id' => 1,  'product_name' => 'Intel Core i5-14400F', 'description' => '10-core processor for balanced gaming, multitasking, and budget-friendly performance.', 'price' => 11499, 'stock' => 16],
    ['product_id' => 1003, 'category_id' => 1,  'product_name' => 'Intel Core i5-14600K',  'description' => 'Unlocked 14-core CPU with strong gaming and creator performance.', 'price' => 16999, 'stock' => 12],
    ['product_id' => 1004, 'category_id' => 1,  'product_name' => 'Intel Core i7-14700K',  'description' => 'High-performance desktop processor for demanding games and workloads.', 'price' => 23999, 'stock' => 9],
    ['product_id' => 1005, 'category_id' => 1,  'product_name' => 'AMD Ryzen 5 5600',      'description' => 'Affordable 6-core AM4 CPU for solid 1080p gaming builds.', 'price' => 7999,  'stock' => 20],
    ['product_id' => 1006, 'category_id' => 1,  'product_name' => 'AMD Ryzen 5 7600',      'description' => 'Efficient 6-core AM5 processor for modern gaming and daily use.', 'price' => 14999, 'stock' => 15],
    ['product_id' => 1007, 'category_id' => 1,  'product_name' => 'AMD Ryzen 7 7700X',     'description' => 'Fast 8-core chip ideal for gaming, streaming, and productivity.', 'price' => 20999, 'stock' => 11],
    ['product_id' => 1008, 'category_id' => 1,  'product_name' => 'AMD Ryzen 7 7800X3D',   'description' => 'Top-tier gaming CPU with 3D V-Cache for exceptional frame rates.', 'price' => 28999, 'stock' => 8],

    ['product_id' => 2001, 'category_id' => 2,  'product_name' => 'NVIDIA GeForce RTX 3050',     'description' => 'Entry-level GPU for esports, light AAA gaming, and everyday graphics use.', 'price' => 12999, 'stock' => 14],
    ['product_id' => 2002, 'category_id' => 2,  'product_name' => 'NVIDIA GeForce RTX 4060',      'description' => 'Efficient 1080p graphics card with modern ray tracing and DLSS support.', 'price' => 17999, 'stock' => 13],
    ['product_id' => 2003, 'category_id' => 2,  'product_name' => 'NVIDIA GeForce RTX 4070 SUPER','description' => 'Excellent 1440p GPU with strong performance and great power efficiency.', 'price' => 34999, 'stock' => 8],
    ['product_id' => 2004, 'category_id' => 2,  'product_name' => 'NVIDIA GeForce RTX 4080 SUPER','description' => 'High-end graphics card for premium 4K gaming and creator workloads.', 'price' => 59999, 'stock' => 4],
    ['product_id' => 2005, 'category_id' => 2,  'product_name' => 'AMD Radeon RX 6600',          'description' => 'Great-value 1080p Radeon card for smooth mainstream gaming.', 'price' => 11999, 'stock' => 15],
    ['product_id' => 2006, 'category_id' => 2,  'product_name' => 'AMD Radeon RX 7600',          'description' => 'Modern 1080p card with strong value and efficient performance.', 'price' => 15999, 'stock' => 12],
    ['product_id' => 2007, 'category_id' => 2,  'product_name' => 'AMD Radeon RX 7800 XT',       'description' => 'Powerful 1440p GPU with high VRAM and strong raster performance.', 'price' => 32999, 'stock' => 7],
    ['product_id' => 2008, 'category_id' => 2,  'product_name' => 'AMD Radeon RX 7900 XTX',      'description' => 'Flagship Radeon GPU built for premium 4K gaming and heavy workloads.', 'price' => 56999, 'stock' => 3],

    ['product_id' => 3001, 'category_id' => 3,  'product_name' => 'ASUS PRIME B760M-A D4',        'description' => 'Reliable Intel mATX motherboard with solid expansion and cooling.', 'price' => 7999,  'stock' => 14],
    ['product_id' => 3002, 'category_id' => 3,  'product_name' => 'MSI PRO Z790-A WIFI',          'description' => 'Feature-rich Intel motherboard with Wi-Fi and strong VRM support.', 'price' => 18999, 'stock' => 7],
    ['product_id' => 3003, 'category_id' => 3,  'product_name' => 'GIGABYTE B650M DS3H',         'description' => 'Affordable AM5 board for Ryzen builds with M.2 and upgrade support.', 'price' => 8999,  'stock' => 16],
    ['product_id' => 3004, 'category_id' => 3,  'product_name' => 'ASUS TUF GAMING X670E-PLUS WIFI','description' => 'Premium AM5 motherboard with excellent power delivery and connectivity.', 'price' => 21999, 'stock' => 6],
    ['product_id' => 3005, 'category_id' => 3,  'product_name' => 'ASRock B550M Steel Legend',    'description' => 'Popular AM4 motherboard with a durable design and solid features.', 'price' => 7499,  'stock' => 13],

    ['product_id' => 4001, 'category_id' => 4,  'product_name' => 'Kingston Fury Beast 16GB DDR4 3200MHz', 'description' => 'Stable dual-channel memory kit for mainstream desktop upgrades.', 'price' => 2499, 'stock' => 24],
    ['product_id' => 4002, 'category_id' => 4,  'product_name' => 'Corsair Vengeance LPX 32GB DDR4 3600MHz', 'description' => 'Popular capacity for gaming PCs, multitasking, and light content creation.', 'price' => 4499, 'stock' => 19],
    ['product_id' => 4003, 'category_id' => 4,  'product_name' => 'G.Skill Ripjaws S5 16GB DDR5 5600MHz', 'description' => 'Next-gen DDR5 memory for modern Intel and AMD platforms.', 'price' => 3499, 'stock' => 18],
    ['product_id' => 4004, 'category_id' => 4,  'product_name' => 'TeamGroup T-Force Delta RGB 32GB DDR5 6000MHz', 'description' => 'Fast RGB memory kit for gaming builds with a premium look.', 'price' => 6999, 'stock' => 14],
    ['product_id' => 4005, 'category_id' => 4,  'product_name' => 'Crucial Pro 64GB DDR5 5600MHz', 'description' => 'Large-capacity kit for creators, developers, and workstation builds.', 'price' => 12999, 'stock' => 8],

    ['product_id' => 5001, 'category_id' => 5,  'product_name' => 'Kingston NV2 500GB NVMe SSD', 'description' => 'Fast boot drive for entry-level builds and responsive everyday use.', 'price' => 1999, 'stock' => 28],
    ['product_id' => 5002, 'category_id' => 5,  'product_name' => 'WD Blue SN580 1TB NVMe SSD', 'description' => 'Reliable all-around SSD for games, apps, and file storage.', 'price' => 3499, 'stock' => 25],
    ['product_id' => 5003, 'category_id' => 5,  'product_name' => 'Samsung 990 PRO 1TB NVMe SSD', 'description' => 'High-performance PCIe 4.0 SSD for speed-focused builds.', 'price' => 5499, 'stock' => 16],
    ['product_id' => 5004, 'category_id' => 5,  'product_name' => 'Lexar NM790 2TB NVMe SSD', 'description' => 'Large-capacity Gen4 SSD for games and creative workloads.', 'price' => 6499, 'stock' => 12],
    ['product_id' => 5005, 'category_id' => 5,  'product_name' => 'Crucial MX500 1TB SATA SSD', 'description' => 'Affordable SATA SSD upgrade for desktops and older systems.', 'price' => 2699, 'stock' => 22],

    ['product_id' => 6001, 'category_id' => 6,  'product_name' => 'Seagate BarraCuda 1TB HDD', 'description' => 'Standard hard drive for budget storage and general file backups.', 'price' => 2299, 'stock' => 20],
    ['product_id' => 6002, 'category_id' => 6,  'product_name' => 'Seagate BarraCuda 2TB HDD', 'description' => 'Balanced mechanical drive for extra game and media storage.', 'price' => 3199, 'stock' => 18],
    ['product_id' => 6003, 'category_id' => 6,  'product_name' => 'WD Blue 4TB HDD', 'description' => 'Large-capacity drive for archives, backups, and bulk storage.', 'price' => 4999, 'stock' => 12],
    ['product_id' => 6004, 'category_id' => 6,  'product_name' => 'Toshiba P300 2TB HDD', 'description' => 'Reliable desktop HDD for everyday storage and media collections.', 'price' => 3099, 'stock' => 14],
    ['product_id' => 6005, 'category_id' => 6,  'product_name' => 'WD Purple 6TB HDD', 'description' => 'High-capacity drive designed for long-term storage and monitoring setups.', 'price' => 6999, 'stock' => 6],

    ['product_id' => 7001, 'category_id' => 7,  'product_name' => 'Cooler Master MWE 550 Bronze V2', 'description' => 'Reliable power supply for budget gaming and office builds.', 'price' => 2999, 'stock' => 22],
    ['product_id' => 7002, 'category_id' => 7,  'product_name' => 'Corsair CX650M 650W Bronze', 'description' => 'Semi-modular PSU with solid efficiency for mainstream systems.', 'price' => 3999, 'stock' => 18],
    ['product_id' => 7003, 'category_id' => 7,  'product_name' => 'MSI MAG A750GL PCIE5 750W Gold', 'description' => 'Modern ATX 3.0 PSU for new GPUs and performance builds.', 'price' => 5999, 'stock' => 13],
    ['product_id' => 7004, 'category_id' => 7,  'product_name' => 'Seasonic Focus GX-850 850W Gold', 'description' => 'High-quality fully modular PSU for powerful gaming rigs.', 'price' => 7999, 'stock' => 9],
    ['product_id' => 7005, 'category_id' => 7,  'product_name' => 'be quiet! Pure Power 12 M 1000W Gold', 'description' => 'Premium PSU with strong headroom for enthusiast and workstation builds.', 'price' => 9999, 'stock' => 5],

    ['product_id' => 8001, 'category_id' => 8,  'product_name' => 'Montech X3 Mesh', 'description' => 'Airflow-focused case with modern styling and easy build access.', 'price' => 3499, 'stock' => 16],
    ['product_id' => 8002, 'category_id' => 8,  'product_name' => 'NZXT H5 Flow', 'description' => 'Clean mid-tower case with strong airflow and a minimalist look.', 'price' => 4999, 'stock' => 12],
    ['product_id' => 8003, 'category_id' => 8,  'product_name' => 'Corsair 4000D Airflow', 'description' => 'Popular airflow case with excellent cable management and cooling support.', 'price' => 5499, 'stock' => 10],
    ['product_id' => 8004, 'category_id' => 8,  'product_name' => 'Lian Li LANCOOL 216', 'description' => 'Premium airflow case with large fans and a clean build layout.', 'price' => 6999, 'stock' => 8],
    ['product_id' => 8005, 'category_id' => 8,  'product_name' => 'Fractal Design Pop Air', 'description' => 'Stylish and practical case with great airflow and storage support.', 'price' => 5999, 'stock' => 9],

    ['product_id' => 9001, 'category_id' => 9,  'product_name' => 'DeepCool AK400', 'description' => 'Quiet single-tower air cooler for efficient mainstream CPU cooling.', 'price' => 1499, 'stock' => 20],
    ['product_id' => 9002, 'category_id' => 9,  'product_name' => 'Thermalright Peerless Assassin 120 SE', 'description' => 'Excellent dual-tower air cooler with strong thermal performance.', 'price' => 2299, 'stock' => 15],
    ['product_id' => 9003, 'category_id' => 9,  'product_name' => 'Noctua NH-D15', 'description' => 'Legendary premium air cooler for silent and powerful cooling.', 'price' => 4999, 'stock' => 6],
    ['product_id' => 9004, 'category_id' => 9,  'product_name' => 'Cooler Master Hyper 212 Halo', 'description' => 'Classic CPU cooler with modern RGB styling and dependable cooling.', 'price' => 1899, 'stock' => 18],
    ['product_id' => 9005, 'category_id' => 9,  'product_name' => 'Arctic Liquid Freezer III 240', 'description' => '240mm AIO liquid cooler for strong thermal performance and quiet operation.', 'price' => 4999, 'stock' => 7],

    ['product_id' => 10001, 'category_id' => 10, 'product_name' => 'ARCTIC P12 PWM PST 120mm', 'description' => 'Affordable high-airflow fan for quiet case cooling.', 'price' => 499, 'stock' => 30],
    ['product_id' => 10002, 'category_id' => 10, 'product_name' => 'Noctua NF-A12x25 PWM', 'description' => 'Premium fan known for quiet performance and high efficiency.', 'price' => 1299, 'stock' => 14],
    ['product_id' => 10003, 'category_id' => 10, 'product_name' => 'DeepCool FC120 3-Pack', 'description' => 'RGB fan kit for clean lighting and dependable airflow.', 'price' => 1999, 'stock' => 12],
    ['product_id' => 10004, 'category_id' => 10, 'product_name' => 'Lian Li UNI FAN SL120 V2', 'description' => 'Daisy-chain RGB fans for a tidy and premium-looking build.', 'price' => 3499, 'stock' => 8],
    ['product_id' => 10005, 'category_id' => 10, 'product_name' => 'Corsair iCUE AF120 RGB Elite', 'description' => 'Bright RGB fan set designed for performance and style.', 'price' => 2999, 'stock' => 10],

    ['product_id' => 11001, 'category_id' => 11, 'product_name' => 'Logitech G Pro Mechanical Keyboard', 'description' => 'Compact mechanical keyboard built for competitive gaming.', 'price' => 4999, 'stock' => 15],
    ['product_id' => 11002, 'category_id' => 11, 'product_name' => 'Razer Huntsman Mini', 'description' => 'Fast 60% keyboard with premium feel and sleek design.', 'price' => 5999, 'stock' => 11],
    ['product_id' => 11003, 'category_id' => 11, 'product_name' => 'Keychron K2 Wireless Mechanical Keyboard', 'description' => 'Versatile wireless mechanical keyboard for work and play.', 'price' => 5499, 'stock' => 13],
    ['product_id' => 11004, 'category_id' => 11, 'product_name' => 'SteelSeries Apex 3 TKL', 'description' => 'Durable tenkeyless keyboard with quiet switches and RGB lighting.', 'price' => 3499, 'stock' => 16],
    ['product_id' => 11005, 'category_id' => 11, 'product_name' => 'HyperX Alloy Origins Core', 'description' => 'Solid mechanical keyboard with a compact layout and sturdy build.', 'price' => 4599, 'stock' => 12],

    ['product_id' => 12001, 'category_id' => 12, 'product_name' => 'Logitech G502 X', 'description' => 'Familiar ergonomic mouse with precise tracking and extra controls.', 'price' => 3499, 'stock' => 18],
    ['product_id' => 12002, 'category_id' => 12, 'product_name' => 'Logitech G Pro X Superlight 2', 'description' => 'Ultra-light competitive mouse with top-tier wireless performance.', 'price' => 6999, 'stock' => 9],
    ['product_id' => 12003, 'category_id' => 12, 'product_name' => 'Razer DeathAdder V3', 'description' => 'Comfortable esports mouse with a proven shape and fast sensor.', 'price' => 4999, 'stock' => 14],
    ['product_id' => 12004, 'category_id' => 12, 'product_name' => 'Glorious Model O 2', 'description' => 'Lightweight mouse for fast movement and responsive gameplay.', 'price' => 3999, 'stock' => 12],
    ['product_id' => 12005, 'category_id' => 12, 'product_name' => 'SteelSeries Rival 3 Wireless', 'description' => 'Reliable wireless mouse with long battery life and clean design.', 'price' => 2999, 'stock' => 17],

    ['product_id' => 13001, 'category_id' => 13, 'product_name' => 'ASUS TUF Gaming VG249Q1R', 'description' => '24-inch gaming monitor with smooth refresh and vibrant image quality.', 'price' => 8999, 'stock' => 10],
    ['product_id' => 13002, 'category_id' => 13, 'product_name' => 'LG UltraGear 27GN800', 'description' => 'Popular 1440p gaming monitor with sharp visuals and fast response.', 'price' => 12999, 'stock' => 8],
    ['product_id' => 13003, 'category_id' => 13, 'product_name' => 'MSI G274QPF-QD', 'description' => 'Color-rich 27-inch monitor ideal for gaming and everyday work.', 'price' => 14999, 'stock' => 7],
    ['product_id' => 13004, 'category_id' => 13, 'product_name' => 'Gigabyte M27Q', 'description' => 'Versatile 27-inch display with great resolution and smooth gameplay.', 'price' => 15999, 'stock' => 6],
    ['product_id' => 13005, 'category_id' => 13, 'product_name' => 'Samsung Odyssey G5 34', 'description' => 'Wide ultrawide monitor for immersion, multitasking, and gaming.', 'price' => 24999, 'stock' => 5],

    ['product_id' => 14001, 'category_id' => 14, 'product_name' => 'HyperX Cloud III', 'description' => 'Comfortable gaming headset with clear audio and a detachable mic.', 'price' => 4499, 'stock' => 16],
    ['product_id' => 14002, 'category_id' => 14, 'product_name' => 'SteelSeries Arctis Nova 7', 'description' => 'Wireless headset with balanced sound and long-session comfort.', 'price' => 9999, 'stock' => 8],
    ['product_id' => 14003, 'category_id' => 14, 'product_name' => 'Logitech G Pro X Lightspeed', 'description' => 'Premium wireless headset built for gaming and communication.', 'price' => 7999, 'stock' => 9],
    ['product_id' => 14004, 'category_id' => 14, 'product_name' => 'Razer BlackShark V2 X', 'description' => 'Lightweight headset with clear voice pickup and gaming-focused audio.', 'price' => 2499, 'stock' => 20],
    ['product_id' => 14005, 'category_id' => 14, 'product_name' => 'Corsair HS80 RGB Wireless', 'description' => 'Feature-rich wireless headset with immersive sound and RGB styling.', 'price' => 7999, 'stock' => 7],
];

$products = [];
foreach ($product_specs as $spec) {
    if (!isset($categories[$spec['category_id']])) {
        continue;
    }

    $spec['category_name'] = $categories[$spec['category_id']]['category_name'];
    $spec['image_folder'] = $categories[$spec['category_id']]['folder'];
    $spec['image_filename'] = slugify_filename($spec['product_name']);
    $spec['image'] = 'assets/images/products/' . $spec['image_folder'] . '/' . $spec['image_filename'];
    $products[] = $spec;
}

if ($category_filter > 0) {
    $products = array_values(array_filter($products, function ($product) use ($category_filter) {
        return (int)$product['category_id'] === $category_filter;
    }));
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
            <div class="group bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden hover:border-cyan-400 transition shadow-xl">

                <div class="h-52 bg-gradient-to-br from-slate-800 to-slate-700 flex items-center justify-center relative overflow-hidden">
                    <div class="absolute inset-0 bg-cyan-400/5 group-hover:bg-cyan-400/10 transition"></div>
                    <img
                        src="<?php echo htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8'); ?>"
                        alt="<?php echo htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8'); ?>"
                        class="absolute inset-0 w-full h-full object-cover transition duration-500 group-hover:scale-105"
                        loading="lazy"
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

                    <form action="cart.php" method="POST" class="mt-6">
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

<?php include 'includes/footer.php'; ?>
