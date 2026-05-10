document.addEventListener('DOMContentLoaded', () => {
    // تشغيل أنيميشن الظهور
    AOS.init({ duration: 1000, once: true });

    // --- حركة الصور والأيقونات الطائرة (Mouse Parallax) ---
    const floatObjs = document.querySelectorAll('.float-obj');
    
    document.addEventListener('mousemove', (e) => {
        let x = (window.innerWidth - e.pageX * 2) / 40;
        let y = (window.innerHeight - e.pageY * 2) / 40;

        floatObjs.forEach(obj => {
            let speed = obj.getAttribute('data-speed');
            // حركة دوران خفيفة مع الإزاحة لزيادة الواقعية
            obj.style.transform = `translateX(${x * speed / 5}px) translateY(${y * speed / 5}px) rotate(${x}deg)`;
        });
    });

    // --- نظام الفلتر الذكي للفيديوهات ---
    const filterBtns = document.querySelectorAll('.filter-btn');
    const videoCards = document.querySelectorAll('.video-card');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // تحديث الزر النشط
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const filterValue = btn.getAttribute('data-filter');

            videoCards.forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'scale(0.8)';
                
                setTimeout(() => {
                    if (filterValue === 'all' || card.classList.contains(filterValue)) {
                        card.style.display = 'block';
                        setTimeout(() => {
                            card.style.opacity = '1';
                            card.style.transform = 'scale(1)';
                        }, 50);
                    } else {
                        card.style.display = 'none';
                    }
                }, 300);
            });
        });
    });
});
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