gsap.registerPlugin(ScrollTrigger);

// 1. حركة اللوح في البداية (Floating Effect)
gsap.to("#floating-board", {
    y: -30,
    rotation: 5,
    duration: 2,
    repeat: -1,
    yoyo: true,
    ease: "power1.inOut"
});

// 2. حركة اللوح مع السكروول (وكأنه بحرك فوق الكلمة)
gsap.to("#floating-board", {
    scrollTrigger: {
        trigger: ".hero",
        start: "top top",
        end: "bottom top",
        scrub: true
    },
    x: 500,
    rotation: 20,
    scale: 0.5
});

// 3. أنميشن القفزة فوق العبارة المحفزة
gsap.to("#jumping-skater", {
    scrollTrigger: {
        trigger: ".motivation",
        start: "top center",
        end: "center center",
        scrub: 1
    },
    left: "70%",
    y: -200, // القفزة للأعلى
    rotation: 360, // حركة بهلوانية
    ease: "power2.out"
});

// 4. ظهور كلمة Join Us عند الوصول للمتزلج الأخير
gsap.to("#join-box", {
    scrollTrigger: {
        trigger: ".cta-section",
        start: "top 30%", // لما توصل الشاشة لهون
        toggleActions: "play none none reverse"
    },
    opacity: 1,
    scale: 1,
    duration: 1,
    ease: "back.out(1.7)"
});

// تأثير تفاعلي خفيف: المتزلج الثابت يميل مع الماوس
document.addEventListener("mousemove", (e) => {
    const x = (e.clientX / window.innerWidth - 0.5) * 20;
    const y = (e.clientY / window.innerHeight - 0.5) * 20;
    gsap.to("#fixed-skater", { x: x, y: y, duration: 1 });
});