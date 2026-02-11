// Form Validation Functions

// Validate email format
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Validate password strength
function validatePassword(password) {
    return password.length >= 6;
}

// Validate required fields
function validateRequired(value) {
    return value.trim() !== '';
}

// Validate number
function validateNumber(value) {
    return !isNaN(value) && value > 0;
}

// Validate phone number
function validatePhone(phone) {
    const re = /^[0-9]{10}$/;
    return re.test(phone);
}

// Show error message
function showError(element, message) {
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.style.color = 'red';
    errorDiv.style.fontSize = '14px';
    errorDiv.style.marginTop = '5px';
    errorDiv.textContent = message;
    
    // Remove existing error
    const existingError = element.parentElement.querySelector('.error-message');
    if (existingError) {
        existingError.remove();
    }
    
    element.parentElement.appendChild(errorDiv);
    element.style.borderColor = 'red';
}

// Clear error message
function clearError(element) {
    const errorDiv = element.parentElement.querySelector('.error-message');
    if (errorDiv) {
        errorDiv.remove();
    }
    element.style.borderColor = '#ddd';
}

// Login form validation
function validateLoginForm(form) {
    let isValid = true;
    
    const userId = form.querySelector('[name="user_id"]');
    const password = form.querySelector('[name="password"]');
    
    // Clear previous errors
    clearError(userId);
    clearError(password);
    
    // Validate User ID
    if (!validateRequired(userId.value)) {
        showError(userId, 'User ID is required');
        isValid = false;
    }
    
    // Validate Password
    if (!validateRequired(password.value)) {
        showError(password, 'Password is required');
        isValid = false;
    }
    
    return isValid;
}

// Signup form validation
function validateSignupForm(form) {
    let isValid = true;
    
    const name = form.querySelector('[name="name"]');
    const email = form.querySelector('[name="email"]');
    const password = form.querySelector('[name="password"]');
    const confirmPassword = form.querySelector('[name="confirm_password"]');
    
    // Clear previous errors
    if (name) clearError(name);
    if (email) clearError(email);
    clearError(password);
    if (confirmPassword) clearError(confirmPassword);
    
    // Validate Name
    if (name && !validateRequired(name.value)) {
        showError(name, 'Name is required');
        isValid = false;
    }
    
    // Validate Email
    if (email) {
        if (!validateRequired(email.value)) {
            showError(email, 'Email is required');
            isValid = false;
        } else if (!validateEmail(email.value)) {
            showError(email, 'Please enter a valid email');
            isValid = false;
        }
    }
    
    // Validate Password
    if (!validateRequired(password.value)) {
        showError(password, 'Password is required');
        isValid = false;
    } else if (!validatePassword(password.value)) {
        showError(password, 'Password must be at least 6 characters');
        isValid = false;
    }
    
    // Validate Confirm Password
    if (confirmPassword) {
        if (!validateRequired(confirmPassword.value)) {
            showError(confirmPassword, 'Please confirm your password');
            isValid = false;
        } else if (password.value !== confirmPassword.value) {
            showError(confirmPassword, 'Passwords do not match');
            isValid = false;
        }
    }
    
    return isValid;
}

// Product form validation
function validateProductForm(form) {
    let isValid = true;
    
    const productName = form.querySelector('[name="product_name"]');
    const productPrice = form.querySelector('[name="product_price"]');
    
    // Clear previous errors
    clearError(productName);
    clearError(productPrice);
    
    // Validate Product Name
    if (!validateRequired(productName.value)) {
        showError(productName, 'Product name is required');
        isValid = false;
    }
    
    // Validate Product Price
    if (!validateRequired(productPrice.value)) {
        showError(productPrice, 'Product price is required');
        isValid = false;
    } else if (!validateNumber(productPrice.value)) {
        showError(productPrice, 'Please enter a valid price');
        isValid = false;
    }
    
    return isValid;
}

// Checkout form validation
function validateCheckoutForm(form) {
    let isValid = true;
    
    const name = form.querySelector('[name="name"]');
    const email = form.querySelector('[name="email"]');
    const phone = form.querySelector('[name="phone"]');
    const address = form.querySelector('[name="address"]');
    const city = form.querySelector('[name="city"]');
    const state = form.querySelector('[name="state"]');
    const pincode = form.querySelector('[name="pincode"]');
    
    // Clear previous errors
    [name, email, phone, address, city, state, pincode].forEach(field => {
        if (field) clearError(field);
    });
    
    // Validate all fields
    if (!validateRequired(name.value)) {
        showError(name, 'Name is required');
        isValid = false;
    }
    
    if (!validateRequired(email.value)) {
        showError(email, 'Email is required');
        isValid = false;
    } else if (!validateEmail(email.value)) {
        showError(email, 'Please enter a valid email');
        isValid = false;
    }
    
    if (!validateRequired(phone.value)) {
        showError(phone, 'Phone number is required');
        isValid = false;
    } else if (!validatePhone(phone.value)) {
        showError(phone, 'Please enter a valid 10-digit phone number');
        isValid = false;
    }
    
    if (!validateRequired(address.value)) {
        showError(address, 'Address is required');
        isValid = false;
    }
    
    if (!validateRequired(city.value)) {
        showError(city, 'City is required');
        isValid = false;
    }
    
    if (!validateRequired(state.value)) {
        showError(state, 'State is required');
        isValid = false;
    }
    
    if (!validateRequired(pincode.value)) {
        showError(pincode, 'Pincode is required');
        isValid = false;
    }
    
    return isValid;
}

// Confirm delete action
function confirmDelete(itemName) {
    return confirm(`Are you sure you want to delete ${itemName}?`);
}

// Confirm action
function confirmAction(message) {
    return confirm(message);
}
