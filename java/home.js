document.addEventListener('DOMContentLoaded', () => {
    // 1. تحديث عداد السلة عند التحميل
    updateCartCount();

    // 2. منطق المودال الخاص بالفوتر
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
            text: "Our team consists of local legends who push the limits of what's possible on concrete."
        },
        sizing: {
            title: "SIZING CHART",
            icon: "fa-ruler-combined",
            text: "Deck Sizing Guide:<br>• 7.75\" to 8.0\": Street tricks.<br>• 8.0\" to 8.5\": All-around."
        },
        faq: {
            title: "F.A.Q",
            icon: "fa-question-circle",
            text: "Shipping takes 2-4 days. Returns available for 14 days on unused gear."
        },
        contact: {
            title: "CONTACT US",
            icon: "fa-envelope-open-text",
            text: "WhatsApp: +970-SKATE-HUB <br>Email: crew@skatehub.ps"
        },
        privacy: {
            title: "PRIVACY",
            icon: "fa-lock",
            text: "We keep your data locked down. No snitching, just skating."
        }
    };

    // فتح مودال الفوتر
    document.querySelectorAll('.footer-link').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const type = link.getAttribute('data-type');
            if (footerContent[type]) {
                const data = footerContent[type];
                modalTitle.innerText = data.title;
                modalBody.innerHTML = data.text;
                modalIcon.className = `fas ${data.icon} pulse-icon`;

                footerModal.style.display = 'flex';
                gsap.fromTo(".footer-modal-card", 
                    { scale: 0.5, opacity: 0, rotationX: 45 }, 
                    { scale: 1, opacity: 1, rotationX: 0, duration: 0.6, ease: "back.out(1.7)" }
                );
            }
        });
    });

    // إغلاق المودال
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

// وظيفة تحديث عداد السلة
function updateCartCount() {
    let cart = JSON.parse(localStorage.getItem('skateHub_FinalCart')) || [];
    let count = cart.reduce((sum, item) => sum + item.qty, 0);
    const cartCountElement = document.getElementById('cart-count');
    if (cartCountElement) {
        cartCountElement.innerText = count;
    }
}