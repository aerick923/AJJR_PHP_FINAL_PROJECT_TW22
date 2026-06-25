<!DOCTYPE html>
<html>

<head>
    <title>Shop</title>
    <link href="./output.css" rel="stylesheet">
    <script src="./cart.js" defer></script>
</head>

<body class="bg-slate-950 text-white" onload="updateCartCount()">

<nav class="p-5 flex justify-between border-b border-white/10">
    <h1 class="text-blue-400 font-bold text-2xl">PC Essentials</h1>

    <div class="flex gap-6">
        <a href="index.html">Home</a>
        <a href="products.html">Shop</a>
        <a href="cart.html">Cart (<span id="cartCount">0</span>)</a>
    </div>
</nav>

<section class="max-w-7xl mx-auto p-6 grid md:grid-cols-3 gap-8">

<!-- GPU -->
<div class="bg-white/5 p-5 rounded-2xl">
    <img class="rounded-xl"
        src="https://images.unsplash.com/photo-1591799264318-7e6ef8ddb7ea?auto=format&fit=crop&w=800&q=80">

    <h2 class="font-bold mt-3">RTX 4070 GPU</h2>
    <p class="text-blue-400">₱35,000</p>
    <button onclick="addToCart('RTX 4070 GPU', 35000)"
        class="bg-blue-600 w-full mt-3 py-2 rounded-xl">
        Add
    </button>
</div>

<!-- CPU -->
<div class="bg-white/5 p-5 rounded-2xl">
    <img class="rounded-xl"
        src="https://images.unsplash.com/photo-1616587226960-4a03badbe8bf?auto=format&fit=crop&w=800&q=80">

    <h2 class="font-bold mt-3">Ryzen 7 CPU</h2>
    <p class="text-blue-400">₱18,500</p>
    <button onclick="addToCart('Ryzen 7 CPU', 18500)"
        class="bg-blue-600 w-full mt-3 py-2 rounded-xl">
        Add
    </button>
</div>

<!-- RAM -->
<div class="bg-white/5 p-5 rounded-2xl">
    <img class="rounded-xl"
        src="https://images.unsplash.com/photo-1587202372775-e229f172b9d7?auto=format&fit=crop&w=800&q=80">

    <h2 class="font-bold mt-3">32GB DDR5 RAM</h2>
    <p class="text-blue-400">₱8,500</p>
    <button onclick="addToCart('32GB DDR5 RAM', 8500)"
        class="bg-blue-600 w-full mt-3 py-2 rounded-xl">
        Add
    </button>
</div>

<!-- SSD -->
<div class="bg-white/5 p-5 rounded-2xl">
    <img class="rounded-xl"
        src="https://images.unsplash.com/photo-1587829741301-dc798b83add3?auto=format&fit=crop&w=800&q=80">

    <h2 class="font-bold mt-3">1TB NVMe SSD</h2>
    <p class="text-blue-400">₱5,500</p>
    <button onclick="addToCart('1TB NVMe SSD', 5500)"
        class="bg-blue-600 w-full mt-3 py-2 rounded-xl">
        Add
    </button>
</div>

<!-- PSU -->
<div class="bg-white/5 p-5 rounded-2xl">
    <img class="rounded-xl"
        src="https://images.unsplash.com/photo-1612810806695-30f7b2a5d4f3?auto=format&fit=crop&w=800&q=80">

    <h2 class="font-bold mt-3">750W PSU</h2>
    <p class="text-blue-400">₱4,200</p>
    <button onclick="addToCart('750W PSU', 4200)"
        class="bg-blue-600 w-full mt-3 py-2 rounded-xl">
        Add
    </button>
</div>

</section>

</body>
</html>