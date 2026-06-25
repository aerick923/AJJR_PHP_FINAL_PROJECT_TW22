let cart = JSON.parse(localStorage.getItem("cart")) || [];

function saveCart() {
    localStorage.setItem("cart", JSON.stringify(cart));
}

function addToCart(name, price, image) {
    let item = cart.find(i => i.name === name);

    if (item) {
        item.qty += 1;
    } else {
        cart.push({ name, price, image, qty: 1 });
    }

    saveCart();
    updateCartCount();
}

function removeItem(name) {
    cart = cart.filter(i => i.name !== name);
    saveCart();
    renderCart();
    updateCartCount();
}

function changeQty(name, amount) {
    let item = cart.find(i => i.name === name);
    if (!item) return;

    item.qty += amount;

    if (item.qty <= 0) {
        removeItem(name);
    }

    saveCart();
    renderCart();
    updateCartCount();
}

function getTotal() {
    return cart.reduce((sum, i) => sum + i.price * i.qty, 0);
}

function updateCartCount() {
    let count = cart.reduce((sum, i) => sum + i.qty, 0);
    let el = document.getElementById("cartCount");
    if (el) el.innerText = count;
}