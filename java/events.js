document.addEventListener('DOMContentLoaded', () => {
    // Initialize AOS (Animate on Scroll)
    AOS.init({
        duration: 1000,
        once: true
    });

    // Parallax effect for floating images
    document.addEventListener('mousemove', (e) => {
        const imgs = document.querySelectorAll('.float-img');
        const x = (window.innerWidth - e.pageX * 2) / 100;
        const y = (window.innerHeight - e.pageY * 2) / 100;

        imgs.forEach(img => {
            img.style.transform = `translateX(${x}px) translateY(${y}px)`;
        });
    });
    // Simple search button interaction
    const searchBtn = document.querySelector('.search-btn');
    searchBtn.addEventListener('click', () => {
        searchBtn.textContent = 'Searching...';
        setTimeout(() => {
            searchBtn.textContent = 'Search Now';
        }, 1000);
    });
});
document.addEventListener('DOMContentLoaded', () => {
    const items = document.querySelectorAll('.float-item');
    let mouseX = 0;
    let mouseY = 0;

    // 1. التقاط إحداثيات الماوس
    document.addEventListener('mousemove', (e) => {
        mouseX = (e.clientX - window.innerWidth / 2) / 20;
        mouseY = (e.clientY - window.innerHeight / 2) / 20;
    });

    // 2. محرك الحركة (Animation Engine)
    function animate() {
        items.forEach((item, index) => {
            // الحصول على السرعة المخصصة لكل عنصر من الـ HTML
            const speed = parseFloat(item.getAttribute('data-speed')) || 5;
            
            // حساب حركة "النبض" التلقائي (باستخدام الدالة الرياضية Sin)
            const autoFloatY = Math.sin(Date.now() * 0.002 + index) * 20;
            const autoFloatX = Math.cos(Date.now() * 0.002 + index) * 10;

            // حساب الحركة النهائية: (الماوس + النبض التلقائي)
            const finalX = (mouseX * speed / 5) + autoFloatX;
            const finalY = (mouseY * speed / 5) + autoFloatY;
            
            // حساب الدوران المائل حسب حركة الماوس
            const rotation = (mouseX * speed / 10);

            // تطبيق التحويل البرمجي
            item.style.transform = `translate(${finalX}px, ${finalY}px) rotate(${rotation}deg)`;
        });

        // تكرار الأنميشن باستمرار لسلاسة قصوى
        requestAnimationFrame(animate);
    }

    // تشغيل المحرك
    animate();
});