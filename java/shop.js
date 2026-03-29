let cart = JSON.parse(localStorage.getItem('cart')) || [];
let favs = JSON.parse(localStorage.getItem('favs')) || [];

function updateBadges() {
    if(document.getElementById('cart-count')) document.getElementById('cart-count').innerText = cart.length;
    if(document.getElementById('fav-count')) document.getElementById('fav-count').innerText = favs.length;
}

function addToCart(name, price, img) {
    cart.push({name, price, img});
    localStorage.setItem('cart', JSON.stringify(cart));
    updateBadges();
    showToast(`🛒 ${name} added to cart!`);
}

function addToFav(name, price, img) {
    if(!favs.some(i => i.name === name)) {
        favs.push({name, price, img});
        localStorage.setItem('favs', JSON.stringify(favs));
        updateBadges();
        showToast(`⭐ ${name} added to favorites!`);
    }
}

function showToast(msg) {
    alert(msg); // يمكن استبدالها بـ Toast UI لاحقاً
}

window.onload = updateBadges;