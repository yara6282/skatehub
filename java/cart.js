document.addEventListener('DOMContentLoaded', renderCart);

function renderCart() {
    let cartData = JSON.parse(localStorage.getItem('skateHub_FinalCart')) || [];
    const list = document.getElementById('cart-items-list');
    
    if (cartData.length === 0) {
        // توليد رسالة السلة الفارغة مطابقة للصورة
        list.innerHTML = `
            <div class="empty-msg">
                <h2>YOUR DECK IS EMPTY... 🛹</h2>
                <a href="shop.html">GO GRAB SOME GEAR</a>
            </div>`;
        
        // تصفير الأرقام في البوكس اليمين (بدون تعديل ستايل البوكس)
        if(document.getElementById('subtotal-val')) document.getElementById('subtotal-val').innerText = "$0.00";
        if(document.getElementById('total-val')) document.getElementById('total-val').innerText = "$0.00";
        return;
    }

    // إذا في منتجات، الكود بكمل عرضه هون...
}

function updateTotal() {
    const subText = document.getElementById('subtotal-val').innerText;
    const subtotal = parseFloat(subText.replace('$', '')) || 0;
    
    const shipping = document.querySelector('input[name="shipping"]:checked').value;
    const total = subtotal + parseFloat(shipping);
    
    document.getElementById('total-val').innerText = `$${total.toFixed(2)}`;
    
    // تحديث شريط التقدم (مثلاً هدف الشحن المجاني 200$)
    const goal = 200;
    const progress = Math.min((subtotal / goal) * 100, 100);
    document.getElementById('p-bar').style.width = progress + '%';
    document.getElementById('s-icon').style.left = progress + '%';
}

window.removeItem = (index) => {
    let cart = JSON.parse(localStorage.getItem('skateHub_FinalCart'));
    cart.splice(index, 1);
    localStorage.setItem('skateHub_FinalCart', JSON.stringify(cart));
    renderCart();
};

function processOrder() {
    const gov = document.getElementById('governorate').value;
    if(!gov) { alert("Please select your Governorat!"); return; }
    alert("ORDER RECEIVED! Shred on! 🛹🔥");
}
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