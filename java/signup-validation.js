const form = document.getElementById("signupForm");

const password = document.getElementById("password");
const confirmPassword = document.getElementById("confirm_password");

const passwordError = document.getElementById("password-error");
const confirmError = document.getElementById("confirm-error");

const togglePassword = document.getElementById("togglePassword");
const toggleConfirmPassword = document.getElementById("toggleConfirmPassword");

function validatePassword() {
    const value = password.value;

    if (value.length < 8) {
        passwordError.textContent = "Password must be at least 8 characters.";
        return false;
    }

    if (!/[A-Z]/.test(value)) {
        passwordError.textContent = "Password must contain at least one uppercase letter.";
        return false;
    }

    if (!/[a-z]/.test(value)) {
        passwordError.textContent = "Password must contain at least one lowercase letter.";
        return false;
    }

    if (!/[0-9]/.test(value)) {
        passwordError.textContent = "Password must contain at least one number.";
        return false;
    }

    passwordError.textContent = "";
    return true;
}

function validateConfirmPassword() {
    if (confirmPassword.value !== password.value) {
        confirmError.textContent = "Passwords do not match.";
        return false;
    }

    confirmError.textContent = "";
    return true;
}

form.addEventListener("submit", function (e) {
    passwordError.textContent = "";
    confirmError.textContent = "";

    const okPassword = validatePassword();
    const okConfirm = validateConfirmPassword();

    if (!okPassword || !okConfirm) {
        e.preventDefault();
    }
});

togglePassword.addEventListener("click", function () {
    if (password.type === "password") {
        password.type = "text";
        togglePassword.classList.remove("fa-eye");
        togglePassword.classList.add("fa-eye-slash");
    } else {
        password.type = "password";
        togglePassword.classList.remove("fa-eye-slash");
        togglePassword.classList.add("fa-eye");
    }
});

toggleConfirmPassword.addEventListener("click", function () {
    if (confirmPassword.type === "password") {
        confirmPassword.type = "text";
        toggleConfirmPassword.classList.remove("fa-eye");
        toggleConfirmPassword.classList.add("fa-eye-slash");
    } else {
        confirmPassword.type = "password";
        toggleConfirmPassword.classList.remove("fa-eye-slash");
        toggleConfirmPassword.classList.add("fa-eye");
    }
});