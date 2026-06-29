<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AJJR PC Parts | Home</title>
    <link href="./output.css" rel="stylesheet">
</head>

<body class="bg-slate-950 text-white">

    <!-- NAVBAR -->
    <header class="sticky top-0 z-50 bg-slate-950/80 backdrop-blur-lg border-b border-slate-800">
        <nav class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

            <!-- LOGO / GROUP NAME -->
            <a href="index.php" class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-2xl bg-cyan-400 text-slate-950 flex items-center justify-center font-black text-lg">
                    AJ
                </div>

                <div>
                    <h1 class="font-black text-lg leading-none">AJJR PC Parts</h1>
                    <p class="text-xs text-slate-400">Online PC Parts Store</p>
                </div>
            </a>

            <div class="hidden md:flex items-center gap-7 text-sm font-medium">
                <a href="index.php" class="text-cyan-400">Home</a>
                <a href="store.php" class="text-slate-300 hover:text-cyan-400 transition">Store</a>
                <a href="cart.php" class="text-slate-300 hover:text-cyan-400 transition">Cart</a>
                <a href="about.php" class="text-slate-300 hover:text-cyan-400 transition">About</a>
                <a href="login.php" class="text-slate-300 hover:text-cyan-400 transition">Login</a>
                <a href="register.php" class="bg-cyan-400 hover:bg-cyan-300 text-slate-950 px-5 py-2.5 rounded-xl font-bold transition">
                    Register
                </a>
            </div>

        </nav>
    </header>

    <!-- HERO SECTION -->
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/10 via-slate-950 to-blue-700/10"></div>

        <div class="relative max-w-7xl mx-auto px-6 py-20 grid lg:grid-cols-2 gap-14 items-center">

            <!-- LEFT CONTENT -->
            <div>
                <div class="inline-flex items-center gap-2 bg-slate-900 border border-slate-800 rounded-full px-4 py-2 mb-6">
                    <span class="w-2 h-2 bg-cyan-400 rounded-full"></span>
                    <p class="text-sm text-slate-300">Quality parts for your dream setup</p>
                </div>

                <h1 class="text-5xl md:text-7xl font-black leading-tight">
                    Build Faster.
                    <span class="text-cyan-400">Game Better.</span>
                    Upgrade Smarter.
                </h1>

                <p class="text-slate-400 text-lg mt-6 max-w-xl leading-relaxed">
                    AJJR PC Parts is your online store for processors, RAM, graphics cards,
                    motherboards, storage devices, power supplies, and gaming peripherals.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 mt-9">
                    <a href="store.php" class="bg-cyan-400 hover:bg-cyan-300 text-slate-950 px-8 py-4 rounded-2xl font-black text-center transition shadow-lg shadow-cyan-500/20">
                        Shop PC Parts
                    </a>

                    <a href="about.php" class="border border-slate-700 hover:border-cyan-400 px-8 py-4 rounded-2xl font-black text-center transition">
                        Learn More
                    </a>
                </div>

                <div class="grid grid-cols-3 gap-5 mt-12 max-w-lg">
                    <div>
                        <h2 class="text-3xl font-black text-cyan-400">7+</h2>
                        <p class="text-sm text-slate-400">Categories</p>
                    </div>

                    <div>
                        <h2 class="text-3xl font-black text-cyan-400">24/7</h2>
                        <p class="text-sm text-slate-400">Online Access</p>
                    </div>

                    <div>
                        <h2 class="text-3xl font-black text-cyan-400">100%</h2>
                        <p class="text-sm text-slate-400">Project Demo</p>
                    </div>
                </div>
            </div>

            <!-- RIGHT CARD -->
            <div class="relative">
                <div class="absolute -inset-4 bg-cyan-400/20 rounded-[2rem] blur-3xl"></div>

                <div class="relative bg-slate-900 border border-slate-800 rounded-[2rem] p-6 shadow-2xl">
                    <div class="bg-gradient-to-br from-cyan-400 to-blue-600 rounded-[1.5rem] p-8 min-h-[360px] flex flex-col justify-between">

                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-slate-900 font-bold">Featured Build</p>
                                <h2 class="text-4xl font-black text-white mt-2">Gaming Ready</h2>
                            </div>

                            <div class="bg-white/20 backdrop-blur rounded-2xl px-4 py-2 text-white font-bold">
                                NEW
                            </div>
                        </div>

                        <div class="text-center py-8">
                            <div class="text-8xl">🖥️</div>
                        </div>

                        <div class="bg-slate-950/30 rounded-2xl p-5 backdrop-blur">
                            <div class="flex justify-between text-white mb-3">
                                <span>Ryzen Processor</span>
                                <span>✓</span>
                            </div>

                            <div class="flex justify-between text-white mb-3">
                                <span>16GB RAM</span>
                                <span>✓</span>
                            </div>

                            <div class="flex justify-between text-white">
                                <span>RTX Graphics</span>
                                <span>✓</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- CATEGORIES -->
    <section class="max-w-7xl mx-auto px-6 py-16">
        <div class="text-center mb-12">
            <p class="text-cyan-400 font-semibold">Shop by Category</p>
            <h2 class="text-4xl md:text-5xl font-black mt-3">Find the Parts You Need</h2>
            <p class="text-slate-400 mt-4 max-w-2xl mx-auto">
                Browse our main product categories for your next PC build or upgrade.
            </p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">

            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 hover:border-cyan-400 transition group">
                <div class="w-14 h-14 bg-cyan-400/10 rounded-2xl flex items-center justify-center text-3xl mb-5 group-hover:bg-cyan-400/20">
                    ⚙️
                </div>
                <h3 class="text-xl font-black">Processors</h3>
                <p class="text-slate-400 text-sm mt-2">Powerful CPUs for gaming and productivity.</p>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 hover:border-cyan-400 transition group">
                <div class="w-14 h-14 bg-cyan-400/10 rounded-2xl flex items-center justify-center text-3xl mb-5 group-hover:bg-cyan-400/20">
                    🧠
                </div>
                <h3 class="text-xl font-black">RAM</h3>
                <p class="text-slate-400 text-sm mt-2">Smooth multitasking with reliable memory.</p>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 hover:border-cyan-400 transition group">
                <div class="w-14 h-14 bg-cyan-400/10 rounded-2xl flex items-center justify-center text-3xl mb-5 group-hover:bg-cyan-400/20">
                    🎮
                </div>
                <h3 class="text-xl font-black">Graphics Cards</h3>
                <p class="text-slate-400 text-sm mt-2">Better visuals for games, editing, and design.</p>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 hover:border-cyan-400 transition group">
                <div class="w-14 h-14 bg-cyan-400/10 rounded-2xl flex items-center justify-center text-3xl mb-5 group-hover:bg-cyan-400/20">
                    💾
                </div>
                <h3 class="text-xl font-black">Storage</h3>
                <p class="text-slate-400 text-sm mt-2">Fast SSDs and storage for your files and games.</p>
            </div>

        </div>
    </section>

    <!-- FEATURED PRODUCTS PREVIEW -->
    <section class="max-w-7xl mx-auto px-6 py-16">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-5 mb-10">
            <div>
                <p class="text-cyan-400 font-semibold">Featured Products</p>
                <h2 class="text-4xl font-black mt-3">Popular PC Parts</h2>
            </div>

            <a href="store.php" class="text-cyan-400 font-bold hover:text-cyan-300 transition">
                View All Products →
            </a>
        </div>

        <div class="grid md:grid-cols-3 gap-8">

            <div class="bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden hover:border-cyan-400 transition">
                <div class="h-48 bg-slate-800 flex items-center justify-center text-6xl">
                    ⚙️
                </div>
                <div class="p-6">
                    <p class="text-cyan-400 text-sm font-bold">Processor</p>
                    <h3 class="text-xl font-black mt-2">AMD Ryzen 5 Processor</h3>
                    <p class="text-slate-400 text-sm mt-3">Reliable processor for gaming and school tasks.</p>
                    <div class="flex items-center justify-between mt-6">
                        <span class="text-2xl font-black">₱6,500</span>
                        <a href="store.php" class="bg-cyan-400 hover:bg-cyan-300 text-slate-950 px-4 py-2 rounded-xl font-bold transition">
                            View
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden hover:border-cyan-400 transition">
                <div class="h-48 bg-slate-800 flex items-center justify-center text-6xl">
                    🧠
                </div>
                <div class="p-6">
                    <p class="text-cyan-400 text-sm font-bold">RAM</p>
                    <h3 class="text-xl font-black mt-2">16GB DDR4 RAM</h3>
                    <p class="text-slate-400 text-sm mt-3">Good memory upgrade for smoother performance.</p>
                    <div class="flex items-center justify-between mt-6">
                        <span class="text-2xl font-black">₱2,100</span>
                        <a href="store.php" class="bg-cyan-400 hover:bg-cyan-300 text-slate-950 px-4 py-2 rounded-xl font-bold transition">
                            View
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden hover:border-cyan-400 transition">
                <div class="h-48 bg-slate-800 flex items-center justify-center text-6xl">
                    🎮
                </div>
                <div class="p-6">
                    <p class="text-cyan-400 text-sm font-bold">Graphics Card</p>
                    <h3 class="text-xl font-black mt-2">RTX Graphics Card</h3>
                    <p class="text-slate-400 text-sm mt-3">Great for gaming, streaming, and editing projects.</p>
                    <div class="flex items-center justify-between mt-6">
                        <span class="text-2xl font-black">₱12,500</span>
                        <a href="store.php" class="bg-cyan-400 hover:bg-cyan-300 text-slate-950 px-4 py-2 rounded-xl font-bold transition">
                            View
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- CALL TO ACTION -->
    <section class="max-w-7xl mx-auto px-6 py-16">
        <div class="bg-gradient-to-r from-cyan-400 to-blue-600 rounded-[2rem] p-10 md:p-14 text-center">
            <h2 class="text-4xl md:text-5xl font-black text-white">Ready to build your setup?</h2>
            <p class="text-cyan-50 mt-4 max-w-2xl mx-auto">
                Visit our store page and start adding PC parts to your cart.
            </p>

            <a href="store.php" class="inline-block mt-8 bg-slate-950 hover:bg-slate-900 text-white px-8 py-4 rounded-2xl font-black transition">
                Start Shopping
            </a>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="border-t border-slate-800 bg-slate-950">
        <div class="max-w-7xl mx-auto px-6 py-10 text-center">
            <h2 class="text-xl font-black text-cyan-400">AJJR PC Parts</h2>
            <p class="text-slate-400 text-sm mt-2">
                This website is for educational purposes only and is a requirement for our final project.
            </p>
            <p class="text-slate-600 text-xs mt-5">
                © 2026 AJJR PC Parts. All rights reserved.
            </p>
        </div>
    </footer>

</body>

</html>