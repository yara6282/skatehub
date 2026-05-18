<div class="ai-floating-widget">
    <button class="ai-open-btn" onclick="toggleAiChat()">
        <i class="fas fa-robot"></i>
    </button>

    <div class="ai-chat-box" id="aiChatBox">
        <div class="ai-chat-header">
            <div>
                <strong>SkateHub AI</strong>
                <p>Smart local assistant 🚀</p>
            </div>
            <button onclick="toggleAiChat()"><i class="fas fa-times"></i></button>
        </div>

        <div class="ai-chat-messages" id="aiMessages">
            <div class="ai-msg bot">
                Hey 👋 I can help with skateboard sizes, tricks, safety, shop, crews, stories, orders, and events.
            </div>
        </div>

        <form class="ai-chat-input" onsubmit="sendAiMessage(event)">
            <input type="text" id="aiInput" placeholder="Ask SkateHub AI..." autocomplete="off">
            <button type="submit"><i class="fas fa-paper-plane"></i></button>
        </form>
    </div>
</div>

<style>
.ai-floating-widget{position:fixed;bottom:25px;right:25px;z-index:999999}
.ai-open-btn{width:70px;height:70px;border:none;border-radius:50%;background:linear-gradient(135deg,#00f2ff,#ff2d55);color:white;font-size:27px;box-shadow:0 0 30px rgba(0,242,255,.35);animation:aiPulse 2s infinite}
@keyframes aiPulse{0%{transform:scale(1)}50%{transform:scale(1.07)}100%{transform:scale(1)}}
.ai-chat-box{position:absolute;bottom:90px;right:0;width:360px;height:520px;display:none;flex-direction:column;background:#081423;border:1px solid rgba(0,242,255,.18);border-radius:28px;overflow:hidden;box-shadow:0 0 45px rgba(0,0,0,.55)}
.ai-chat-box.show-ai{display:flex}
.ai-chat-header{padding:18px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.08)}
.ai-chat-header strong{color:#00f2ff}
.ai-chat-header p{color:rgba(255,255,255,.6);font-size:12px;margin-top:4px}
.ai-chat-header button{background:none;border:none;color:white;font-size:18px}
.ai-chat-messages{flex:1;padding:18px;overflow-y:auto;display:flex;flex-direction:column;gap:14px}
.ai-msg{padding:14px 16px;border-radius:18px;line-height:1.5;max-width:85%;font-size:14px}
.ai-msg.bot{align-self:flex-start;background:rgba(255,255,255,.06);color:white}
.ai-msg.user{align-self:flex-end;background:#00f2ff;color:#00101c}
.ai-chat-input{padding:16px;display:flex;gap:10px;border-top:1px solid rgba(255,255,255,.08)}
.ai-chat-input input{flex:1;border:none;outline:none;border-radius:16px;padding:14px;background:rgba(255,255,255,.06);color:white}
.ai-chat-input button{width:55px;border:none;border-radius:16px;background:linear-gradient(135deg,#00f2ff,#00a8ff);color:#00101c;font-size:18px}
</style>

<script>
function toggleAiChat(){
    document.getElementById("aiChatBox").classList.toggle("show-ai");
}

function sendAiMessage(e){
    e.preventDefault();

    const input = document.getElementById("aiInput");
    const messages = document.getElementById("aiMessages");
    const text = input.value.trim();

    if(text === "") return;

    messages.innerHTML += `<div class="ai-msg user">${escapeHtml(text)}</div>`;
    input.value = "";

    const loading = document.createElement("div");
    loading.className = "ai-msg bot";
    loading.innerText = "Thinking...";
    messages.appendChild(loading);

    setTimeout(() => {
        loading.innerHTML = getSkateHubReply(text);
        messages.scrollTop = messages.scrollHeight;
    }, 500);

    messages.scrollTop = messages.scrollHeight;
}

function getSkateHubReply(text){
    const q = text.toLowerCase();

    if(has(q, ["deck", "board size", "skateboard size", "مقاس", "لوح", "بورد"])){
        return `
        🛹 <strong>Best skateboard deck size:</strong><br><br>
        • Beginner: <strong>7.75 - 8.0 inch</strong><br>
        • Street tricks: <strong>7.75 - 8.25</strong><br>
        • Park / ramps: <strong>8.25 - 8.5</strong><br>
        • Stable riding: <strong>8.25+</strong><br><br>
        If you are not sure, choose <strong>8.0</strong>. It is the safest all-around size.
        `;
    }

    if(has(q, ["wheel", "wheels", "عجل", "عجلات"])){
        return `
        🛞 <strong>Wheel guide:</strong><br><br>
        • Street tricks: small hard wheels, around <strong>52-54mm</strong><br>
        • Cruising: bigger softer wheels, around <strong>56-60mm</strong><br>
        • Rough streets: softer wheels are better.<br><br>
        For beginners, choose medium wheels so you can ride and learn tricks.
        `;
    }

    if(has(q, ["bearing", "bearings", "رمان", "بلبرنغ"])){
        return `
        ⚙️ Bearings help wheels spin smoothly.<br><br>
        For normal skating, <strong>ABEC-5 or ABEC-7</strong> is enough. Clean them if they feel slow, and avoid water.
        `;
    }

    if(has(q, ["shoe", "shoes", "حذاء", "كندرة"])){
        return `
        👟 <strong>Skate shoes tips:</strong><br><br>
        Choose flat sole shoes with good grip. They should feel snug, but not painful. Avoid running shoes because they reduce board control.
        `;
    }

    if(has(q, ["helmet", "protect", "safety", "خوذة", "حماية", "أمان"])){
        return `
        🪖 Safety first:<br><br>
        Use a helmet, wrist guards, knee pads, and elbow pads, especially if you are beginner or trying ramps. Falling is normal, protection keeps you confident.
        `;
    }

    if(has(q, ["ollie", "trick", "tricks", "حركة", "حركات"])){
        return `
        🔥 <strong>Beginner trick path:</strong><br><br>
        1. Balance and pushing<br>
        2. Turning and stopping<br>
        3. Manual practice<br>
        4. Ollie basics<br>
        5. Shuvit<br><br>
        For ollie: pop the tail, slide front foot up, keep shoulders straight, and land with knees bent.
        `;
    }

    if(has(q, ["shop", "buy", "cart", "شراء", "اشتري", "سلة"])){
        return `
        🛒 In SkateHub shop:<br><br>
        Choose your product, select size if available, add to cart, then checkout. You can track the order status from your profile/orders area.
        `;
    }

    if(has(q, ["order", "shipping", "delivery", "طلب", "شحن", "توصيل"])){
        return `
        📦 Order status meanings:<br><br>
        • Pending Review: admin is checking the order<br>
        • Preparing Gear: items are being prepared<br>
        • Shipped: order is on the way<br>
        • Delivered: order arrived<br><br>
        You will receive notifications when the status changes.
        `;
    }

    if(has(q, ["event", "events", "challenge", "فعالية", "فعاليات", "تحدي"])){
        return `
        📅 Events page shows skate events and challenges. You can check event date, location, price, and details before joining.
        `;
    }

    if(has(q, ["crew", "crews", "group", "كرو", "مجموعة"])){
        return `
        👥 Skate Crews let you join local rider groups. After joining/following riders, you can chat with them and see their community activity.
        `;
    }

    if(has(q, ["story", "stories", "ستوري", "ستوريز"])){
        return `
        📸 Stories stay for <strong>24 hours</strong>. You can add an image, write a caption, see viewers, see likes, and delete your own story.
        `;
    }

    if(has(q, ["profile", "بروفايل", "account", "حساب"])){
        return `
        👤 Your community profile shows your posts, followers, following, comments, likes, and joined skate crews.
        `;
    }

    if(has(q, ["follow", "follower", "following", "تابع", "متابع"])){
        return `
        🤝 Follow system helps you connect with skaters. When you follow someone, they get a notification, and you can see their stories/posts.
        `;
    }

    if(has(q, ["chat", "message", "رسالة", "شات"])){
        return `
        💬 Private chat works between users you follow or who follow you. Search for a rider, open chat, and send messages privately.
        `;
    }

    if(has(q, ["admin", "moderation", "ادمن", "مشرف"])){
        return `
        🛡️ Admin dashboard can manage products, events, tutorials, orders, and community moderation like deleting posts, comments, and stories.
        `;
    }

    if(has(q, ["beginner", "new", "مبتدئ", "بداية"])){
        return `
        🌱 Beginner plan:<br><br>
        1. Learn balance<br>
        2. Practice pushing<br>
        3. Learn stopping safely<br>
        4. Practice turning<br>
        5. Start small tricks<br><br>
        Do not rush. 20 minutes daily is better than one long session.
        `;
    }

    if(has(q, ["hello", "hi", "hey", "مرحبا", "هاي", "هلا"])){
        return `
        Hey legend 🔥 I’m SkateHub AI. Ask me about skateboard sizes, tricks, shop, events, crews, stories, chat, or orders.
        `;
    }

    return `
    🤖 I can help with SkateHub and skating questions.<br><br>
    Try asking:<br>
    • What skateboard size should I buy?<br>
    • How do I learn ollie?<br>
    • How do orders work?<br>
    • What are skate crews?<br>
    • How long do stories stay?
    `;
}

function has(q, words){
    return words.some(word => q.includes(word));
}

function escapeHtml(text){
    const div = document.createElement("div");
    div.innerText = text;
    return div.innerHTML;
}
</script>