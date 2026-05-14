document.addEventListener("DOMContentLoaded", function () {
    const passwordInput = document.getElementById("password");
    const togglePassword = document.getElementById("togglePassword");

    const emailError = document.getElementById("email-error");
    const passwordError = document.getElementById("password-error");

    togglePassword.addEventListener("click", function () {
        if (passwordInput.getAttribute("type") === "password") {
            passwordInput.setAttribute("type", "text");
            togglePassword.classList.remove("fa-eye");
            togglePassword.classList.add("fa-eye-slash");
        } else {
            passwordInput.setAttribute("type", "password");
            togglePassword.classList.remove("fa-eye-slash");
            togglePassword.classList.add("fa-eye");
        }
    });

    const params = new URLSearchParams(window.location.search);

    if (params.get("email") === "notfound") {
        emailError.textContent = "This email does not exist.";
    }

    if (params.get("password") === "wrong") {
        passwordError.textContent = "Incorrect password.";
    }

    if (window.location.search) {
        window.history.replaceState({}, document.title, window.location.pathname);
    }

    window.addEventListener("beforeunload", function () {
        emailError.textContent = "";
        passwordError.textContent = "";
    });
});