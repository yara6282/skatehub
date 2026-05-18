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
// وظيفة تحديث عداد السلة
function updateCartCount() {
    let cart = JSON.parse(localStorage.getItem('skateHub_FinalCart')) || [];
    let count = cart.reduce((sum, item) => sum + item.qty, 0);
    const cartCountElement = document.getElementById('cart-count');
    if (cartCountElement) {
        cartCountElement.innerText = count;
    }
}