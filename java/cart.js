document.addEventListener('DOMContentLoaded', renderCart);

function renderCart() {
    let cartData = JSON.parse(localStorage.getItem('skateHub_FinalCart')) || [];
    const list = document.getElementById('cart-items-list');

    if (cartData.length === 0) {
        list.innerHTML = `
            <div class="empty-msg">
                <h2>YOUR DECK IS EMPTY... 🛹</h2>
                <a href="shop.html">GO GRAB SOME GEAR</a>
            </div>`;

        document.getElementById('subtotal-val').innerText = "$0.00";
        document.getElementById('total-val').innerText = "$0.00";
        updateShippingProgress(0);
        return;
    }

    let subtotal = 0;
    list.innerHTML = "";

    cartData.forEach((item, index) => {
        let itemTotal = item.price * item.qty;
        subtotal += itemTotal;

        list.innerHTML += `
            <div class="cart-product">
                <img src="${item.img}" alt="${item.name}" class="cart-product-img">

                <div class="cart-product-info">
                    <h2>${item.name}</h2>
                    <p>Size: <span>${item.size}</span></p>
                    <p>Price: <span>$${item.price.toFixed(2)}</span></p>

                    <div class="qty-box">
                        <button onclick="changeQty(${index}, -1)">-</button>
                        <span>${item.qty}</span>
                        <button onclick="changeQty(${index}, 1)">+</button>
                    </div>

                    <p>Total: $${itemTotal.toFixed(2)}</p>

                    <button class="remove-btn" onclick="removeItem(${index})">
                        REMOVE
                    </button>
                </div>
            </div>
        `;
    });

    list.innerHTML += `
        <div class="continue-shopping">
            <a href="shop.html">GO GRAB SOME GEAR</a>
        </div>
    `;

    document.getElementById('subtotal-val').innerText = `$${subtotal.toFixed(2)}`;
    updateTotal();
}

function changeQty(index, amount) {
    let cart = JSON.parse(localStorage.getItem('skateHub_FinalCart')) || [];

    cart[index].qty += amount;

    if (cart[index].qty <= 0) {
        cart.splice(index, 1);
    }

    localStorage.setItem('skateHub_FinalCart', JSON.stringify(cart));
    renderCart();
}

function removeItem(index) {
    let cart = JSON.parse(localStorage.getItem('skateHub_FinalCart')) || [];
    cart.splice(index, 1);
    localStorage.setItem('skateHub_FinalCart', JSON.stringify(cart));
    renderCart();
}

function updateTotal() {
    const subtotalText = document.getElementById('subtotal-val').innerText;
    const subtotal = parseFloat(subtotalText.replace('$', '')) || 0;

    const shipping = parseFloat(document.querySelector('input[name="shipping"]:checked').value);
    const total = subtotal + shipping;

    document.getElementById('total-val').innerText = `$${total.toFixed(2)}`;

    updateShippingProgress(subtotal);
}

function updateShippingProgress(subtotal) {
    const goal = 500;
    const progress = Math.min((subtotal / goal) * 100, 100);

    document.getElementById('p-bar').style.width = progress + '%';
    document.getElementById('s-icon').style.left = progress + '%';

    const remaining = goal - subtotal;

    if (remaining <= 0) {
        document.getElementById('shipping-text').innerHTML =
            `<span class="neon-green">FREE SHIPPING UNLOCKED!</span>`;
    } else {
        document.getElementById('shipping-text').innerHTML =
            `Add <span class="neon-green">$${remaining.toFixed(2)}</span> more for FREE SHIPPING`;
    }
}

function processOrder() {
    const gov = document.getElementById('governorate').value;

    if (!gov) {
        alert("Please select your Governorate!");
        return;
    }

    alert("ORDER RECEIVED! Shred on! 🛹🔥");
}