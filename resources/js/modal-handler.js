// Modal form handling
document.addEventListener('DOMContentLoaded', function() {
    // Test functions for debugging (optional - remove in production)
    window.testLogin = function() {
        console.log('Testing login modal...');
        window.dispatchEvent(new CustomEvent('open-modal', { detail: 'login' }));
    };

    window.testRegister = function() {
        console.log('Testing register modal...');
        window.dispatchEvent(new CustomEvent('open-modal', { detail: 'register' }));
    };

    window.testForgotPassword = function() {
        console.log('Testing forgot password modal...');
        window.dispatchEvent(new CustomEvent('open-modal', { detail: 'forgot-password' }));
    };
});

// Custom events for modal management
window.addEventListener('close-modals', function() {
    // This will close all modals by setting their Alpine.js data to false
    if (typeof Alpine !== 'undefined' && Alpine.store) {
        Alpine.store('modals', {
            login: false,
            register: false,
            forgotPassword: false
        });
    }
});

// To open login modal
$dispatch('open-modal', 'login')

// To open register modal  
$dispatch('open-modal', 'register')

// To open forgot password modal
$dispatch('open-modal', 'forgot-password')

// To close any modal
$dispatch('close-modal', 'modal-name')
