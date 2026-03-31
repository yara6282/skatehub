let currentSlide = 0; // متغير بيحفظ رقم الصورة الحالية (بنبلش من صفر يعني الصورة الأولى)
        const slides = document.querySelectorAll('.slide'); // هون بنمسك كل الصور اللي عندهم كلاس slide

        function changeSlide() { // وظيفة (Function) مسؤولة عن تبديل الصور
            slides[currentSlide].classList.remove('active'); // بنشيل كلاس active من الصورة الحالية عشان تختفي
            currentSlide = (currentSlide + 1) % slides.length; // بنزيد الرقم 1، وإذا وصلنا لآخر صورة بنرجع للصفر
            slides[currentSlide].classList.add('active'); // بنضيف كلاس active للصورة الجديدة عشان تظهر
        }

        // هذا السطر بيشغل وظيفة التبديل كل 4000 جزء من الثانية (يعني كل 4 ثواني)
        setInterval(changeSlide, 4000); 