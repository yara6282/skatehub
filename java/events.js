// تسجيل مكتبة ScrollTrigger الخاصة بـ GSAP
gsap.registerPlugin(ScrollTrigger);

document.addEventListener('DOMContentLoaded', () => {

    // 1. Custom Cursor Logic (مؤشر الماوس الاحترافي)
    const cursor = document.querySelector('.cursor');
    const follower = document.querySelector('.cursor-follower');
    const interactables = document.querySelectorAll('a, button, .skate-panel, input, select');
    // --- Purchase Modal Logic ---
    const ticketModal = document.getElementById('ticket-modal');
    const modalCard = document.querySelector('.modal-card');
    const buyBtns = document.querySelectorAll('.btn-card');
    const closeBtn = document.querySelector('.close-modal');

    // وظيفة فتح النافذة
    buyBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation(); // منع انتقال الحدث للـ Panel
            
            // جلب اسم الحدث من الكرت
            const eventTitle = btn.parentElement.querySelector('h3').innerText;
            document.getElementById('modal-event-name').innerText = eventTitle;

            // إظهار النافذة مع أنميشن GSAP
            ticketModal.style.display = 'flex';
            gsap.fromTo(modalCard, 
                { scale: 0.5, opacity: 0, rotationX: 45 }, 
                { scale: 1, opacity: 1, rotationX: 0, duration: 0.6, ease: "back.out(1.7)" }
            );
        });
    });

    // وظيفة الإغلاق
    const closeModalFunc = () => {
        gsap.to(modalCard, { scale: 0.8, opacity: 0, duration: 0.3, ease: "power2.in", onComplete: () => {
            ticketModal.style.display = 'none';
        }});
    };

    closeBtn.addEventListener('click', closeModalFunc);
    
    // إغلاق عند الضغط خارج المربع
    ticketModal.addEventListener('click', (e) => {
        if (e.target === ticketModal) closeModalFunc();
    });

    // تفاعل زر Pay Now
    const payBtn = document.querySelector('.pay-now-btn');
    payBtn.addEventListener('click', () => {
        payBtn.innerHTML = "PROCESSING...";
        setTimeout(() => {
            payBtn.innerHTML = "SUCCESS! 🛹";
            payBtn.style.background = "var(--neon-blue)";
            setTimeout(closeModalFunc, 1500);
        }, 2000);
    });

    document.addEventListener('mousemove', (e) => {
        gsap.to(cursor, { x: e.clientX, y: e.clientY, duration: 0.1 });
        gsap.to(follower, { x: e.clientX, y: e.clientY, duration: 0.3 });
    });

    // تكبير الماوس عند المرور على أزرار
    interactables.forEach(item => {
        item.addEventListener('mouseenter', () => {
            cursor.classList.add('active');
            follower.style.borderColor = "var(--neon-blue)";
        });
        item.addEventListener('mouseleave', () => {
            cursor.classList.remove('active');
            follower.style.borderColor = "var(--neon-pink)";
        });
    });

    // 2. GSAP Entrance Animations (دخول ناري للواجهة)
    const tl = gsap.timeline();

    // نزول شريط القائمة العلوية
    tl.from(".navbar", { y: -100, opacity: 0, duration: 1, ease: "power4.out" })
      // دخول النصوص الرئيسية بشكل متتالي
      .from(".gsap-reveal", { y: 50, opacity: 0, duration: 0.8, stagger: 0.2, ease: "back.out(1.7)" }, "-=0.5")
      // ظهور الصور الطائرة
      .from(".float-item", { scale: 0, rotation: 45, opacity: 0, duration: 1, stagger: 0.2, ease: "elastic.out(1, 0.5)" }, "-=1");

    // 3. GSAP Scroll Animations (حركة عند النزول للأسفل)
    gsap.utils.toArray('.gsap-scroll').forEach(element => {
        gsap.from(element, {
            scrollTrigger: {
                trigger: element,
                start: "top 85%", // يبدأ الأنميشن لما يوصل العنصر لـ 85% من الشاشة
                toggleActions: "play none none reverse"
            },
            y: 100,
            opacity: 0,
            duration: 1,
            ease: "power3.out"
        });
    });

    // 4. Parallax Floating Elements (تأثير حركة الماوس)
    const items = document.querySelectorAll('.float-item');
    document.addEventListener('mousemove', (e) => {
        const mouseX = (e.clientX - window.innerWidth / 2) / 20;
        const mouseY = (e.clientY - window.innerHeight / 2) / 20;

        items.forEach((item) => {
            const speed = parseFloat(item.getAttribute('data-speed')) || 5;
            gsap.to(item, {
                x: mouseX * speed,
                y: mouseY * speed,
                rotation: mouseX * (speed / 2),
                duration: 1,
                ease: "power1.out"
            });
        });
    });

    // 5. Pokemon Style Expanding Cards (السلايدر الاحترافي)
    const panels = document.querySelectorAll('.skate-panel');
    panels.forEach(panel => {
        panel.addEventListener('click', () => {
            panels.forEach(p => p.classList.remove('active'));
            panel.classList.add('active');
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