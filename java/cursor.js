document.addEventListener('DOMContentLoaded', () => {
    const cursor = document.querySelector('.cursor');
    const follower = document.querySelector('.cursor-follower');
    
    // إذا فتح الموقع من موبايل، أوقف الكود عشان نوفر أداء
    if (window.innerWidth <= 768) return;

    // تحريك الماوس باستخدام مكتبة GSAP للنعومة الخرافية
    document.addEventListener('mousemove', (e) => {
        gsap.to(cursor, { x: e.clientX, y: e.clientY, duration: 0.1 });
        gsap.to(follower, { x: e.clientX, y: e.clientY, duration: 0.3 }); // الدائرة تتأخر شوي لتعطي ستايل حلو
    });

    // تحديد كل العناصر القابلة للضغط في الموقع (روابط، أزرار، حقول إدخال)
    const interactables = document.querySelectorAll('a, button, input, select, textarea, .skate-panel');

    // تأثير التكبير وتغيير اللون لما تمر فوق عنصر قابل للضغط
    interactables.forEach(item => {
        item.addEventListener('mouseenter', () => {
            cursor.classList.add('active');
            follower.style.borderColor = "var(--neon-blue, #00f2ff)"; // الدائرة بتصير زرقاء
        });
        item.addEventListener('mouseleave', () => {
            cursor.classList.remove('active');
            follower.style.borderColor = "var(--neon-pink, #ff007a)"; // ترجع زهرية
        });
    });
});
// Add this to your cursor.js
const links = document.querySelectorAll('a, button, .media-box');
links.forEach(link => {
    link.addEventListener('mouseenter', () => {
        cursor.classList.add('cursor-hover'); // اضف كلاس CSS يكبر حجم الكيرسر
    });
    link.addEventListener('mouseleave', () => {
        cursor.classList.remove('cursor-hover');
    });
});