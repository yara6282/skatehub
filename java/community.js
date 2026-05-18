document.addEventListener('DOMContentLoaded', () => {
    const cursor = document.querySelector('.cursor');
    const follower = document.querySelector('.cursor-follower');

    if (!cursor || !follower) return;

    document.body.style.cursor = 'none';

    function moveCursor(e) {
        cursor.style.left = e.clientX + 'px';
        cursor.style.top = e.clientY + 'px';

        follower.animate({
            left: `${e.clientX}px`,
            top: `${e.clientY}px`
        }, {
            duration: 500,
            fill: "forwards"
        });
    }

    document.addEventListener('mousemove', moveCursor);

    document.addEventListener('mouseenter', () => {
        cursor.style.display = 'block';
        follower.style.display = 'block';
    });

    document.addEventListener('mouseleave', () => {
        cursor.style.display = 'none';
        follower.style.display = 'none';
    });

    window.addEventListener('focus', () => {
        cursor.style.display = 'block';
        follower.style.display = 'block';
        document.body.style.cursor = 'none';
    });

    window.addEventListener('blur', () => {
        cursor.style.display = 'none';
        follower.style.display = 'none';
    });

    const cards = document.querySelectorAll('.tilt-target');

    cards.forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            const rotateX = (centerY - y) / 20;
            const rotateY = (x - centerX) / 20;

            card.style.transform =
                `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-10px)`;
        });

        card.addEventListener('mouseleave', () => {
            card.style.transform =
                `perspective(1000px) rotateX(0deg) rotateY(0deg) translateY(0)`;
        });
    });

    const interactables = document.querySelectorAll(
        'a, button, .story-item, input, textarea, .chat-user, label, select'
    );

    interactables.forEach(item => {
        item.addEventListener('mouseenter', () => {
            follower.style.transform = 'translate(-50%, -50%) scale(2)';
            follower.style.borderColor = '#00f2ff';
        });

        item.addEventListener('mouseleave', () => {
            follower.style.transform = 'translate(-50%, -50%) scale(1)';
            follower.style.borderColor = '#ff2d55';
        });
    });
});

function animateLike(btn) {
    const icon = btn.querySelector('i');

    icon.classList.toggle('fa-regular');
    icon.classList.toggle('fa-solid');

    icon.style.color = icon.classList.contains('fa-solid') ? '#ff2d55' : 'white';
    btn.style.transform = 'scale(1.2)';

    setTimeout(() => {
        btn.style.transform = 'scale(1)';
    }, 200);
}

function selectChat(user, name) {
    document.querySelectorAll('.chat-user').forEach(item => {
        item.classList.remove('active');
    });

    user.classList.add('active');

    const chatName = document.getElementById('chatName');
    const chatContainer = document.getElementById('chatContainer');

    if (!chatName || !chatContainer) return;

    chatName.textContent = name;

    if (name === 'Tony') {
        chatContainer.innerHTML = `
            <div class="msg received">Yo! That flip was sick!</div>
            <div class="msg sent">Thanks bro! Appreciate it.</div>
        `;
    } else if (name === 'Alex') {
        chatContainer.innerHTML = `
            <div class="msg received">Are you joining the challenge today?</div>
            <div class="msg sent">Yeah, I am ready 🔥</div>
        `;
    } else {
        chatContainer.innerHTML = `
            <div class="msg received">Your new trick looks amazing!</div>
            <div class="msg sent">Thank you Sarah!</div>
        `;
    }
}