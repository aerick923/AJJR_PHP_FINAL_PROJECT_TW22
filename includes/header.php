<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once 'includes/functions.php';

$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AJJR PC Parts</title>
    <link href="./output.css" rel="stylesheet">
</head>

<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col">

<header class="sticky top-0 z-50 bg-slate-950/80 backdrop-blur-xl border-b border-slate-800">
    <nav class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

        <a href="index.php" class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-2xl bg-cyan-400 text-slate-950 flex items-center justify-center font-black text-lg shadow-lg shadow-cyan-500/20">
                AJ
            </div>

            <div>
                <h1 class="font-black text-lg leading-none">AJJR PC Parts</h1>
                <p class="text-xs text-slate-400">Online PC Parts Store</p>
            </div>
        </a>

        <div class="hidden md:flex items-center gap-6 text-sm font-medium">
            <a href="index.php" class="<?php echo $currentPage == 'index.php' ? 'text-cyan-400' : 'text-slate-300 hover:text-cyan-400'; ?>">Home</a>
            <a href="store.php" class="<?php echo $currentPage == 'store.php' ? 'text-cyan-400' : 'text-slate-300 hover:text-cyan-400'; ?>">Store</a>
            <a href="cart.php" class="<?php echo $currentPage == 'cart.php' ? 'text-cyan-400' : 'text-slate-300 hover:text-cyan-400'; ?>">
                Cart
                <span class="ml-1 bg-cyan-400 text-slate-950 px-2 py-0.5 rounded-full text-xs">
                    <?php echo cart_count(); ?>
                </span>
            </a>
            <a href="about.php" class="<?php echo $currentPage == 'about.php' ? 'text-cyan-400' : 'text-slate-300 hover:text-cyan-400'; ?>">About</a>

            <?php if (is_logged_in()) { ?>
                <a href="logout.php" class="bg-red-500 hover:bg-red-400 text-white px-4 py-2 rounded-xl transition">Logout</a>
            <?php } else { ?>
                <a href="login.php" class="text-slate-300 hover:text-cyan-400">Login</a>
                <a href="register.php" class="bg-cyan-400 hover:bg-cyan-300 text-slate-950 px-5 py-2.5 rounded-xl font-bold transition shadow-lg shadow-cyan-500/20">Register</a>
            <?php } ?>
        </div>
    </nav>
</header>

<main class="flex-grow w-full">
