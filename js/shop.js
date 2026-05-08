/**
 * SkateHub - Official Shop Script
 * المميزات: سلايدر سينمائي، فلترة شاملة للسكيت، نظام سلة المشتريات
 */

// 1. قاعدة بيانات المنتجات (تضم كل أنواع السكيت)
const products = [
    // --- فئة SKATES (تشمل بورد، رولر، إنلاين) ---
    { id: 1, name: "Baker Skull Skateboard 8.25", category: "skates", price: "$59.99", img: "./image/BQ8D783-c.jpg" },
    { id: 2, name: "Impala Roller Skates - Cyan", category: "skates", price: "$95.00", img: "./image/BLUE_1_1200x_271dc398-dae0-4405-896f-5ea6fedef1a4_1200x1200.jpg" },
    { id: 3, name: "Roces Inline Skates M12", category: "skates", price: "$149.99", img: "./image/M12_LO_UFS_Team_101286_001_Buio__36891.webp" },
    { id: 4, name: "Primitive Dragon Ball Z Deck", category: "skates", price: "$65.00", img: "./image/Primitive-x-Dragon-Ball-Z-Goku-Energy-8.25-Skateboard-Deck-_394513-front-US.jpg" },
    { id: 5, name: "Quad Roller Skates Retro", category: "skates", price: "$85.00", img: "./image/quad_impala_vintage_stripe_1_1.jpg" },

    // --- فئة T-SHIRTS ---
    { id: 6, name: "Thrasher Flame Hoodie", category: "tshirts", price: "$65.00", img: "./image/FLAME-LOGO_BLACK-HOODIE-1.jpg" },
    { id: 7, name: "Santa Cruz Classic Dot Tee", category: "tshirts", price: "$28.00", img: "./image/santacruzteeclassicdotblackfront_grande.jpg" },
    { id: 8, name: "SkateHub Urban Shirt", category: "tshirts", price: "$22.00", img: "./image/i_mbi_production_blanks_mtl53ofohwq5goqjo9ke_1462829015,c_0_0_470x,s_630,q_90.jpg" },

    // --- فئة SHOES ---
    { id: 9, name: "Vans Old Skool Pro Black", category: "shoes", price: "$70.00", img: "./image/vans-skate-classic-old-skool-pro-shoes-blackgum-shoes-accent-group-us-8-2.jpg" },
    { id: 10, name: "Nike SB Dunk Low Pro", category: "shoes", price: "$110.00", img: "./image/NIKE+SB+DUNK+LOW+PRO.png" },
    { id: 11, name: "Converse All Star", category: "shoes", price: "$65.00", img: "./image/M9160C_M9160_A_107X1_c7bbdde3-c782-4d16-97c9-02f85cba79e5.jpg" },

    // --- فئة ACCESSORIES ---
    { id: 12, name: "Bones Reds Bearings", category: "accessories", price: "$18.99", img: "./image/BSACBR88.jpg" },
    { id: 13, name: "Spitfire Formula Four Wheels", category: "accessories", price: "$38.00", img: "./image/spitfire-formula-four-conical-full-skateboard-wheels-white-99d.webp" },
    { id: 14, name: "Skate Tool - Multi All-in-one", category: "accessories", price: "$12.00", img: "./image/71N2Ucc5OWL._AC_UF894,1000_QL80_.jpg" },
    { id: 15, name: "Protective Gear Set (Pads)", category: "accessories", price: "$45.00", img: "./image/71eNv9GK1ML._SL1500.jpg" },
];

// 2. منطق السلايدر التلقائي (الصور الثلاث)
let currentSlideIndex = 0;
const slides = document.querySelectorAll('.slide');

function rotateHeroSlides() {
    if (slides.length === 0) return;

    // إخفاء الصورة الحالية
    slides[currentSlideIndex].classList.remove('active');
    
    // الانتقال للصورة التالية
    currentSlideIndex = (currentSlideIndex + 1) % slides.length;
    
    // إظهار الصورة الجديدة
    slides[currentSlideIndex].classList.add('active');
}

// تبديل الصور كل 5 ثوانٍ
if (slides.length > 0) {
    setInterval(rotateHeroSlides, 5000);
}

// 3. منطق عرض وفلترة المنتجات
const grid = document.getElementById('products-grid');
const categoryTitle = document.getElementById('category-title');

function filterProducts(category) {
    // تحديث شكل أزرار الملصقات (Stickers)
    document.querySelectorAll('.sticker').forEach(btn => {
        btn.classList.remove('active');
        // نطابق أول 3 حروف لضمان دقة الاختيار (skate, tshir, shoe, acces)
        const btnText = btn.innerText.toLowerCase();
        if (btnText.includes(category.substring(0, 3))) {
            btn.classList.add('active');
        }
    });

    // تحديث العنوان بناءً على القسم
    if (category === 'all') {
        categoryTitle.innerText = "FEATURED_ITEMS";
    } else if (category === 'skates') {
        categoryTitle.innerText = "BOARDS, ROLLERS & INLINES";
    } else {
        categoryTitle.innerText = category.toUpperCase();
    }

    // تصفية المصفوفة
    const filteredItems = category === 'all' ? products : products.filter(p => p.category === category);

    // بناء شبكة المنتجات مع أنيميشن بسيط
    renderProducts(filteredItems);
}

function renderProducts(items) {
    grid.style.opacity = '0'; // إخفاء الشبكة لبدء الانيميشن
    
    setTimeout(() => {
        grid.innerHTML = items.map(p => `
            <div class="product-card">
                <div class="product-tag">${p.category}</div>
                <img src="${p.img}" alt="${p.name}" loading="lazy">
                <h3>${p.name}</h3>
                <p class="price">${p.price}</p>
                <button class="add-btn" onclick="addToCart()">
                    <i class="fas fa-cart-plus"></i> ADD TO CART
                </button>
            </div>
        `).join('');
        
        grid.style.opacity = '1'; // إظهار الشبكة
    }, 250);
}

// 4. منطق سلة المشتريات
let cartCountValue = 0;
const cartCounterUI = document.getElementById('cart-count');

function addToCart() {
    cartCountValue++;
    if (cartCounterUI) {
        cartCounterUI.innerText = cartCountValue;
        
        // تأثير حركة خفيف لأيقونة السلة
        cartCounterUI.parentElement.classList.add('bump');
        setTimeout(() => {
            cartCounterUI.parentElement.classList.remove('bump');
        }, 300);
    }
}

// 5. التشغيل عند التحميل
window.addEventListener('DOMContentLoaded', () => {
    // عرض كل المنتجات عند فتح الصفحة
    filterProducts('all');
    
    // التأكد من تفعيل أول صورة في السلايدر
    if (slides.length > 0) {
        slides[0].classList.add('active');
    }
});