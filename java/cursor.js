document.addEventListener('DOMContentLoaded', () => {
    const cursor = document.querySelector('.cursor');
    const follower = document.querySelector('.cursor-follower');
    
    if (!cursor || !follower) return;

    // تحريك الماوس
    document.addEventListener('mousemove', (e) => {
        // استخدام GSAP للتحريك
        gsap.to(cursor, {
            x: e.clientX,
            y: e.clientY,
            duration: 0.1,
            overwrite: true
        });
        gsap.to(follower, {
            x: e.clientX,
            y: e.clientY,
            duration: 0.3,
            overwrite: true
        });
    });

    // التفاعل مع الأزرار
    const interactables = document.querySelectorAll('a, button, input, .sticker, .chip');
    interactables.forEach(item => {
        item.addEventListener('mouseenter', () => {
            cursor.classList.add('active');
        });
        item.addEventListener('mouseleave', () => {
            cursor.classList.remove('active');
        });
    });
});
// أضف هذا داخل مستمعmousemove في cursor.js
document.querySelectorAll('input[type="text"]').forEach(input => {
    input.addEventListener('mouseenter', () => {
        cursor.style.width = '2px'; // تحويل الكيرسر لشكل خط يشبه مؤشر الكتابة
        cursor.style.height = '20px';
        cursor.style.borderRadius = '0';
    });
    input.addEventListener('mouseleave', () => {
        cursor.style.width = '8px'; // إرجاع الكيرسر لشكل نقطة
        cursor.style.height = '8px';
        cursor.style.borderRadius = '50%';
    });
});