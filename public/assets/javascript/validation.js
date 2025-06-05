/**
 * Form validation for the registration and login forms
 */
document.addEventListener('DOMContentLoaded', function() {
    // Get forms
    const registerForm = document.getElementById('register-form');
    const loginForm = document.getElementById('login-form');

    // Validation for registration form
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            let valid = true;
            const errors = {};
            
            // Get form fields
            const username = document.getElementById('username');
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            const passwordConfirm = document.getElementById('password_confirm');
            
            // Clear previous error messages
            clearErrors();
            
            // Validate username
            if (!username.value.trim()) {
                errors.username = 'Username is required';
                valid = false;
            } else if (username.value.length < 3 || username.value.length > 50) {
                errors.username = 'Username must be between 3 and 50 characters';
                valid = false;
            } else if (!/^[a-zA-Z0-9_-]+$/.test(username.value)) {
                errors.username = 'Username may only contain letters, numbers, dashes and underscores';
                valid = false;
            }
            
            // Validate email
            if (!email.value.trim()) {
                errors.email = 'Email is required';
                valid = false;
            } else if (!isValidEmail(email.value)) {
                errors.email = 'Please enter a valid email address';
                valid = false;
            }
            
            // Validate password
            if (!password.value) {
                errors.password = 'Password is required';
                valid = false;
            } else if (password.value.length < 8) {
                errors.password = 'Password must be at least 8 characters';
                valid = false;
            }
            
            // Validate password confirmation
            if (password.value !== passwordConfirm.value) {
                errors.password_confirm = 'Passwords do not match';
                valid = false;
            }
            
            // Display errors and prevent form submission if invalid
            if (!valid) {
                e.preventDefault();
                displayErrors(errors);
            }
        });
    }
    
    // Validation for login form
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            let valid = true;
            const errors = {};
            
            // Get form fields
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            
            // Clear previous error messages
            clearErrors();
            
            // Validate email
            if (!email.value.trim()) {
                errors.email = 'Email is required';
                valid = false;
            } else if (!isValidEmail(email.value)) {
                errors.email = 'Please enter a valid email address';
                valid = false;
            }
            
            // Validate password
            if (!password.value) {
                errors.password = 'Password is required';
                valid = false;
            }
            
            // Display errors and prevent form submission if invalid
            if (!valid) {
                e.preventDefault();
                displayErrors(errors);
            }
        });
    }
    
    // Helper function to validate email
    function isValidEmail(email) {
        const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return pattern.test(email);
    }
    
    // Helper function to display errors
    function displayErrors(errors) {
        for (const field in errors) {
            const errorElement = document.getElementById(`${field}-error`);
            if (errorElement) {
                errorElement.textContent = errors[field];
                errorElement.style.display = 'block';
                
                // Add error class to input
                const input = document.getElementById(field);
                if (input) {
                    input.classList.add('is-invalid');
                }
            }
        }
    }
    
    // Helper function to clear errors
    function clearErrors() {
        const errorElements = document.querySelectorAll('.error-message');
        errorElements.forEach(element => {
            element.textContent = '';
            element.style.display = 'none';
        });
        
        // Remove error class from inputs
        const inputs = document.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.classList.remove('is-invalid');
        });
    }
    
    // Real-time validation for password strength
    const passwordInput = document.getElementById('password');
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const strength = document.getElementById('password-strength');
            if (!strength) return;
            
            const value = passwordInput.value;
            let strengthText = '';
            let strengthClass = '';
            
            if (value.length === 0) {
                strengthText = '';
            } else if (value.length < 6) {
                strengthText = 'Weak';
                strengthClass = 'text-danger';
            } else if (value.length < 8) {
                strengthText = 'Medium';
                strengthClass = 'text-warning';
            } else if (value.length < 12) {
                strengthText = 'Strong';
                strengthClass = 'text-success';
            } else {
                strengthText = 'Very Strong';
                strengthClass = 'text-success';
            }
            
            strength.textContent = strengthText;
            strength.className = strengthClass;
        });
    }
}); 