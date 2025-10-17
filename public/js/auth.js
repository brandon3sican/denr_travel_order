// auth.js - Authentication related JavaScript functionality

document.addEventListener('DOMContentLoaded', function() {
    // Check if we're on the login page
    if (document.querySelector('.login-form')) {
        setupLoginForm();
    }

    // Handle session timeout warning
    setupSessionTimeoutWarning();
});

/**
 * Setup login form submission
 */
function setupLoginForm() {
    const loginForm = document.getElementById('loginForm');
    
    if (loginForm) {
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(loginForm);
            const submitButton = loginForm.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.innerHTML;
            
            try {
                // Show loading state
                submitButton.disabled = true;
                submitButton.innerHTML = 'Signing in...';
                
                const response = await fetch(loginForm.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    // Redirect on successful login
                    window.location.href = data.redirect || '/dashboard';
                } else {
                    // Show error message
                    showError(data.message || 'Login failed. Please check your credentials.');
                }
            } catch (error) {
                console.error('Login error:', error);
                showError('An error occurred during login. Please try again.');
            } finally {
                // Reset button state
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
            }
        });
    }
}

/**
 * Show error message
 */
function showError(message) {
    // Check if we have a dedicated error container
    const errorContainer = document.getElementById('error-message') || 
                         document.querySelector('.error-message');
    
    if (errorContainer) {
        errorContainer.textContent = message;
        errorContainer.classList.remove('hidden');
    } else {
        // Fallback to alert if no error container found
        alert(message);
    }
}

/**
 * Setup session timeout warning
 */
function setupSessionTimeoutWarning() {
    // Check if user is authenticated
    if (!document.querySelector('meta[name="user-id"]')) return;
    
    const warningTime = 1000 * 60 * 5; // 5 minutes before session expires
    const checkInterval = 1000 * 60; // Check every minute
    let timeoutId;
    
    function checkSession() {
        fetch('/session/keep-alive')
            .then(response => response.json())
            .then(data => {
                if (data.remaining && data.remaining < (warningTime / 1000)) {
                    // Show warning if we're within the warning time
                    showSessionWarning(data.remaining);
                }
            })
            .catch(console.error);
    }
    
    function showSessionWarning(secondsRemaining) {
        // Only show one warning at a time
        if (document.getElementById('session-warning')) return;
        
        const minutes = Math.ceil(secondsRemaining / 60);
        const warning = document.createElement('div');
        warning.id = 'session-warning';
        warning.className = 'fixed bottom-4 right-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded shadow-lg';
        warning.role = 'alert';
        warning.innerHTML = `
            <p class="font-bold">Session Timeout Warning</p>
            <p>Your session will expire in ${minutes} minute${minutes > 1 ? 's' : ''}. Click <a href="/session/keep-alive" class="font-semibold underline">here</a> to stay signed in.</p>
        `;
        
        document.body.appendChild(warning);
        
        // Auto-remove after some time
        setTimeout(() => {
            warning.remove();
        }, 1000 * 60 * 2); // Remove after 2 minutes
    }
    
    // Initial check
    checkSession();
    
    // Set up periodic checking
    timeoutId = setInterval(checkSession, checkInterval);
    
    // Clear interval when page unloads
    window.addEventListener('beforeunload', () => {
        if (timeoutId) clearInterval(timeoutId);
    });
}

// Make functions available globally if needed
window.auth = {
    showError,
    setupLoginForm,
    setupSessionTimeoutWarning
};
