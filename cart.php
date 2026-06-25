<!DOCTYPE html>
<html>

<head>
    <title>Cart</title>
    <link href="./output.css" rel="stylesheet">
    <script src="./cart.js" defer></script>
</head>

<body class="bg-slate-950 text-white" onload="renderCart(); updateCartCount()">

<nav class="p-5 flex justify-between border-b border-white/10">
    <h1 class="text-blue-400 font-bold text-2xl">Your Cart</h1>
    <a href="products.html">← Back</a>
</nav>

<section class="max-w-3xl mx-auto p-6">

<div id="cartItems" class="space-y-4"></div>

<h2 class="text-2xl font-bold mt-8">
    Total: ₱<span id="total">0</span>
</h2>

</section>

<script>
function renderCart() {
    let box = document.getElementById("cartItems");
    box.innerHTML = "";

    cart.forEach(item => {
        box.innerHTML += `
        <div class="bg-white/5 p-4 rounded-xl">
            <h3 class="font-bold">${item.name}</h3>
            <p class="text-blue-400">₱${item.price}</p>

            <div class="flex gap-3 mt-2 items-center">
                <button onclick="changeQty('${item.name}', -1)" class="bg-red-600 px-3 rounded">-</button>
                <span>${item.qty}</span>
                <button onclick="changeQty('${item.name}', 1)" class="bg-green-600 px-3 rounded">+</button>

                <button onclick="removeItem('${item.name}')" class="ml-auto text-red-400">
                    Remove
                </button>
            </div>
        </div>
        `;
    });

    document.getElementById("total").innerText = getTotal();
}

renderCart();
updateCartCount();
</script>

</body>
</html>