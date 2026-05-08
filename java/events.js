// تسجيل مكتبة ScrollTrigger الخاصة بـ GSAP
gsap.registerPlugin(ScrollTrigger);

document.addEventListener('DOMContentLoaded', () => {

    // 1. Custom Cursor Logic (مؤشر الماوس الاحترافي)
    const cursor = document.querySelector('.cursor');
    const follower = document.querySelector('.cursor-follower');
    const interactables = document.querySelectorAll('a, button, .skate-panel, input, select');

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