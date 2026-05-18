

// =========================================
// 2. قاعدة بيانات المنتجات
// =========================================
const products = [
    { id: 1, name: "Baker Skull Skateboard 8.25", category: "skates", price: "$59.99", img: "./image/BQ8D783-c.jpg" },
    { id: 2, name: "Impala Roller Skates - Cyan", category: "skates", price: "$95.00", img: "./image/BLUE_1_1200x_271dc398-dae0-4405-896f-5ea6fedef1a4_1200x1200.jpg" },
    { id: 3, name: "Roces Inline Skates M12", category: "skates", price: "$149.99", img: "./image/M12_LO_UFS_Team_101286_001_Buio__36891.webp" },
    { id: 4, name: "Primitive Dragon Ball Z Deck", category: "skates", price: "$65.00", img: "./image/Primitive-x-Dragon-Ball-Z-Goku-Energy-8.25-Skateboard-Deck-_394513-front-US.jpg" },
    { id: 5, name: "Quad Roller Skates Retro", category: "skates", price: "$85.00", img: "./image/quad_impala_vintage_stripe_1_1.jpg" },
    { id: 6, name: "Thrasher Flame Hoodie", category: "tshirts", price: "$65.00", img: "./image/FLAME-LOGO_BLACK-HOODIE-1.jpg" },
    { id: 7, name: "Santa Cruz Classic Dot Tee", category: "tshirts", price: "$28.00", img: "./image/santacruzteeclassicdotblackfront_grande.jpg" },
    { id: 8, name: "SkateHub Urban Shirt", category: "tshirts", price: "$22.00", img: "./image/i_mbi_production_blanks_mtl53ofohwq5goqjo9ke_1462829015,c_0_0_470x,s_630,q_90.jpg" },
    { id: 9, name: "Vans Old Skool Pro Black", category: "shoes", price: "$70.00", img: "./image/vans-skate-classic-old-skool-pro-shoes-blackgum-shoes-accent-group-us-8-2.jpg" },
    { id: 10, name: "Nike SB Dunk Low Pro", category: "shoes", price: "$110.00", img: "./image/NIKE+SB+DUNK+LOW+PRO.png" },
    { id: 11, name: "Converse All Star", category: "shoes", price: "$65.00", img: "./image/M9160C_M9160_A_107X1_c7bbdde3-c782-4d16-97c9-02f85cba79e5.jpg" },
    { id: 12, name: "Bones Reds Bearings", category: "accessories", price: "$18.99", img: "./image/BSACBR88.jpg" },
    { id: 13, name: "Spitfire Formula Four Wheels", category: "accessories", price: "$38.00", img: "./image/spitfire-formula-four-conical-full-skateboard-wheels-white-99d.webp" },
    { id: 14, name: "Skate Tool - Multi All-in-one", category: "accessories", price: "$12.00", img: "./image/71N2Ucc5OWL._AC_UF894,1000_QL80_.jpg" },
    { id: 15, name: "Protective Gear Set (Pads)", category: "accessories", price: "$45.00", img: "./image/71eNv9GK1ML._SL1500.jpg" }
];

// دمج منتجات قاعدة البيانات إذا وجدت
if (typeof dbProducts !== "undefined" && dbProducts.length > 0) {
    dbProducts.forEach(p => products.push(p));
}

const grid = document.getElementById('products-grid');
const categoryTitle = document.getElementById('category-title');

// =========================================
// 3. منطق عرض المنتجات والفلترة
// =========================================
function filterProducts(category) {
    document.querySelectorAll('.sticker').forEach(btn => btn.classList.remove('active'));

    if (category === 'all') categoryTitle.innerText = "FEATURED_ITEMS";
    else if (category === 'skates') categoryTitle.innerText = "BOARDS, ROLLERS & INLINES";
    else categoryTitle.innerText = category.toUpperCase();

    const filteredItems = category === 'all' ? products : products.filter(p => p.category === category);
    renderProducts(filteredItems);
}

function renderProducts(items) {
    grid.innerHTML = items.map(p => `
        <div class="product-card">
            <div class="product-tag">${p.category}</div>
            <img src="${p.img}" alt="${p.name}">
            <h3>${p.name}</h3>
            <p class="price">${p.price}</p>
            
            <div class="size-options" id="sizes-${p.id}">
                <button type="button" onclick="selectSize(${p.id}, 'S', this)">S</button>
                <button type="button" onclick="selectSize(${p.id}, 'M', this)">M</button>
                <button type="button" onclick="selectSize(${p.id}, 'L', this)">L</button>
                <button type="button" onclick="selectSize(${p.id}, 'XL', this)">XL</button>
            </div>

            <small class="size-error" id="size-error-${p.id}"></small>
            
            <div class="product-actions">
                <button class="add-btn" onclick="addToCart(${p.id})">
                    <i class="fas fa-cart-plus"></i> ADD TO CART
                </button>
                <button class="wish-btn" onclick="toggleWishlist(${p.id})">
                    <i class="fas fa-heart"></i>
                </button>
            </div>
        </div>
    `).join('');
}

function selectSize(productId, size, btn) {
    document.querySelectorAll(`#sizes-${productId} button`).forEach(b => b.classList.remove("active-size"));
    btn.classList.add("active-size");
    btn.parentElement.dataset.selectedSize = size;
    document.getElementById(`size-error-${productId}`).innerText = "";
}

// =========================================
// 4. نظام الإشعارات (Shop Toast)
// =========================================
function showShopToast(msg, type = 'blue') {
    const container = document.getElementById('shop-toast-container');
    if(!container) return;
    const toast = document.createElement('div');
    toast.className = `shop-toast ${type === 'pink' ? 'toast-pink' : 'toast-blue'}`;
    toast.innerText = msg;
    container.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 500);
    }, 3000);
}

// =========================================
// 5. وظائف السلة والأمنيات
// =========================================
function addToCart(productId) {
    fetch("check_login.php")
    .then(response => response.json())
    .then(data => {
        if (!data.loggedIn) {
            showShopToast("LOGIN REQUIRED TO GRAB GEAR!", "pink");
            return;
        }

        const product = products.find(p => p.id === productId);
        const sizeBox = document.getElementById(`sizes-${productId}`);
        const selectedSize = sizeBox.dataset.selectedSize;

        if (!selectedSize) {
            document.getElementById(`size-error-${productId}`).innerText = "Please choose a size.";
            return;
        }

        const cartItem = {
            id: Date.now(),
            productId: product.id,
            name: product.name,
            price: parseFloat(product.price.replace('$', '')),
            img: product.img,
            size: selectedSize,
            qty: 1
        };

        let cart = JSON.parse(localStorage.getItem('skateHub_FinalCart')) || [];
        cart.push(cartItem);
        localStorage.setItem('skateHub_FinalCart', JSON.stringify(cart));

        // تحديث العداد فوراً
        updateCartCount();
        showShopToast(`${product.name.toUpperCase()} ADDED!`, "blue");
    });
}

function toggleWishlist(productId) {
    fetch("check_login.php")
    .then(response => response.json())
    .then(login => {
        if (!login.loggedIn) {
            showShopToast("LOGIN TO SAVE FAVORITES!", "pink");
            return;
        }
        const product = products.find(p => p.id === productId);
        fetch("toggle_wishlist.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                product_id: product.id,
                product_name: product.name,
                product_img: product.img,
                product_price: parseFloat(product.price.replace("$", ""))
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "added") showShopToast("ADDED TO WISHLIST ❤️", "blue");
            else showShopToast("REMOVED FROM WISHLIST", "pink");
        });
    });
}

// =========================================
// 6. منطق مختبر التصميم (Custom Lab)
// =========================================
window.setBase = function(type, imgSrc) {
    const area = document.getElementById('design-area');
    const baseImg = document.getElementById('base-product-img');
    const userText = document.getElementById('user-custom-text');
    if (!area || !baseImg) return;
    baseImg.src = imgSrc;
    if (type === 'deck') {
        area.classList.remove('lab-shirt');
        area.classList.add('lab-deck');
    } else {
        area.classList.remove('lab-deck');
        area.classList.add('lab-shirt');
    }
    document.querySelectorAll('.base-btn').forEach(btn => btn.classList.remove('active'));
    if (event) event.currentTarget.classList.add('active');
};

if (document.getElementById('lab-text-input')) {
    document.getElementById('lab-text-input').addEventListener('input', function() {
        document.getElementById('user-custom-text').innerText = this.value.toUpperCase() || "YOUR_TEXT";
    });
    document.getElementById('lab-color-input').addEventListener('input', function() {
        document.getElementById('user-custom-text').style.color = this.value;
    });
    document.getElementById('lab-image-input').addEventListener('change', function(evt) {
        const [file] = this.files;
        if (file) {
            const imgPreview = document.getElementById('user-uploaded-img');
            imgPreview.src = URL.createObjectURL(file);
            imgPreview.style.display = 'block';
        }
    });
}

window.addCustomToCart = function() {
    fetch("check_login.php")
    .then(response => response.json())
    .then(data => {
        if (!data.loggedIn) {
            showShopToast("LOGIN TO DESIGN GEAR!", "pink");
            return;
        }
        showShopToast("CAPTURING DESIGN...", "blue");
        const designArea = document.getElementById('design-area');
        html2canvas(designArea, { backgroundColor: null, useCORS: true }).then(canvas => {
            const capturedImage = canvas.toDataURL("image/png");
            const baseType = document.querySelector('.base-btn.active').innerText;
            const customPrice = (baseType === "DECK") ? 85.00 : 35.00;

            const customCartItem = {
                id: Date.now(),
                productId: "CUSTOM-" + Date.now(),
                name: `CUSTOM_${baseType}`,
                price: customPrice,
                img: capturedImage,
                size: "CUSTOM",
                qty: 1
            };

            let cart = JSON.parse(localStorage.getItem('skateHub_FinalCart')) || [];
            cart.push(customCartItem);
            localStorage.setItem('skateHub_FinalCart', JSON.stringify(cart));
            
            // تحديث العداد فوراً
            updateCartCount();
            showShopToast("CUSTOM GEAR ADDED! 🛹", "blue");
        });
    });
};

window.addEventListener('DOMContentLoaded', () => {
    filterProducts('all');
});
document.addEventListener('DOMContentLoaded', () => {
    const footerModal = document.getElementById('footer-modal');
    const modalTitle = document.getElementById('modal-title');
    const modalBody = document.getElementById('modal-body-content');
    const modalIcon = document.getElementById('modal-icon');
    const closeX = document.querySelector('.close-footer-modal');
    const closeBottom = document.querySelector('.close-btn-bottom');

    if (!footerModal || !modalTitle || !modalBody || !modalIcon) return;

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

            const type = link.dataset.type;
            const data = footerContent[type];

            if (!data) return;

            modalTitle.innerText = data.title;
            modalBody.innerHTML = data.text;
            modalIcon.className = `fas ${data.icon} pulse-icon`;

            footerModal.style.display = 'flex';

            if (typeof gsap !== "undefined") {
                gsap.fromTo(
                    ".footer-modal-card",
                    { scale: 0.75, opacity: 0, y: 40 },
                    { scale: 1, opacity: 1, y: 0, duration: 0.55, ease: "back.out(1.7)" }
                );
            }
        });
    });

    function closeFooterModal() {
        if (typeof gsap !== "undefined") {
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
        } else {
            footerModal.style.display = 'none';
        }
    }

    if (closeX) closeX.addEventListener('click', closeFooterModal);
    if (closeBottom) closeBottom.addEventListener('click', closeFooterModal);

    footerModal.addEventListener('click', (e) => {
        if (e.target === footerModal) closeFooterModal();
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && footerModal.style.display === 'flex') {
            closeFooterModal();
        }
    });
});