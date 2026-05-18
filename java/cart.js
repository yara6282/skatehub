updateCartCount();
// وظيفة تحديث عداد السلة
function updateCartCount() {
    let cart = JSON.parse(localStorage.getItem('skateHub_FinalCart')) || [];
    let count = cart.reduce((sum, item) => sum + item.qty, 0);
    const cartCountElement = document.getElementById('cart-count');
    if (cartCountElement) {
        cartCountElement.innerText = count;
    }
}
document.addEventListener('DOMContentLoaded', renderCart);
function renderCart() {
    let cartData = JSON.parse(localStorage.getItem('skateHub_FinalCart')) || [];
    const list = document.getElementById('cart-items-list');

    if (cartData.length === 0) {
        list.innerHTML = `
            <div class="empty-msg">
                <h2>YOUR DECK IS EMPTY... 🛹</h2>
                <a href="shop.php">GO GRAB SOME GEAR</a>
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
    const cart = JSON.parse(localStorage.getItem('skateHub_FinalCart')) || [];

    // **تم حذف سطر الحصول على governorate من هنا.**
    // **يمكنك تعيين قيمة افتراضية إذا كنت تريد إرسال شيء إلى الخادم، أو تركه فارغًا.**
    const governorate = "N/A"; // أو يمكنك جعلها = "" إذا لم تكن تريد إرسال أي قيمة
    
    const address = document.getElementById('address-details').value.trim();

    const subtotal = parseFloat(
        document.getElementById('subtotal-val').innerText.replace('$', '')
    ) || 0;

    const shippingInput = document.querySelector('input[name="shipping"]:checked');
    const shippingFee = parseFloat(shippingInput.value) || 0;

    const shippingMethod = shippingFee === 0 ? "Free Shipping" : "Express Delivery";

    const paymentInput = document.querySelector('input[name="payway"]:checked');
    const paymentMethod = paymentInput
        .closest('.pay-option')
        .querySelector('span')
        .innerText;

    const total = parseFloat(
        document.getElementById('total-val').innerText.replace('$', '')
    ) || 0;

    if (cart.length === 0) {
        showNotification("Your cart is empty.");
        return;
    }

    // **تم حذف التحقق من governorate من هنا.**
    // if (!governorate) {
    //     showNotification("Please select your Governorate!");
    //     return;
    // }

    if (address === "") {
        showNotification("Please enter your address details.");
        return;
    }

    fetch("place_order.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            governorate: governorate, // سيتم إرسال "N/A" أو ""
            address: address,
            payment_method: paymentMethod,
            shipping_method: shippingMethod,
            subtotal: subtotal,
            shipping_fee: shippingFee,
            total: total,
            cart: cart
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            localStorage.removeItem('skateHub_FinalCart');
           window.location.href = "orders.php";
        } else {
            showNotification(data.message);
        }
    })
    .catch(error => {
        console.error(error);
        showNotification("Something went wrong while placing the order.");
    });
}
// دالة الإشعارات الجديدة بدل الـ alert
function showNotification(msg, type = 'error') {
    const container = document.getElementById('cart-status-container');
    
    // إنشاء عنصر الإشعار
    const toast = document.createElement('div');
    toast.className = `toast-msg ${type === 'error' ? 'toast-error' : 'toast-success'}`;
    toast.innerText = msg;

    // مسح الإشعارات القديمة وإضافة الجديد
    container.innerHTML = "";
    container.appendChild(toast);

    // حذف الإشعار تلقائياً بعد 3 ثوانٍ
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
document.addEventListener('DOMContentLoaded', () => {
    const footerModal = document.getElementById('footer-modal');
    const modalTitle = document.getElementById('modal-title');
    const modalBody = document.getElementById('modal-body-content');
    const modalIcon = document.getElementById('modal-icon');

    const footerContent = {
        about: {
            title: "OUR STORY",
            icon: "fa-history",
            text: "SkateHub started in 2024 with one goal: to bring the raw energy of street skating to the digital world. We aren't just a shop; we are a heartbeat for the local skate community, providing gear that survives the toughest grinds."
        },
        team: {
            title: "TEAM RIDERS",
            icon: "fa-users",
            text: "Our team consists of local legends who live on four wheels. From technical wizards to downhill daredevils, we support riders who push the limits of what's possible on concrete. Stay tuned for our upcoming street film!"
        },
        sizing: {
            title: "SIZING CHART",
            icon: "fa-ruler-combined",
            text: "Choosing the right deck is crucial.<br><br>• 7.75&quot; to 8.0&quot;: Great for technical street tricks.<br>• 8.0&quot; to 8.5&quot;: The all-rounder for park and street.<br>• 8.5&quot;+: Maximum stability for ramps and bowls."
        },
        faq: {
            title: "F.A.Q",
            icon: "fa-question-circle",
            text: "<b>How long is shipping?</b> Usually 2-4 business days.<br><br><b>Do you ship internationally?</b> Yes, we shred worldwide!<br><br><b>Can I return a used board?</b> Only if it has a manufacturing defect. Snapped boards from bad landings aren't covered!"
        },
        contact: {
            title: "CONTACT US",
            icon: "fa-envelope-open-text",
            text: "Need to talk? Hit us up on WhatsApp at +970-SKATE-HUB or visit our underground warehouse in the city. We are open from Sunset to Midnight."
        },
        privacy: {
            title: "PRIVACY",
            icon: "fa-lock",
            text: "Your data is safe with the crew. We only use your info to get your gear to your door. No snitching, no selling data, just skating."
        }
    };

    document.querySelectorAll('.footer-link').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();

            const type = link.getAttribute('data-type');
            const data = footerContent[type];

            if (!data) return;

            modalTitle.innerText = data.title;
            modalBody.innerHTML = data.text;
            modalIcon.className = `fas ${data.icon} pulse-icon`;

            footerModal.style.display = 'flex';

            gsap.fromTo(
                ".footer-modal-card",
                { scale: 0.75, opacity: 0, y: 40 },
                { scale: 1, opacity: 1, y: 0, duration: 0.55, ease: "back.out(1.7)" }
            );
        });
    });

    function closeFooterModal() {
        gsap.to(".footer-modal-card", {
            scale: 0.75,
            opacity: 0,
            y: 40,
            duration: 0.3,
            ease: "power2.in",
            onComplete: () => {
                footerModal.style.display = 'none';
            }
        });
    }

    document.querySelector('.close-footer-modal').addEventListener('click', closeFooterModal);
    document.querySelector('.close-btn-bottom').addEventListener('click', closeFooterModal);

    footerModal.addEventListener('click', (e) => {
        if (e.target === footerModal) {
            closeFooterModal();
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && footerModal.style.display === 'flex') {
            closeFooterModal();
        }
    });
});