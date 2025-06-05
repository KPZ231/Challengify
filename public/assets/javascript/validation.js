/**
 * Challengify Form Validation
 * 
 * Handles client-side validation for login and registration forms
 */

document.addEventListener('DOMContentLoaded', function() {
    // Form validation for login form
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            let valid = true;
            
            // Email validation
            const emailInput = document.getElementById('email');
            if (emailInput && !validateEmail(emailInput.value)) {
                showError(emailInput, 'Please enter a valid email address');
                valid = false;
            } else {
                clearError(emailInput);
            }
            
            // Password validation
            const passwordInput = document.getElementById('password');
            if (passwordInput && passwordInput.value.length < 1) {
                showError(passwordInput, 'Password is required');
                valid = false;
            } else {
                clearError(passwordInput);
            }
            
            if (!valid) {
                e.preventDefault();
            }
        });
    }
    
    // Form validation for register form
    const registerForm = document.getElementById('register-form');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            let valid = true;
            
            // Username validation
            const usernameInput = document.getElementById('username');
            if (usernameInput) {
                if (usernameInput.value.length < 3) {
                    showError(usernameInput, 'Username must be at least 3 characters');
                    valid = false;
                } else if (usernameInput.value.length > 50) {
                    showError(usernameInput, 'Username cannot exceed 50 characters');
                    valid = false;
                } else if (!validateUsername(usernameInput.value)) {
                    showError(usernameInput, 'Username can only contain letters, numbers, dashes, and underscores');
                    valid = false;
                } else {
                    clearError(usernameInput);
                }
            }
            
            // Email validation
            const emailInput = document.getElementById('email');
            if (emailInput && !validateEmail(emailInput.value)) {
                showError(emailInput, 'Please enter a valid email address');
                valid = false;
            } else {
                clearError(emailInput);
            }
            
            // Password validation
            const passwordInput = document.getElementById('password');
            if (passwordInput) {
                if (passwordInput.value.length < 8) {
                    showError(passwordInput, 'Password must be at least 8 characters long');
                    valid = false;
                } else {
                    clearError(passwordInput);
                }
            }
            
            // Password confirmation validation
            const confirmInput = document.getElementById('password_confirm');
            if (confirmInput && passwordInput) {
                if (confirmInput.value !== passwordInput.value) {
                    showError(confirmInput, 'Passwords do not match');
                    valid = false;
                } else {
                    clearError(confirmInput);
                }
            }
            
            // Terms checkbox validation
            const termsCheckbox = document.getElementById('terms');
            if (termsCheckbox && !termsCheckbox.checked) {
                showError(termsCheckbox, 'You must agree to the Terms of Service and Privacy Policy');
                valid = false;
            } else if (termsCheckbox) {
                clearError(termsCheckbox);
            }
            
            if (!valid) {
                e.preventDefault();
            }
        });
    }
    
    // Utility functions
    function validateEmail(email) {
        const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }
    
    function validateUsername(username) {
        const re = /^[a-zA-Z0-9_-]+$/;
        return re.test(username);
    }
    
    function showError(element, message) {
        // Find parent .form-input-icon div
        const parentDiv = element.closest('.form-input-icon') || element.parentNode;
        
        // Check if error message element already exists
        let errorElement = parentDiv.querySelector('.error-message');
        
        // If not, create a new one
        if (!errorElement) {
            errorElement = document.createElement('p');
            errorElement.className = 'mt-1 text-sm text-red-600 error-message';
            parentDiv.appendChild(errorElement);
        }
        
        // Set the error message
        errorElement.textContent = message;
        
        // Add error class to input
        element.classList.add('border-red-500');
    }
    
    function clearError(element) {
        // Find parent .form-input-icon div
        const parentDiv = element.closest('.form-input-icon') || element.parentNode;
        
        // Remove error message if it exists
        const errorElement = parentDiv.querySelector('.error-message');
        if (errorElement) {
            errorElement.remove();
        }
        
        // Remove error class from input
        element.classList.remove('border-red-500');
    }
}); 