// assets/js/main.js - Main JavaScript file for Learnify LMS

// Global variables
let currentUser = null;
let isMobile = window.innerWidth < 768;

// Document ready function
document.addEventListener('DOMContentLoaded', function() {
    initializeApp();
    setupEventListeners();
    setupUIComponents();
});

/**
 * Initialize the application
 */
function initializeApp() {
    console.log('Learnify LMS initialized');
    
    // Check if user is logged in (from session/localStorage)
    checkAuthStatus();
    
    // Setup mobile detection
    setupMobileDetection();
    
    // Initialize tooltips
    initTooltips();
    
    // Initialize loading states
    initLoadingStates();
    
    // Setup form handling
    setupFormHandling();
}

/**
 * Setup global event listeners
 */
function setupEventListeners() {
    // Mobile menu toggle
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', toggleMobileMenu);
    }

    // Search functionality
    const searchInput = document.getElementById('globalSearch');
    if (searchInput) {
        searchInput.addEventListener('input', debounce(handleGlobalSearch, 300));
    }

    // Notification bell
    const notificationBell = document.querySelector('.notification-bell');
    if (notificationBell) {
        notificationBell.addEventListener('click', toggleNotifications);
    }

    // User dropdown
    const userMenu = document.querySelector('.user-menu');
    if (userMenu) {
        userMenu.addEventListener('click', toggleUserDropdown);
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', closeAllDropdowns);

    // Handle window resize
    window.addEventListener('resize', debounce(handleResize, 250));

    // Handle beforeunload for form protection
    window.addEventListener('beforeunload', handleBeforeUnload);
}

/**
 * Setup UI components
 */
function setupUIComponents() {
    // Initialize dropdowns
    initDropdowns();
    
    // Initialize modals
    initModals();
    
    // Initialize tabs
    initTabs();
    
    // Initialize accordions
    initAccordions();
    
    // Initialize progress bars
    initProgressBars();
    
    // Initialize counters
    initCounters();
}

// *********************************************
// AUTHENTICATION & USER MANAGEMENT
// =============================================

/**
 * Check authentication status
 */
function checkAuthStatus() {
    const userData = localStorage.getItem('currentUser');
    if (userData) {
        try {
            currentUser = JSON.parse(userData);
            updateUIForAuthState(true);
        } catch (e) {
            console.error('Error parsing user data:', e);
            localStorage.removeItem('currentUser');
        }
    } else {
        updateUIForAuthState(false);
    }
}

/**
 * Update UI based on authentication state
 */
function updateUIForAuthState(isLoggedIn) {
    const authElements = document.querySelectorAll('[data-auth]');
    authElements.forEach(element => {
        const authState = element.getAttribute('data-auth');
        if (authState === 'logged-in' && !isLoggedIn) {
            element.style.display = 'none';
        } else if (authState === 'logged-out' && isLoggedIn) {
            element.style.display = 'none';
        } else {
            element.style.display = '';
        }
    });

    // Update user-specific content
    if (isLoggedIn && currentUser) {
        const userNames = document.querySelectorAll('[data-user-name]');
        userNames.forEach(element => {
            element.textContent = currentUser.first_name || 'User';
        });

        const userRoles = document.querySelectorAll('[data-user-role]');
        userRoles.forEach(element => {
            element.textContent = currentUser.role || 'Student';
        });
    }
}

/**
 * Login function
 */
async function loginUser(email, password) {
    showLoading('Logging in...');
    
    try {
        const response = await fetch('/api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ email, password })
        });

        const data = await response.json();

        if (data.success) {
            localStorage.setItem('currentUser', JSON.stringify(data.user));
            localStorage.setItem('authToken', data.token);
            currentUser = data.user;
            
            showNotification('Login successful!', 'success');
            setTimeout(() => {
                window.location.href = data.redirect || '/dashboard.php';
            }, 1000);
        } else {
            throw new Error(data.message || 'Login failed');
        }
    } catch (error) {
        showNotification(error.message, 'error');
    } finally {
        hideLoading();
    }
}

/**
 * Logout function
 */
function logoutUser() {
    if (confirm('Are you sure you want to logout?')) {
        localStorage.removeItem('currentUser');
        localStorage.removeItem('authToken');
        currentUser = null;
        
        showNotification('Logged out successfully', 'success');
        setTimeout(() => {
            window.location.href = '/login.php';
        }, 1000);
    }
}

// *********************************************
// UI COMPONENTS & INTERACTIONS
// =============================================

/**
 * Mobile menu toggle
 */
function toggleMobileMenu() {
    const nav = document.querySelector('.nav-links');
    const body = document.body;
    
    if (nav) {
        nav.classList.toggle('active');
        body.classList.toggle('mobile-menu-open');
    }
}

/**
 * Toggle notifications dropdown
 */
function toggleNotifications(e) {
    e.stopPropagation();
    const dropdown = document.querySelector('.notifications-dropdown');
    if (dropdown) {
        dropdown.classList.toggle('active');
        
        // Close other dropdowns
        document.querySelectorAll('.dropdown.active').forEach(drop => {
            if (drop !== dropdown) drop.classList.remove('active');
        });
    }
}

/**
 * Toggle user dropdown
 */
function toggleUserDropdown(e) {
    e.stopPropagation();
    const dropdown = document.querySelector('.user-dropdown');
    if (dropdown) {
        dropdown.classList.toggle('active');
        
        // Close other dropdowns
        document.querySelectorAll('.dropdown.active').forEach(drop => {
            if (drop !== dropdown) drop.classList.remove('active');
        });
    }
}

/**
 * Close all dropdowns
 */
function closeAllDropdowns(e) {
    // Don't close if clicking on a dropdown toggle
    if (e && (e.target.closest('.dropdown-toggle') || e.target.closest('.dropdown'))) {
        return;
    }
    
    document.querySelectorAll('.dropdown.active').forEach(dropdown => {
        dropdown.classList.remove('active');
    });
}

/**
 * Initialize dropdowns
 */
function initDropdowns() {
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.stopPropagation();
            const dropdown = this.nextElementSibling;
            if (dropdown && dropdown.classList.contains('dropdown')) {
                dropdown.classList.toggle('active');
                
                // Close other dropdowns
                document.querySelectorAll('.dropdown.active').forEach(drop => {
                    if (drop !== dropdown) drop.classList.remove('active');
                });
            }
        });
    });
}

/**
 * Initialize modals
 */
function initModals() {
    // Open modal
    document.querySelectorAll('[data-modal-target]').forEach(trigger => {
        trigger.addEventListener('click', function() {
            const modalId = this.getAttribute('data-modal-target');
            const modal = document.getElementById(modalId);
            if (modal) {
                openModal(modal);
            }
        });
    });

    // Close modal
    document.querySelectorAll('.modal-close, .modal-overlay').forEach(closeBtn => {
        closeBtn.addEventListener('click', function() {
            closeModal(this.closest('.modal'));
        });
    });

    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAllModals();
        }
    });
}

/**
 * Open modal
 */
function openModal(modal) {
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

/**
 * Close modal
 */
function closeModal(modal) {
    modal.classList.remove('active');
    document.body.style.overflow = '';
}

/**
 * Close all modals
 */
function closeAllModals() {
    document.querySelectorAll('.modal.active').forEach(modal => {
        closeModal(modal);
    });
}

/**
 * Initialize tabs
 */
function initTabs() {
    const tabContainers = document.querySelectorAll('.tabs');
    
    tabContainers.forEach(container => {
        const tabButtons = container.querySelectorAll('.tab-button');
        const tabPanes = container.querySelectorAll('.tab-pane');
        
        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');
                
                // Update active button
                tabButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                // Show active pane
                tabPanes.forEach(pane => {
                    pane.classList.remove('active');
                    if (pane.id === tabId) {
                        pane.classList.add('active');
                    }
                });
            });
        });
    });
}

/**
 * Initialize accordions
 */
function initAccordions() {
    const accordionHeaders = document.querySelectorAll('.accordion-header');
    
    accordionHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const accordion = this.parentElement;
            const content = this.nextElementSibling;
            
            // Toggle current accordion
            accordion.classList.toggle('active');
            
            // Close other accordions if needed
            if (accordion.classList.contains('active') && accordion.hasAttribute('data-close-others')) {
                document.querySelectorAll('.accordion.active').forEach(acc => {
                    if (acc !== accordion) {
                        acc.classList.remove('active');
                    }
                });
            }
        });
    });
}

/**
 * Initialize progress bars
 */
function initProgressBars() {
    const progressBars = document.querySelectorAll('.progress-bar');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const progress = entry.target.getAttribute('data-progress');
                if (progress) {
                    setTimeout(() => {
                        entry.target.style.width = progress + '%';
                    }, 100);
                }
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    progressBars.forEach(bar => observer.observe(bar));
}

/**
 * Initialize counters
 */
function initCounters() {
    const counters = document.querySelectorAll('.counter');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const target = +entry.target.getAttribute('data-target');
                const duration = 2000; // 2 seconds
                const step = target / (duration / 16); // 60fps
                let current = 0;
                
                const timer = setInterval(() => {
                    current += step;
                    if (current >= target) {
                        entry.target.textContent = formatNumber(target);
                        clearInterval(timer);
                    } else {
                        entry.target.textContent = formatNumber(Math.floor(current));
                    }
                }, 16);
                
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    counters.forEach(counter => observer.observe(counter));
}

// *********************************************
// FORM HANDLING & VALIDATION
// =============================================

/**
 * Setup form handling
 */
function setupFormHandling() {
    // Auto-save forms
    document.querySelectorAll('form[data-autosave]').forEach(form => {
        setupAutoSave(form);
    });

    // Character counters
    document.querySelectorAll('textarea[data-max-length]').forEach(textarea => {
        setupCharacterCounter(textarea);
    });

    // Password strength indicators
    document.querySelectorAll('input[type="password"][data-strength]').forEach(input => {
        setupPasswordStrength(input);
    });

    // File upload previews
    document.querySelectorAll('input[type="file"][data-preview]').forEach(input => {
        setupFilePreview(input);
    });
}

/**
 * Setup auto-save for forms
 */
function setupAutoSave(form) {
    let timeoutId;
    
    form.querySelectorAll('input, textarea, select').forEach(field => {
        field.addEventListener('input', debounce(function() {
            saveFormData(form);
        }, 1000));
    });

    // Load saved data on page load
    loadFormData(form);
}

/**
 * Save form data to localStorage
 */
function saveFormData(form) {
    const formData = new FormData(form);
    const data = {};
    
    formData.forEach((value, key) => {
        data[key] = value;
    });
    
    const formId = form.id || 'form-' + Math.random().toString(36).substr(2, 9);
    localStorage.setItem('form-' + formId, JSON.stringify(data));
    
    showAutoSaveIndicator();
}

/**
 * Load form data from localStorage
 */
function loadFormData(form) {
    const formId = form.id || 'form-' + Math.random().toString(36).substr(2, 9);
    const savedData = localStorage.getItem('form-' + formId);
    
    if (savedData) {
        const data = JSON.parse(savedData);
        Object.keys(data).forEach(key => {
            const field = form.querySelector(`[name="${key}"]`);
            if (field) {
                field.value = data[key];
            }
        });
        
        showNotification('Form data restored from previous session', 'info');
    }
}

/**
 * Setup character counter
 */
function setupCharacterCounter(textarea) {
    const maxLength = textarea.getAttribute('data-max-length');
    const counter = document.createElement('div');
    counter.className = 'character-counter';
    counter.style.fontSize = '0.8rem';
    counter.style.color = 'var(--text-light)';
    counter.style.textAlign = 'right';
    counter.style.marginTop = '0.25rem';
    
    textarea.parentNode.insertBefore(counter, textarea.nextSibling);
    
    function updateCounter() {
        const length = textarea.value.length;
        counter.textContent = `${length}/${maxLength}`;
        
        if (length > maxLength * 0.9) {
            counter.style.color = '#dc3545';
        } else if (length > maxLength * 0.75) {
            counter.style.color = '#ffc107';
        } else {
            counter.style.color = 'var(--text-light)';
        }
    }
    
    textarea.addEventListener('input', updateCounter);
    updateCounter();
}

/**
 * Setup password strength indicator
 */
function setupPasswordStrength(passwordInput) {
    const strengthMeter = document.createElement('div');
    strengthMeter.className = 'password-strength';
    strengthMeter.style.marginTop = '0.5rem';
    
    const strengthText = document.createElement('div');
    strengthText.style.fontSize = '0.8rem';
    strengthText.style.marginTop = '0.25rem';
    
    passwordInput.parentNode.insertBefore(strengthText, passwordInput.nextSibling);
    passwordInput.parentNode.insertBefore(strengthMeter, strengthText);
    
    function updateStrength() {
        const password = passwordInput.value;
        const strength = calculatePasswordStrength(password);
        
        strengthMeter.innerHTML = '';
        strengthMeter.style.height = '4px';
        strengthMeter.style.borderRadius = '2px';
        strengthMeter.style.background = '#e9ecef';
        strengthMeter.style.overflow = 'hidden';
        
        const strengthBar = document.createElement('div');
        strengthBar.style.height = '100%';
        strengthBar.style.transition = 'all 0.3s ease';
        strengthBar.style.width = strength.percentage + '%';
        strengthBar.style.background = strength.color;
        
        strengthMeter.appendChild(strengthBar);
        strengthText.textContent = strength.text;
        strengthText.style.color = strength.color;
    }
    
    passwordInput.addEventListener('input', updateStrength);
}

/**
 * Calculate password strength
 */
function calculatePasswordStrength(password) {
    let score = 0;
    
    if (password.length >= 8) score++;
    if (password.length >= 12) score++;
    if (/[a-z]/.test(password)) score++;
    if (/[A-Z]/.test(password)) score++;
    if (/[0-9]/.test(password)) score++;
    if (/[^A-Za-z0-9]/.test(password)) score++;
    
    const strengthLevels = [
        { percentage: 0, color: '#dc3545', text: 'Very Weak' },
        { percentage: 20, color: '#dc3545', text: 'Weak' },
        { percentage: 40, color: '#ffc107', text: 'Fair' },
        { percentage: 60, color: '#ffc107', text: 'Good' },
        { percentage: 80, color: '#28a745', text: 'Strong' },
        { percentage: 100, color: '#28a745', text: 'Very Strong' }
    ];
    
    return strengthLevels[Math.min(score, strengthLevels.length - 1)];
}

/**
 * Setup file preview
 */
function setupFilePreview(fileInput) {
    const previewContainer = document.createElement('div');
    previewContainer.className = 'file-preview';
    previewContainer.style.marginTop = '0.5rem';
    
    fileInput.parentNode.insertBefore(previewContainer, fileInput.nextSibling);
    
    fileInput.addEventListener('change', function() {
        previewContainer.innerHTML = '';
        
        if (this.files && this.files[0]) {
            const file = this.files[0];
            const reader = new FileReader();
            
            reader.onload = function(e) {
                if (file.type.startsWith('image/')) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.maxWidth = '200px';
                    img.style.maxHeight = '150px';
                    img.style.borderRadius = '8px';
                    previewContainer.appendChild(img);
                } else {
                    const fileInfo = document.createElement('div');
                    fileInfo.innerHTML = `
                        <div style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem; background: #f8f9fa; border-radius: 5px;">
                            <i class="fas fa-file" style="color: var(--primary);"></i>
                            <span>${file.name}</span>
                            <small style="color: var(--text-light);">(${formatFileSize(file.size)})</small>
                        </div>
                    `;
                    previewContainer.appendChild(fileInfo);
                }
            };
            
            reader.readAsDataURL(file);
        }
    });
}

// *********************************************
// NOTIFICATIONS & MESSAGING
// =============================================

/**
 * Show notification
 */
function showNotification(message, type = 'info', duration = 5000) {
    // Remove existing notifications of the same type
    document.querySelectorAll('.notification').forEach(notification => {
        if (notification.getAttribute('data-type') === type) {
            notification.remove();
        }
    });

    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.setAttribute('data-type', type);
    
    const icons = {
        success: 'fas fa-check-circle',
        error: 'fas fa-exclamation-circle',
        warning: 'fas fa-exclamation-triangle',
        info: 'fas fa-info-circle'
    };
    
    notification.innerHTML = `
        <div class="notification-content">
            <i class="${icons[type] || icons.info}"></i>
            <span>${message}</span>
        </div>
        <button class="notification-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    // Auto remove
    if (duration > 0) {
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 300);
        }, duration);
    }
    
    return notification;
}

/**
 * Show auto-save indicator
 */
function showAutoSaveIndicator() {
    let indicator = document.querySelector('.auto-save-indicator');
    
    if (!indicator) {
        indicator = document.createElement('div');
        indicator.className = 'auto-save-indicator';
        indicator.style.position = 'fixed';
        indicator.style.bottom = '20px';
        indicator.style.right = '20px';
        indicator.style.background = '#28a745';
        indicator.style.color = 'white';
        indicator.style.padding = '0.5rem 1rem';
        indicator.style.borderRadius = '20px';
        indicator.style.fontSize = '0.8rem';
        indicator.style.zIndex = '10000';
        indicator.style.opacity = '0';
        indicator.style.transition = 'opacity 0.3s ease';
        document.body.appendChild(indicator);
    }
    
    indicator.innerHTML = '<i class="fas fa-save"></i> Changes saved';
    indicator.style.opacity = '1';
    
    setTimeout(() => {
        indicator.style.opacity = '0';
    }, 2000);
}

/**
 * Show loading state
 */
function showLoading(message = 'Loading...') {
    let loader = document.querySelector('.global-loader');
    
    if (!loader) {
        loader = document.createElement('div');
        loader.className = 'global-loader';
        loader.innerHTML = `
            <div class="loader-overlay">
                <div class="loader-content">
                    <div class="loader-spinner"></div>
                    <div class="loader-text">${message}</div>
                </div>
            </div>
        `;
        document.body.appendChild(loader);
    }
    
    loader.style.display = 'block';
}

/**
 * Hide loading state
 */
function hideLoading() {
    const loader = document.querySelector('.global-loader');
    if (loader) {
        loader.style.display = 'none';
    }
}

/**
 * Initialize loading states
 */
function initLoadingStates() {
    // Add loading state to buttons on click
    document.addEventListener('click', function(e) {
        const button = e.target.closest('button[type="submit"], .btn[data-loading]');
        if (button && !button.disabled) {
            const originalText = button.innerHTML;
            button.innerHTML = `
                <i class="fas fa-spinner fa-spin"></i>
                ${button.getAttribute('data-loading-text') || 'Loading...'}
            `;
            button.disabled = true;
            
            // Store original content for restoration
            button.setAttribute('data-original-content', originalText);
            
            // Auto restore after 10 seconds (safety net)
            setTimeout(() => {
                if (button.disabled) {
                    restoreButton(button);
                }
            }, 10000);
        }
    });
}

/**
 * Restore button to original state
 */
function restoreButton(button) {
    const originalContent = button.getAttribute('data-original-content');
    if (originalContent) {
        button.innerHTML = originalContent;
        button.disabled = false;
        button.removeAttribute('data-original-content');
    }
}

// =============================================
// UTILITY FUNCTIONS
// =============================================

/**
 * Debounce function
 */
function debounce(func, wait, immediate) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            timeout = null;
            if (!immediate) func.apply(this, args);
        };
        const callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(this, args);
    };
}

/**
 * Format file size
 */
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

/**
 * Format number with commas
 */
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

/**
 * Initialize tooltips
 */
function initTooltips() {
    const tooltips = document.querySelectorAll('[data-tooltip]');
    
    tooltips.forEach(element => {
        element.addEventListener('mouseenter', showTooltip);
        element.addEventListener('mouseleave', hideTooltip);
    });
}

/**
 * Show tooltip
 */
function showTooltip(e) {
    const tooltipText = this.getAttribute('data-tooltip');
    const tooltip = document.createElement('div');
    tooltip.className = 'tooltip';
    tooltip.textContent = tooltipText;
    
    document.body.appendChild(tooltip);
    
    const rect = this.getBoundingClientRect();
    tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
    tooltip.style.top = (rect.top - tooltip.offsetHeight - 5) + 'px';
    
    this._currentTooltip = tooltip;
}

/**
 * Hide tooltip
 */
function hideTooltip() {
    if (this._currentTooltip) {
        this._currentTooltip.remove();
        this._currentTooltip = null;
    }
}

/**
 * Setup mobile detection
 */
function setupMobileDetection() {
    const checkMobile = () => {
        isMobile = window.innerWidth < 768;
        document.body.classList.toggle('is-mobile', isMobile);
        document.body.classList.toggle('is-desktop', !isMobile);
    };
    
    checkMobile();
    window.addEventListener('resize', debounce(checkMobile, 250));
}

/**
 * Handle window resize
 */
function handleResize() {
    // Close mobile menu on resize to desktop
    if (window.innerWidth >= 768) {
        const nav = document.querySelector('.nav-links');
        const body = document.body;
        
        if (nav && nav.classList.contains('active')) {
            nav.classList.remove('active');
            body.classList.remove('mobile-menu-open');
        }
    }
}

/**
 * Handle beforeunload for form protection
 */
function handleBeforeUnload(e) {
    const formsWithChanges = document.querySelectorAll('form[data-autosave]');
    let hasUnsavedChanges = false;
    
    formsWithChanges.forEach(form => {
        const formId = form.id || 'form-' + Math.random().toString(36).substr(2, 9);
        if (localStorage.getItem('form-' + formId)) {
            hasUnsavedChanges = true;
        }
    });
    
    if (hasUnsavedChanges) {
        e.preventDefault();
        e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
        return e.returnValue;
    }
}

/**
 * Handle global search
 */
function handleGlobalSearch(e) {
    const searchTerm = e.target.value.trim();
    
    if (searchTerm.length >= 2) {
        // Show search results dropdown
        showSearchResults(searchTerm);
    } else {
        // Hide search results
        hideSearchResults();
    }
}

/**
 * Show search results
 */
function showSearchResults(searchTerm) {
    let resultsDropdown = document.querySelector('.search-results-dropdown');
    
    if (!resultsDropdown) {
        resultsDropdown = document.createElement('div');
        resultsDropdown.className = 'search-results-dropdown';
        document.querySelector('.search-container').appendChild(resultsDropdown);
    }
    
    // Simulate search results (in real app, this would be an API call)
    const results = [
        { type: 'course', title: 'Web Development Fundamentals', url: '/courses/web-dev' },
        { type: 'course', title: 'Advanced JavaScript', url: '/courses/advanced-js' },
        { type: 'lesson', title: 'CSS Grid Layout', url: '/lessons/css-grid' },
        { type: 'instructor', title: 'John Doe', url: '/instructors/john-doe' }
    ].filter(item => 
        item.title.toLowerCase().includes(searchTerm.toLowerCase())
    );
    
    if (results.length > 0) {
        resultsDropdown.innerHTML = results.map(result => `
            <a href="${result.url}" class="search-result-item">
                <i class="fas fa-${getSearchResultIcon(result.type)}"></i>
                <span>${result.title}</span>
                <small>${result.type}</small>
            </a>
        `).join('');
        resultsDropdown.classList.add('active');
    } else {
        resultsDropdown.innerHTML = '<div class="search-no-results">No results found</div>';
        resultsDropdown.classList.add('active');
    }
}

/**
 * Hide search results
 */
function hideSearchResults() {
    const resultsDropdown = document.querySelector('.search-results-dropdown');
    if (resultsDropdown) {
        resultsDropdown.classList.remove('active');
    }
}

/**
 * Get search result icon
 */
function getSearchResultIcon(type) {
    const icons = {
        course: 'book',
        lesson: 'file-alt',
        instructor: 'user-tie',
        student: 'user-graduate'
    };
    return icons[type] || 'search';
}

// =============================================
// API HELPERS
// =============================================

/**
 * Make API request
 */
async function apiRequest(endpoint, options = {}) {
    const baseURL = window.location.origin;
    const url = endpoint.startsWith('http') ? endpoint : `${baseURL}/api${endpoint}`;
    
    const defaultOptions = {
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'include'
    };
    
    // Add auth token if available
    const token = localStorage.getItem('authToken');
    if (token) {
        defaultOptions.headers['Authorization'] = `Bearer ${token}`;
    }
    
    const finalOptions = { ...defaultOptions, ...options };
    
    try {
        const response = await fetch(url, finalOptions);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('API request failed:', error);
        throw error;
    }
}

/**
 * Upload file with progress
 */
async function uploadFile(file, endpoint, onProgress = null) {
    const formData = new FormData();
    formData.append('file', file);
    
    const xhr = new XMLHttpRequest();
    
    return new Promise((resolve, reject) => {
        xhr.upload.addEventListener('progress', (e) => {
            if (onProgress && e.lengthComputable) {
                const percentComplete = (e.loaded / e.total) * 100;
                onProgress(percentComplete);
            }
        });
        
        xhr.addEventListener('load', () => {
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    resolve(response);
                } catch (e) {
                    reject(new Error('Invalid JSON response'));
                }
            } else {
                reject(new Error(`Upload failed with status: ${xhr.status}`));
            }
        });
        
        xhr.addEventListener('error', () => {
            reject(new Error('Upload failed'));
        });
        
        xhr.open('POST', endpoint);
        
        // Add auth token if available
        const token = localStorage.getItem('authToken');
        if (token) {
            xhr.setRequestHeader('Authorization', `Bearer ${token}`);
        }
        
        xhr.send(formData);
    });
}

// *********************************************
// EXPORT FUNCTIONS FOR GLOBAL USE
// =============================================

// Make functions available globally
window.Learnify = {
    // Auth
    login: loginUser,
    logout: logoutUser,
    getCurrentUser: () => currentUser,
    
    // UI
    showNotification,
    showLoading,
    hideLoading,
    openModal,
    closeModal,
    
    // Utils
    debounce,
    formatFileSize,
    formatNumber,
    apiRequest,
    uploadFile,
    
    // Forms
    restoreButton,
    
    // Constants
    isMobile: () => isMobile
};

// Global error handler
window.addEventListener('error', function(e) {
    console.error('Global error:', e.error);
    showNotification('An unexpected error occurred', 'error');
});

// Unhandled promise rejection handler
window.addEventListener('unhandledrejection', function(e) {
    console.error('Unhandled promise rejection:', e.reason);
    showNotification('An unexpected error occurred', 'error');
    e.preventDefault();
});

console.log('Learnify main.js loaded successfully');
