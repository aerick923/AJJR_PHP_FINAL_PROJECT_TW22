-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 15, 2026 at 09:21 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pc_parts_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `audit_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`audit_id`, `user_id`, `action`, `description`, `created_at`) VALUES
(1, 1, 'Database Setup', 'Initial database tables, categories, sample products, and test accounts were created.', '2026-06-29 13:16:14'),
(2, 1, 'Edit Product', 'Updated product: Redragon K552 Mechanical Keyboard', '2026-06-30 13:04:53'),
(3, 1, 'Edit Product', 'Updated product: Redragon K552 Mechanical Keyboard', '2026-06-30 13:05:26'),
(4, 1, 'Edit Product', 'Updated product: Redragon K552 Mechanical Keyboard', '2026-07-11 06:42:56'),
(5, 1, 'Add Product', 'Added product: Redragon K552 Mechanical Keyboard (test)', '2026-07-14 11:19:29'),
(6, 1, 'Edit Product', 'Updated product: Redragon K552 Mechanical Keyboard (test)', '2026-07-14 11:20:38'),
(7, 1, 'Delete Product', 'Deleted product ID: 75', '2026-07-14 11:23:03'),
(8, 1, 'Edit User', 'Updated user: buyer@ajjrpcparts.com', '2026-07-14 11:52:15'),
(9, 1, 'Delete User', 'Deleted user ID: 2', '2026-07-14 11:52:58'),
(10, 1, 'Edit User', 'Updated user: admin@ajjrpcparts.com', '2026-07-14 12:30:51'),
(11, 1, 'Edit User', 'Updated user: buyer@ajjrpcparts.com', '2026-07-14 12:34:13'),
(12, 3, 'Add Product', 'Added product: Razer BlackShark V2 X (test)', '2026-07-14 12:36:42'),
(13, 3, 'Edit User', 'Updated user: buyer@ajjrpcparts.com', '2026-07-14 12:37:40');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`) VALUES
(1, 'Processors'),
(2, 'Graphics Cards'),
(3, 'Motherboards'),
(4, 'Memory'),
(5, 'Storage SSDs'),
(6, 'Storage HDDs'),
(7, 'Power Supplies'),
(8, 'PC Cases'),
(9, 'CPU Coolers'),
(10, 'Case Fans'),
(11, 'Keyboards'),
(12, 'Mouse'),
(13, 'Monitors'),
(14, 'Headsets');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `order_status` varchar(50) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_proof` varchar(255) DEFAULT NULL,
  `reference_number` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `total_amount`, `payment_method`, `order_status`, `created_at`, `payment_proof`, `reference_number`) VALUES
(1, 3, 0.00, 'Cash on Delivery', 'Pending', '2026-07-14 12:10:24', NULL, NULL),
(2, 3, 0.00, 'Cash on Delivery', 'Pending', '2026-07-14 12:41:34', NULL, NULL),
(3, 3, 6999.00, 'Cash on Delivery', 'Pending', '2026-07-14 13:06:19', NULL, NULL),
(4, 3, 6999.00, 'Proof of Payment', 'Pending', '2026-07-14 13:18:22', 'uploads/payment_proofs/proof_3_1784035102_b3fb5468.png', ''),
(5, 3, 6999.00, 'Proof of Payment', 'Pending', '2026-07-14 13:24:38', 'uploads/payment_proofs/proof_3_1784035478_262c81aa.png', ''),
(6, 3, 18498.00, 'Proof of Payment', 'Pending', '2026-07-14 13:28:42', 'uploads/payment_proofs/proof_3_1784035722_0f649b17.png', ''),
(7, 3, 6999.00, 'Proof of Payment', 'Pending', '2026-07-14 13:32:04', 'uploads/payment_proofs/proof_3_1784035924_659ebbac.png', ''),
(8, 3, 38995.00, 'Proof of Payment', 'Pending', '2026-07-14 13:40:03', 'uploads/payment_proofs/proof_3_1784036403_dcea24f1.png', '');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 3, 1, 1, 6999.00),
(2, 4, 1, 1, 6999.00),
(3, 5, 1, 1, 6999.00),
(4, 6, 1, 1, 6999.00),
(5, 6, 2, 1, 11499.00),
(6, 7, 1, 1, 6999.00),
(7, 8, 1, 3, 6999.00),
(8, 8, 65, 2, 8999.00);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `reference_number` varchar(100) DEFAULT NULL,
  `amount_paid` decimal(10,2) NOT NULL,
  `payment_status` varchar(50) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(150) NOT NULL,
  `category_id` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT 'default.png',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `category_id`, `description`, `price`, `stock`, `image`, `created_at`) VALUES
(1, 'Intel Core i3-14100F', 1, '4-core entry-level CPU for reliable everyday gaming and productivity builds.', 6999.00, 10, 'intel-core-i3-14100f.jpg', '2026-07-14 11:03:28'),
(2, 'Intel Core i5-14400F', 1, '10-core processor for balanced gaming, multitasking, and budget-friendly performance.', 11499.00, 15, 'intel-core-i5-14400f.jpg', '2026-07-14 11:03:28'),
(3, 'Intel Core i5-14600K', 1, 'Unlocked 14-core CPU with strong gaming and creator performance.', 16999.00, 12, 'intel-core-i5-14600k.jpg', '2026-07-14 11:03:28'),
(4, 'Intel Core i7-14700K', 1, 'High-performance desktop processor for demanding games and workloads.', 23999.00, 9, 'intel-core-i7-14700k.jpg', '2026-07-14 11:03:28'),
(5, 'AMD Ryzen 5 5600', 1, 'Affordable 6-core AM4 CPU for solid 1080p gaming builds.', 7999.00, 20, 'amd-ryzen-5-5600.jpg', '2026-07-14 11:03:28'),
(6, 'AMD Ryzen 5 7600', 1, 'Efficient 6-core AM5 processor for modern gaming and daily use.', 14999.00, 15, 'amd-ryzen-5-7600.jpg', '2026-07-14 11:03:28'),
(7, 'AMD Ryzen 7 7700X', 1, 'Fast 8-core chip ideal for gaming, streaming, and productivity.', 20999.00, 11, 'amd-ryzen-7-7700x.jpg', '2026-07-14 11:03:28'),
(8, 'AMD Ryzen 7 7800X3D', 1, 'Top-tier gaming CPU with 3D V-Cache for exceptional frame rates.', 28999.00, 8, 'amd-ryzen-7-7800x3d.jpg', '2026-07-14 11:03:28'),
(9, 'NVIDIA GeForce RTX 3050', 2, 'Entry-level GPU for esports, light AAA gaming, and everyday graphics use.', 12999.00, 14, 'nvidia-geforce-rtx-3050.jpg', '2026-07-14 11:03:28'),
(10, 'NVIDIA GeForce RTX 4060', 2, 'Efficient 1080p graphics card with modern ray tracing and DLSS support.', 17999.00, 13, 'nvidia-geforce-rtx-4060.jpg', '2026-07-14 11:03:28'),
(11, 'NVIDIA GeForce RTX 4070 SUPER', 2, 'Excellent 1440p GPU with strong performance and great power efficiency.', 34999.00, 8, 'nvidia-geforce-rtx-4070-super.jpg', '2026-07-14 11:03:28'),
(12, 'AMD Radeon RX 7600', 2, 'Modern 1080p card with strong value and efficient performance.', 15999.00, 12, 'amd-radeon-rx-7600.jpg', '2026-07-14 11:03:28'),
(13, 'AMD Radeon RX 7800 XT', 2, 'Powerful 1440p GPU with high VRAM and strong raster performance.', 32999.00, 7, 'amd-radeon-rx-7800-xt.jpg', '2026-07-14 11:03:28'),
(14, 'AMD Radeon RX 7900 XTX', 2, 'Flagship Radeon GPU built for premium 4K gaming and heavy workloads.', 56999.00, 3, 'amd-radeon-rx-7900-xtx.jpg', '2026-07-14 11:03:28'),
(15, 'ASUS PRIME B760M-A D4', 3, 'Reliable Intel mATX motherboard with solid expansion and cooling.', 7999.00, 14, 'asus-prime-b760m-a-d4.jpg', '2026-07-14 11:03:28'),
(16, 'MSI PRO Z790-A WIFI', 3, 'Feature-rich Intel motherboard with Wi-Fi and strong VRM support.', 18999.00, 7, 'msi-pro-z790-a-wifi.jpg', '2026-07-14 11:03:28'),
(17, 'GIGABYTE B650M DS3H', 3, 'Affordable AM5 board for Ryzen builds with M.2 and upgrade support.', 8999.00, 16, 'gigabyte-b650m-ds3h.jpg', '2026-07-14 11:03:28'),
(18, 'ASUS TUF GAMING X670E-PLUS WIFI', 3, 'Premium AM5 motherboard with excellent power delivery and connectivity.', 21999.00, 6, 'asus-tuf-gaming-x670e-plus-wifi.jpg', '2026-07-14 11:03:28'),
(19, 'ASRock B550M Steel Legend', 3, 'Popular AM4 motherboard with a durable design and solid features.', 7499.00, 13, 'asrock-b550m-steel-legend.jpg', '2026-07-14 11:03:28'),
(20, 'Kingston Fury Beast 16GB DDR4 3200MHz', 4, 'Stable dual-channel memory kit for mainstream desktop upgrades.', 2499.00, 24, 'kingston-fury-beast-16gb-ddr4-3200mhz.jpg', '2026-07-14 11:03:28'),
(21, 'Corsair Vengeance LPX 32GB DDR4 3600MHz', 4, 'Popular capacity for gaming PCs, multitasking, and light content creation.', 4499.00, 19, 'corsair-vengeance-lpx-32gb-ddr4-3600mhz.jpg', '2026-07-14 11:03:28'),
(22, 'G.Skill Ripjaws S5 16GB DDR5 5600MHz', 4, 'Next-gen DDR5 memory for modern Intel and AMD platforms.', 3499.00, 18, 'gskill-ripjaws-s5-16gb-ddr5-5600mhz.jpg', '2026-07-14 11:03:28'),
(23, 'TeamGroup T-Force Delta RGB 32GB DDR5 6000MHz', 4, 'Fast RGB memory kit for gaming builds with a premium look.', 6999.00, 14, 'teamgroup-tforce-delta-rgb-32gb-ddr5-6000mhz.jpg', '2026-07-14 11:03:28'),
(24, 'Crucial Pro 64GB DDR5 5600MHz', 4, 'Large-capacity kit for creators, developers, and workstation builds.', 12999.00, 8, 'crucial-pro-64gb-ddr5-5600mhz.jpg', '2026-07-14 11:03:28'),
(25, 'Kingston NV2 500GB NVMe SSD', 5, 'Fast boot drive for entry-level builds and responsive everyday use.', 1999.00, 28, 'kingston-nv2-500gb-nvme-ssd.jpg', '2026-07-14 11:03:28'),
(26, 'WD Blue SN580 1TB NVMe SSD', 5, 'Reliable all-around SSD for games, apps, and file storage.', 3499.00, 25, 'wd-blue-sn580-1tb-nvme-ssd.jpg', '2026-07-14 11:03:28'),
(27, 'Samsung 990 PRO 1TB NVMe SSD', 5, 'High-performance PCIe 4.0 SSD for speed-focused builds.', 5499.00, 16, 'samsung-990-pro-1tb-nvme-ssd.jpg', '2026-07-14 11:03:28'),
(28, 'Lexar NM790 2TB NVMe SSD', 5, 'Large-capacity Gen4 SSD for games and creative workloads.', 6499.00, 12, 'lexar-nm790-2tb-nvme-ssd.jpg', '2026-07-14 11:03:28'),
(29, 'Crucial MX500 1TB SATA SSD', 5, 'Affordable SATA SSD upgrade for desktops and older systems.', 2699.00, 22, 'crucial-mx500-1tb-sata-ssd.jpg', '2026-07-14 11:03:28'),
(30, 'Seagate BarraCuda 1TB HDD', 6, 'Standard hard drive for budget storage and general file backups.', 2299.00, 20, 'seagate-barracuda-1tb-hd.jpg', '2026-07-14 11:03:28'),
(31, 'Seagate BarraCuda 2TB HDD', 6, 'Balanced mechanical drive for extra game and media storage.', 3199.00, 18, 'seagate-barracuda-2tb-hdd.jpg', '2026-07-14 11:03:28'),
(32, 'WD Blue 4TB HDD', 6, 'Large-capacity drive for archives, backups, and bulk storage.', 4999.00, 12, 'wd-blue-4tb-hdd.jpg', '2026-07-14 11:03:28'),
(33, 'Toshiba P300 2TB HDD', 6, 'Reliable desktop HDD for everyday storage and media collections.', 3099.00, 14, 'toshiba-p300-2tb-hdd.jpg', '2026-07-14 11:03:28'),
(34, 'WD Purple 6TB HDD', 6, 'High-capacity drive designed for long-term storage and monitoring setups.', 6999.00, 6, 'wd-purple-6tb-hdd.jpg', '2026-07-14 11:03:28'),
(35, 'Cooler Master MWE 550 Bronze V2', 7, 'Reliable power supply for budget gaming and office builds.', 2999.00, 22, 'cooler-master-mwe-550-bronze-v2.jpg', '2026-07-14 11:03:28'),
(36, 'Corsair CX650M 650W Bronze', 7, 'Semi-modular PSU with solid efficiency for mainstream systems.', 3999.00, 18, 'corsair-cx650m-650w-bronze.jpg', '2026-07-14 11:03:28'),
(37, 'MSI MAG A750GL PCIE5 750W Gold', 7, 'Modern ATX 3.0 PSU for new GPUs and performance builds.', 5999.00, 13, 'msi-mag-a750gl-pcie5-750w-gold.jpg', '2026-07-14 11:03:28'),
(38, 'Seasonic Focus GX-850 850W Gold', 7, 'High-quality fully modular PSU for powerful gaming rigs.', 7999.00, 9, 'seasonic-focus-gx850-850w-gold.jpg', '2026-07-14 11:03:28'),
(39, 'be quiet! Pure Power 12 M 1000W Gold', 7, 'Premium PSU with strong headroom for enthusiast and workstation builds.', 9999.00, 5, 'be-quiet-pure-power-12m-1000w-gold.jpg', '2026-07-14 11:03:28'),
(40, 'Montech X3 Mesh', 8, 'Airflow-focused case with modern styling and easy build access.', 3499.00, 16, 'montech-x3-mesh.jpg', '2026-07-14 11:03:28'),
(41, 'NZXT H5 Flow', 8, 'Clean mid-tower case with strong airflow and a minimalist look.', 4999.00, 12, 'nzxt-h5-flow.jpg', '2026-07-14 11:03:28'),
(42, 'Corsair 4000D Airflow', 8, 'Popular airflow case with excellent cable management and cooling support.', 5499.00, 10, 'corsair-4000d-airflow.jpg', '2026-07-14 11:03:28'),
(43, 'Lian Li LANCOOL 216', 8, 'Premium airflow case with large fans and a clean build layout.', 6999.00, 8, 'lian-li-lancool-216.jpg', '2026-07-14 11:03:28'),
(44, 'Fractal Design Pop Air', 8, 'Stylish and practical case with great airflow and storage support.', 5999.00, 9, 'fractal-design-pop-air.jpg', '2026-07-14 11:03:28'),
(45, 'DeepCool AK400', 9, 'Quiet single-tower air cooler for efficient mainstream CPU cooling.', 1499.00, 20, 'deepcool-ak400.jpg', '2026-07-14 11:03:28'),
(46, 'Thermalright Peerless Assassin 120 SE', 9, 'Excellent dual-tower air cooler with strong thermal performance.', 2299.00, 15, 'thermalright-peerless-assassin-120-se.jpg', '2026-07-14 11:03:28'),
(47, 'Noctua NH-D15', 9, 'Legendary premium air cooler for silent and powerful cooling.', 4999.00, 6, 'noctua-nh-d15.jpg', '2026-07-14 11:03:28'),
(48, 'Cooler Master Hyper 212 Halo', 9, 'Classic CPU cooler with modern RGB styling and dependable cooling.', 1899.00, 18, 'cooler-master-hyper-212-halo.jpg', '2026-07-14 11:03:28'),
(49, 'Arctic Liquid Freezer III 240', 9, '240mm AIO liquid cooler for strong thermal performance and quiet operation.', 4999.00, 7, 'arctic-liquid-freezer-iii-240.jpg', '2026-07-14 11:03:28'),
(50, 'ARCTIC P12 PWM PST 120mm', 10, 'Affordable high-airflow fan for quiet case cooling.', 499.00, 30, 'arctic-p12-pwm-pst-120mm.jpg', '2026-07-14 11:03:28'),
(51, 'Noctua NF-A12x25 PWM', 10, 'Premium fan known for quiet performance and high efficiency.', 1299.00, 14, 'noctua-nf-a12x25-pwm.jpg', '2026-07-14 11:03:28'),
(52, 'DeepCool FC120 3-Pack', 10, 'RGB fan kit for clean lighting and dependable airflow.', 1999.00, 12, 'deepcool-fc120-3pack.jpg', '2026-07-14 11:03:28'),
(53, 'Lian Li UNI FAN SL120 V2', 10, 'Daisy-chain RGB fans for a tidy and premium-looking build.', 3499.00, 8, 'lian-li-uni-fan-sl120-v2.jpg', '2026-07-14 11:03:28'),
(54, 'Corsair iCUE AF120 RGB Elite', 10, 'Bright RGB fan set designed for performance and style.', 2999.00, 10, 'corsair-icue-af120-rgb-elite.jpg', '2026-07-14 11:03:28'),
(55, 'Logitech G Pro Mechanical Keyboard', 11, 'Compact mechanical keyboard built for competitive gaming.', 4999.00, 15, 'logitech-g-pro-mechanical-keyboard.jpg', '2026-07-14 11:03:28'),
(56, 'Razer Huntsman Mini', 11, 'Fast 60% keyboard with premium feel and sleek design.', 5999.00, 11, 'razer-huntsman-mini.jpg', '2026-07-14 11:03:28'),
(57, 'Keychron K2 Wireless Mechanical Keyboard', 11, 'Versatile wireless mechanical keyboard for work and play.', 5499.00, 13, 'keychron-k2-wireless-mechanical-keyboard.jpg', '2026-07-14 11:03:28'),
(58, 'SteelSeries Apex 3 TKL', 11, 'Durable tenkeyless keyboard with quiet switches and RGB lighting.', 3499.00, 16, 'steelseries-apex-3-tk.jpg', '2026-07-14 11:03:28'),
(59, 'HyperX Alloy Origins Core', 11, 'Solid mechanical keyboard with a compact layout and sturdy build.', 4599.00, 12, 'hyperx-alloy-origins-core.jpg', '2026-07-14 11:03:28'),
(60, 'Logitech G502 X', 12, 'Familiar ergonomic mouse with precise tracking and extra controls.', 3499.00, 18, 'logitech-g502-x.jpg', '2026-07-14 11:03:28'),
(61, 'Logitech G Pro X Superlight 2', 12, 'Ultra-light competitive mouse with top-tier wireless performance.', 6999.00, 9, 'logitech-g-pro-x-superlight-2.jpg', '2026-07-14 11:03:28'),
(62, 'Razer DeathAdder V3', 12, 'Comfortable esports mouse with a proven shape and fast sensor.', 4999.00, 14, 'razer-deathadder-v3.jpg', '2026-07-14 11:03:28'),
(63, 'Glorious Model O 2', 12, 'Lightweight mouse for fast movement and responsive gameplay.', 3999.00, 12, 'glorious-model-o-2.jpg', '2026-07-14 11:03:28'),
(64, 'SteelSeries Rival 3 Wireless', 12, 'Reliable wireless mouse with long battery life and clean design.', 2999.00, 17, 'steelseries-rival-3-wireless.jpg', '2026-07-14 11:03:28'),
(65, 'ASUS TUF Gaming VG249Q1R', 13, '24-inch gaming monitor with smooth refresh and vibrant image quality.', 8999.00, 8, 'asus-tuf-gaming-vg249q1r.jpg', '2026-07-14 11:03:28'),
(66, 'LG UltraGear 27GN800', 13, 'Popular 1440p gaming monitor with sharp visuals and fast response.', 12999.00, 8, 'lg-ultragear-27gn800.jpg', '2026-07-14 11:03:28'),
(67, 'MSI G274QPF-QD', 13, 'Color-rich 27-inch monitor ideal for gaming and everyday work.', 14999.00, 7, 'msi-g274qpf-qd.jpg', '2026-07-14 11:03:28'),
(68, 'Gigabyte M27Q', 13, 'Versatile 27-inch display with great resolution and smooth gameplay.', 15999.00, 6, 'gigabyte-m27q.jpg', '2026-07-14 11:03:28'),
(69, 'Samsung Odyssey G5 34', 13, 'Wide ultrawide monitor for immersion, multitasking, and gaming.', 24999.00, 5, 'samsung-odyssey-g5-34.jpg', '2026-07-14 11:03:28'),
(70, 'HyperX Cloud III', 14, 'Comfortable gaming headset with clear audio and a detachable mic.', 4499.00, 16, 'hyperx-cloud-iii.jpg', '2026-07-14 11:03:28'),
(71, 'SteelSeries Arctis Nova 7', 14, 'Wireless headset with balanced sound and long-session comfort.', 9999.00, 8, 'steelseries-arctis-nova-7.jpg', '2026-07-14 11:03:28'),
(72, 'Logitech G Pro X Lightspeed', 14, 'Premium wireless headset built for gaming and communication.', 7999.00, 9, 'logitech-g-pro-x-lightspeed.jpg', '2026-07-14 11:03:28'),
(73, 'Razer BlackShark V2 X', 14, 'Lightweight headset with clear voice pickup and gaming-focused audio.', 2499.00, 20, 'razer-blackshark-v2-x.jpg', '2026-07-14 11:03:28'),
(74, 'Corsair HS80 RGB Wireless', 14, 'Feature-rich wireless headset with immersive sound and RGB styling.', 7999.00, 7, 'corsair-hs80-rgb-wireless.jpg', '2026-07-14 11:03:28'),
(76, 'Razer BlackShark V2 X (test)', 14, '(test)', 2000.00, 5, 'redragon-k552.jpg', '2026-07-14 12:36:42');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL DEFAULT '',
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `region` varchar(100) DEFAULT NULL,
  `province` varchar(150) DEFAULT NULL,
  `municipality` varchar(150) DEFAULT NULL,
  `barangay` varchar(150) DEFAULT NULL,
  `contact_number` varchar(20) NOT NULL,
  `role` enum('buyer','admin') NOT NULL DEFAULT 'buyer',
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `verification_token` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `middle_name`, `last_name`, `email`, `password`, `region`, `province`, `municipality`, `barangay`, `contact_number`, `role`, `is_verified`, `verification_token`, `created_at`) VALUES
(1, 'Jose', 'Mercado', 'Cordero', 'admin@ajjrpcparts.com', '$2y$10$6esI6UZFJG8lcfWXYi.jD.VxqJwnFdhDN4bHZII87h719.BE9Ma7S', '10', 'BUKIDNON', 'SAN FERNANDO', 'NACABUKLAD', '09123456789', 'admin', 1, NULL, '2026-06-29 13:16:14'),
(3, 'Aerick Lee', 'Puzon', 'Alba', 'buyer@ajjrpcparts.com', '$2y$10$5OU8YX318WCC6sX2mI1mUeaG.sXcM/VWPHjzr1QzcNKkuNRiflmKW', 'NCR', 'NATIONAL CAPITAL REGION - SECOND DISTRICT', 'QUEZON CITY', 'SANGANDAAN', '09202639948', 'buyer', 1, NULL, '2026-07-14 11:54:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`audit_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `audit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
