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