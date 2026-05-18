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
if (typeof dbProducts !== "undefined" && dbProducts.length > 0) {
    dbProducts.forEach(p => {
        products.push(p);
    });
}
const grid = document.getElementById('products-grid');
const categoryTitle = document.getElementById('category-title');

function getSizeOptions(category) {
    if (category === "shoes") {
        return ["36", "37", "38", "39", "40", "41", "42", "43"];
    }

    if (category === "tshirts") {
        return ["S", "M", "L", "XL"];
    }

    if (category === "skates") {
        return ["7.75", "8.0", "8.25", "8.5"];
    }

    return ["Standard"];
}

function filterProducts(category) {
    document.querySelectorAll('.sticker').forEach(btn => btn.classList.remove('active'));

    if (category === 'all') categoryTitle.innerText = "FEATURED_ITEMS";
    else if (category === 'skates') categoryTitle.innerText = "BOARDS, ROLLERS & INLINES";
    else categoryTitle.innerText = category.toUpperCase();

    const filteredItems = category === 'all'
        ? products
        : products.filter(p => p.category === category);

    renderProducts(filteredItems);
}

function renderProducts(items) {
    grid.innerHTML = items.map(p => `
        <div class="product-card">
            <div class="product-tag">${p.category}</div>
            <img src="${p.img}" alt="${p.name}">
            <h3>${p.name}</h3>
            <p class="price">${p.price}</p>
            
</button>

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
    document.querySelectorAll(`#sizes-${productId} button`).forEach(b => {
        b.classList.remove("active-size");
    });

    btn.classList.add("active-size");
    btn.parentElement.dataset.selectedSize = size;

    document.getElementById(`size-error-${productId}`).innerText = "";
}

function addToCart(productId) {

    fetch("check_login.php")

    .then(response => response.json())

    .then(data => {

        // إذا مش عامل login
        if (!data.loggedIn) {

            alert("Please login first.");

            window.location.href = "login.html";

            return;
        }

        // إذا عامل login
        const product = products.find(p => p.id === productId);

        const sizeBox = document.getElementById(`sizes-${productId}`);

        const selectedSize = sizeBox.dataset.selectedSize;

        if (!selectedSize) {

            document.getElementById(`size-error-${productId}`).innerText =
                "Please choose a size.";

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

        let cart =
            JSON.parse(localStorage.getItem('skateHub_FinalCart')) || [];

        cart.push(cartItem);

        localStorage.setItem(
            'skateHub_FinalCart',
            JSON.stringify(cart)
        );

        window.location.href = "cart.html";
    });
}

window.addEventListener('DOMContentLoaded', () => {
    filterProducts('all');
});
function toggleWishlist(productId) {
    fetch("check_login.php")
        .then(response => response.json())
        .then(login => {
            if (!login.loggedIn) {
                alert("Please login first to add wishlist.");
                window.location.href = "login.html";
                return;
            }

            const product = products.find(p => p.id === productId);

            fetch("toggle_wishlist.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    product_id: product.id,
                    product_name: product.name,
                    product_img: product.img,
                    product_price: parseFloat(product.price.replace("$", ""))
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "added") {
                    alert("Added to wishlist ❤️");
                } else {
                    alert("Removed from wishlist");
                }
            });
        });
}
// =========================================
// 2. منطق مختبر التصميم (Custom Lab Logic)
// =========================================

// وظيفة تبديل المنتج الأساسي (سكيت أو تيشيرت)
window.setBase = function(type, imgSrc) {
    const area = document.getElementById('design-area');
    const baseImg = document.getElementById('base-product-img');
    const userText = document.getElementById('user-custom-text');
    
    if (!area || !baseImg) return;

    // تغيير الصورة الأساسية
    baseImg.src = imgSrc;

    // تبديل الكلاسات لتغيير وضعية الصورة والنص (من ملف CSS)
    if (type === 'deck') {
        area.classList.remove('lab-shirt');
        area.classList.add('lab-deck');
        userText.innerText = userText.innerText === "YOUR_TEXT" ? "DECK_ART" : userText.innerText;
    } else {
        area.classList.remove('lab-deck');
        area.classList.add('lab-shirt');
        userText.innerText = userText.innerText === "DECK_ART" ? "YOUR_TEXT" : userText.innerText;
    }

    // تحديث شكل الأزرار (Highlight)
    document.querySelectorAll('.base-btn').forEach(btn => btn.classList.remove('active'));
    if (event && event.currentTarget) {
        event.currentTarget.classList.add('active');
    }
};

// مراقبة إدخال النص وتحديثه فوراً على المنتج
const labTextInput = document.getElementById('lab-text-input');
if (labTextInput) {
    labTextInput.addEventListener('input', function() {
        const previewText = document.getElementById('user-custom-text');
        previewText.innerText = this.value.toUpperCase() || "YOUR_TEXT";
    });
}

// مراقبة اختيار اللون
const labColorInput = document.getElementById('lab-color-input');
if (labColorInput) {
    labColorInput.addEventListener('input', function() {
        document.getElementById('user-custom-text').style.color = this.value;
    });
}

// مراقبة رفع الصورة (Live Preview)
const labImageInput = document.getElementById('lab-image-input');
if (labImageInput) {
    labImageInput.addEventListener('change', function(evt) {
        const [file] = this.files;
        if (file) {
            const imgPreview = document.getElementById('user-uploaded-img');
            imgPreview.src = URL.createObjectURL(file);
            imgPreview.style.display = 'block';
            
            // إضافة تأثير انيميشن بسيط عند ظهور الصورة
            gsap.fromTo("#user-uploaded-img", {opacity: 0, scale: 0.5}, {opacity: 1, scale: 1, duration: 0.5});
        }
    });
}
window.addCustomToCart = function() {
    // 1. التأكد من تسجيل الدخول
    fetch("check_login.php")
    .then(response => response.json())
    .then(data => {
        if (!data.loggedIn) {
            alert("Please login first.");
            window.location.href = "login.html";
            return;
        }

        // إظهار رسالة "جاري التجهيز" لأن التصوير يأخذ ثانية
        const btn = document.querySelector('.publish-btn');
        const originalText = btn.innerText;
        btn.innerText = "CAPTURING_DESIGN...";

        // 2. استخدام html2canvas لتصوير منطقة التصميم فقط
        const designArea = document.getElementById('design-area');

        html2canvas(designArea, {
            backgroundColor: null, // يجعل الخلفية شفافة إذا أمكن
            logging: false,
            useCORS: true // للسماح بتصوير الصور من روابط خارجية
        }).then(canvas => {
            // تحويل اللوحة (Canvas) إلى صورة نصية (Base64)
            const capturedImage = canvas.toDataURL("image/png");

            // 3. جمع البيانات
            const baseType = document.querySelector('.base-btn.active').innerText;
            const customPrice = (baseType === "DECK") ? 85.00 : 35.00;

            const customCartItem = {
                id: Date.now(),
                productId: "CUSTOM-" + Date.now(),
                name: `CUSTOM_${baseType}`,
                price: customPrice,
                img: capturedImage, // هنا السحر! حفظنا صورة التصميم كاملة
                size: "CUSTOM",
                qty: 1
            };

            // 4. الحفظ في localStorage
            let cart = JSON.parse(localStorage.getItem('skateHub_FinalCart')) || [];
            cart.push(customCartItem);
            localStorage.setItem('skateHub_FinalCart', JSON.stringify(cart));

            // 5. الذهاب للسلة
            window.location.href = "cart.php";
        });
    });
};
