function toggleForm(formType) 
{
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const toggleBtns = document.querySelectorAll('.toggle-btn');

    if (formType === 'login') {
        loginForm.classList.add('active');
        registerForm.classList.remove('active');
        toggleBtns[0].classList.add('active');
        toggleBtns[1].classList.remove('active');
    } else {
        registerForm.classList.add('active');
        loginForm.classList.remove('active');
        toggleBtns[1].classList.add('active');
        toggleBtns[0].classList.remove('active');
    }
}

function validateLogin(event) {
    event.preventDefault();
    
    document.querySelectorAll('#loginForm .error-message').forEach(error => {
        error.style.display = 'none';
    });

    let isValid = true;
    const email = document.getElementById('loginEmail').value;
    const password = document.getElementById('loginPassword').value;

    if (!email) {
        showError('loginEmailError', 'Email is required');
        isValid = false;
    } else if (!isValidEmail(email)) {
        showError('loginEmailError', 'Please enter a valid email');
        isValid = false;
    }

    if (!password) {
        showError('loginPasswordError', 'Password is required');
        isValid = false;
    }

    if (isValid) {
        console.log('Login form submitted:', { email, password });
        alert('Login successful!');
    }

    return false;
}

function validateRegister(event) {
    event.preventDefault();
    
    document.querySelectorAll('#registerForm .error-message').forEach(error => {
        error.style.display = 'none';
    });

    let isValid = true;
    const fullName = document.getElementById('fullName').value;
    const email = document.getElementById('registerEmail').value;
    const password = document.getElementById('registerPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    const terms = document.getElementById('terms').checked;

    if (!fullName) {
        showError('nameError', 'Full name is required');
        isValid = false;
    } else if (fullName.length < 2) {
        showError('nameError', 'Name must be at least 2 characters long');
        isValid = false;
    }

    if (!email) {
        showError('registerEmailError', 'Email is required');
        isValid = false;
    } else if (!isValidEmail(email)) {
        showError('registerEmailError', 'Please enter a valid email');
        isValid = false;
    }

    if (!password) {
        showError('registerPasswordError', 'Password is required');
        isValid = false;
    } else if (!isValidPassword(password)) {
        showError('registerPasswordError', 'Password must be at least 8 characters long and include a number and a special character');
        isValid = false;
    }

    if (!confirmPassword) {
        showError('confirmPasswordError', 'Please confirm your password');
        isValid = false;
    } else if (password !== confirmPassword) {
        showError('confirmPasswordError', 'Passwords do not match');
        isValid = false;
    }

    if (!terms) {
        showError('termsError', 'You must agree to the Terms of Service and Privacy Policy');
        isValid = false;
    }

    if (isValid) {
        console.log('Registration form submitted:', { fullName, email, password, terms });
        alert('Registration successful!');
    }

    return false;
}

function showError(elementId, message) {
    const errorElement = document.getElementById(elementId);
    errorElement.textContent = message;
    errorElement.style.display = 'block';
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}



// User-Role

document.addEventListener("DOMContentLoaded", function () {
    const roleSelect = document.getElementById("registerRole"); // Target the correct select dropdown
    const forms = document.querySelectorAll(".form-container");

    function showSelectedForm() {
        const selectedRole = roleSelect.value;
        
        forms.forEach(form => {
            form.style.display = "none"; // Hide all forms initially
        });
        
        if (selectedRole) {
            const selectedForm = document.getElementById(selectedRole);
            if (selectedForm) {
                selectedForm.style.display = "block"; // Show relevant form
            }
        }
    }

    roleSelect.addEventListener("change", showSelectedForm);
    
    showSelectedForm(); // Ensure correct form is displayed on page load
});