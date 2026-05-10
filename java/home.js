
document.addEventListener('DOMContentLoaded', () => {
    const footerModal = document.getElementById('footer-modal');
    const modalTitle = document.getElementById('modal-title');
    const modalBody = document.getElementById('modal-body-content');
    const modalIcon = document.getElementById('modal-icon');
    
    // بيانات المحتوى (القصة، التيم، الخ...)
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
            text: "Choosing the right deck is crucial. <br>• 7.75\" to 8.0\": Great for technical street tricks.<br>• 8.0\" to 8.5\": The all-rounder for park and street.<br>• 8.5\"+: Maximum stability for ramps and bowls."
        },
        faq: {
            title: "F.A.Q",
            icon: "fa-question-circle",
            text: "<b>How long is shipping?</b> Usually 2-4 business days. <br><b>Do you ship internationally?</b> Yes, we shred worldwide! <br><b>Can I return a used board?</b> Only if it has a manufacturing defect. Snapped boards from bad landings aren't covered!"
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

    // فتح المودال عند الضغط
    document.querySelectorAll('.footer-link').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const type = link.getAttribute('data-type');
            const data = footerContent[type];

            modalTitle.innerText = data.title;
            modalBody.innerHTML = data.text;
            modalIcon.className = `fas ${data.icon} pulse-icon`;

            footerModal.style.display = 'flex';
            gsap.fromTo(".footer-modal-card", 
                { scale: 0.7, opacity: 0 }, 
                { scale: 1, opacity: 1, duration: 0.5, ease: "back.out(1.7)" }
            );
        });
    });

    // إغلاق المودال
    const closeFooter = () => {
        gsap.to(".footer-modal-card", { scale: 0.7, opacity: 0, duration: 0.3, onComplete: () => {
            footerModal.style.display = 'none';
        }});
    };

    document.querySelector('.close-footer-modal').addEventListener('click', closeFooter);
    document.querySelector('.close-btn-bottom').addEventListener('click', closeFooter);
});
// 1. إدارة الذاكرة والعداد (Cart Logic)
function updateCartCount() {
    // توحيد الاسم لـ skateHub_FinalCart لضمان عمل صفحة الكارت
    let cart = JSON.parse(localStorage.getItem('skateHub_FinalCart')) || [];
    let count = cart.reduce((sum, item) => sum + item.qty, 0);
    const cartCountElement = document.getElementById('cart-count');
    if (cartCountElement) {
        cartCountElement.innerText = count;
    }
}

// تنفيذ التحديث عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', updateCartCount);

// دالة الإضافة للسلة (ستايل الشوب)
window.addToCartFinal = function() {
    const productName = document.getElementById('modal-title').innerText;
    const productPrice = document.getElementById('modal-price').innerText;
    const productImg = document.getElementById('modal-img').src;
    const selectedSize = document.querySelector('.chip.active')?.innerText || "M";
    const quantity = parseInt(document.getElementById('qty-val').innerText) || 1;

    const product = {
        id: Date.now(),
        name: productName,
        price: parseFloat(productPrice.replace('$', '')),
        img: productImg,
        size: selectedSize,
        qty: quantity
    };

    let cart = JSON.parse(localStorage.getItem('skateHub_FinalCart')) || [];
    cart.push(product);
    localStorage.setItem('skateHub_FinalCart', JSON.stringify(cart));

    updateCartCount();
    
    // إغلاق مودال الشوب بأنيميشن
    closeModal(); 
    
    // إظهار توست (إشعار) بدل الـ Alert البشع
    showShopToast("Added to Deck! 🛹");
};

// دالة إظهار إشعار احترافي
function showShopToast(msg) {
    const toast = document.getElementById('toast-notification');
    if(toast) {
        toast.innerText = msg;
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 3000);
    }
}

// 2. إدارة محتوى الفوتر (Footer Modal Logic)
document.addEventListener('DOMContentLoaded', () => {
    const footerModal = document.getElementById('footer-modal');
    const modalTitle = document.getElementById('footer-modal-title');
    const modalBody = document.getElementById('footer-modal-body-content');
    const modalIcon = document.getElementById('footer-modal-icon');
    
    const footerContent = {
        about: {
            title: "OUR STORY",
            icon: "fa-history",
            text: "SkateHub started in 2024 with one goal: to bring the raw energy of street skating to the digital world. We provide gear that survives the toughest grinds."
        },
        team: {
            title: "TEAM RIDERS",
            icon: "fa-users",
            text: "Our team consists of local legends who push the limits of what's possible on concrete. Stay tuned for our upcoming street film!"
        },
        sizing: {
            title: "SIZING CHART",
            icon: "fa-ruler-combined",
            text: "<b>Deck Sizing Guide:</b><br>• 7.75\" to 8.0\": Street tricks.<br>• 8.0\" to 8.5\": All-around.<br>• 8.5\"+: Stability for bowls."
        },
        faq: {
            title: "F.A.Q",
            icon: "fa-question-circle",
            text: "<b>Shipping:</b> 2-4 days. <br><b>Returns:</b> 14-day policy for unused gear."
        },
        contact: {
            title: "CONTACT US",
            icon: "fa-envelope-open-text",
            text: "WhatsApp: +970-SKATE-HUB <br>Email: crew@skatehub.ps"
        },
        privacy: {
            title: "PRIVACY",
            icon: "fa-lock",
            text: "We keep your data locked down. Only used for shipping your shred-gear."
        }
    };

    // فتح مودال الفوتر بأنيميشن الشوب (GSAP)
    document.querySelectorAll('.footer-link').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const type = link.getAttribute('data-type');
            const data = footerContent[type];

            modalTitle.innerText = data.title;
            modalBody.innerHTML = data.text;
            modalIcon.className = `fas ${data.icon} pulse-icon`;

            footerModal.style.display = 'flex';
            
            // نفس أنيميشن الشوب لتوحيد التجربة
            gsap.fromTo(".footer-modal-card", 
                { scale: 0.5, opacity: 0, rotationX: 45 }, 
                { scale: 1, opacity: 1, rotationX: 0, duration: 0.6, ease: "back.out(1.7)" }
            );
        });
    });

    // إغلاق مودال الفوتر
    const closeFooter = () => {
        gsap.to(".footer-modal-card", { scale: 0.5, opacity: 0, duration: 0.3, onComplete: () => {
            footerModal.style.display = 'none';
        }});
    };

    if(document.querySelector('.close-footer-modal')) {
        document.querySelector('.close-footer-modal').addEventListener('click', closeFooter);
        document.querySelector('.close-btn-bottom').addEventListener('click', closeFooter);
    }
});