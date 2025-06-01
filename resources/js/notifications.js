/**
 * IntraGest Notification System
 * Handles real-time notifications and notification interactions
 */

// Initialize notification functionality when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    initNotifications();
});

/**
 * Initialize the notification system
 */
function initNotifications() {
    // Setup notification dropdown toggle
    setupNotificationDropdown();
    
    // Setup mark as read functionality
    setupMarkAsReadButtons();
    
    // Setup mark all as read functionality
    setupMarkAllAsReadButton();
    
    // Setup real-time notifications if Echo is available
    setupRealTimeNotifications();
}

/**
 * Setup the notification dropdown toggle behavior
 */
function setupNotificationDropdown() {
    const dropdown = document.getElementById('notification-dropdown');
    const button = document.getElementById('notification-dropdown-button');
    
    if (!dropdown || !button) return;
    
    button.addEventListener('click', (e) => {
        e.preventDefault();
        dropdown.classList.toggle('hidden');
        
        // If dropdown is now visible, fetch latest notifications
        if (!dropdown.classList.contains('hidden')) {
            fetchNotifications();
        }
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', (e) => {
        if (!dropdown.classList.contains('hidden') && 
            !dropdown.contains(e.target) && 
            !button.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });
}

/**
 * Fetch the latest notifications from the server
 */
function fetchNotifications() {
    const container = document.getElementById('notification-list');
    const countBadge = document.getElementById('notification-count');
    
    if (!container) return;
    
    // Show loading state
    container.innerHTML = '<div class="p-4 text-center text-gray-500">Loading notifications...</div>';
    
    fetch('/api/notifications/recent')
        .then(response => response.json())
        .then(data => {
            updateNotificationList(data.notifications);
            updateNotificationCount(data.unread_count);
        })
        .catch(error => {
            console.error('Error fetching notifications:', error);
            container.innerHTML = '<div class="p-4 text-center text-red-500">Failed to load notifications</div>';
        });
}

/**
 * Update the notification list in the dropdown
 * 
 * @param {Array} notifications List of notification objects
 */
function updateNotificationList(notifications) {
    const container = document.getElementById('notification-list');
    if (!container) return;
    
    // Clear the container
    container.innerHTML = '';
    
    if (notifications.length === 0) {
        container.innerHTML = '<div class="p-4 text-center text-gray-500">No notifications</div>';
        return;
    }
    
    // Add each notification to the list
    notifications.forEach(notification => {
        const notificationEl = document.createElement('div');
        notificationEl.className = `p-4 border-b border-gray-200 ${!notification.read_at ? 'bg-blue-50' : ''}`;
        notificationEl.innerHTML = `
            <div class="flex items-start">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">${notification.data.title || 'Notification'}</p>
                    <p class="text-xs text-gray-500">${notification.data.message || ''}</p>
                    <p class="text-xs text-gray-400 mt-1">${formatDate(notification.created_at)}</p>
                </div>
                ${!notification.read_at ? `
                <button data-notification-id="${notification.id}" class="mark-as-read text-xs text-blue-600 hover:text-blue-800">
                    Mark as read
                </button>
                ` : ''}
            </div>
        `;
        container.appendChild(notificationEl);
    });
    
    // Add "View All" link
    const viewAllLink = document.createElement('a');
    viewAllLink.href = '/notifications';
    viewAllLink.className = 'block text-center text-sm text-blue-600 hover:text-blue-800 p-2';
    viewAllLink.textContent = 'View all notifications';
    container.appendChild(viewAllLink);
    
    // Setup mark as read buttons for the new notifications
    setupMarkAsReadButtons();
}

/**
 * Update the notification count badge
 * 
 * @param {number} count Number of unread notifications
 */
function updateNotificationCount(count) {
    const countBadge = document.getElementById('notification-count');
    if (!countBadge) return;
    
    if (count > 0) {
        countBadge.textContent = count > 99 ? '99+' : count;
        countBadge.classList.remove('hidden');
    } else {
        countBadge.classList.add('hidden');
    }
}

/**
 * Setup click handlers for mark as read buttons
 */
function setupMarkAsReadButtons() {
    document.querySelectorAll('.mark-as-read').forEach(button => {
        button.addEventListener('click', function() {
            const notificationId = this.dataset.notificationId;
            markAsRead(notificationId, this);
        });
    });
}

/**
 * Setup click handler for mark all as read button
 */
function setupMarkAllAsReadButton() {
    const markAllButton = document.getElementById('markAllAsRead');
    if (markAllButton) {
        markAllButton.addEventListener('click', markAllAsRead);
    }
}

/**
 * Mark a notification as read
 * 
 * @param {string} id Notification ID
 * @param {HTMLElement} button The button element that was clicked
 */
function markAsRead(id, button) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch('/notifications/mark-as-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ id: id })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update UI
            const notificationEl = button.closest('div.flex').parentElement;
            notificationEl.classList.remove('bg-blue-50');
            button.remove();
            
            // Update count
            const countBadge = document.getElementById('notification-count');
            if (countBadge && !countBadge.classList.contains('hidden')) {
                const currentCount = parseInt(countBadge.textContent);
                updateNotificationCount(currentCount - 1);
            }
        }
    })
    .catch(error => {
        console.error('Error marking notification as read:', error);
    });
}

/**
 * Mark all notifications as read
 */
function markAllAsRead() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch('/notifications/mark-all-as-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update UI - remove all mark as read buttons and blue backgrounds
            document.querySelectorAll('.mark-as-read').forEach(button => {
                const notificationEl = button.closest('div.flex').parentElement;
                notificationEl.classList.remove('bg-blue-50');
                button.remove();
            });
            
            // Update count to zero
            updateNotificationCount(0);
            
            // If on notifications page, update all notifications
            document.querySelectorAll('.bg-blue-50').forEach(el => {
                el.classList.remove('bg-blue-50', 'border-blue-200');
                el.classList.add('bg-gray-50', 'border-gray-200');
            });
            
            // Hide the mark all as read button on the notifications page
            const pageMarkAllButton = document.querySelector('button[onclick="markAllAsRead()"]');
            if (pageMarkAllButton) {
                pageMarkAllButton.remove();
            }
        }
    })
    .catch(error => {
        console.error('Error marking all notifications as read:', error);
    });
}

/**
 * Setup real-time notifications using Laravel Echo
 */
function setupRealTimeNotifications() {
    if (typeof window.Echo !== 'undefined') {
        // Get the current user ID from the page
        const userId = document.body.dataset.userId;
        
        if (userId) {
            // Listen for notifications on the user's private channel
            window.Echo.private(`App.Models.User.${userId}`)
                .notification((notification) => {
                    // Show a toast notification
                    showNotificationToast(notification);
                    
                    // Update the notification count
                    const countBadge = document.getElementById('notification-count');
                    if (countBadge) {
                        const currentCount = countBadge.classList.contains('hidden') ? 0 : parseInt(countBadge.textContent);
                        updateNotificationCount(currentCount + 1);
                    }
                    
                    // If the dropdown is open, refresh the list
                    const dropdown = document.getElementById('notification-dropdown');
                    if (dropdown && !dropdown.classList.contains('hidden')) {
                        fetchNotifications();
                    }
                });
        }
    }
}

/**
 * Show a toast notification
 * 
 * @param {Object} notification The notification object
 */
function showNotificationToast(notification) {
    // Create toast container if it doesn't exist
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.className = 'fixed top-4 right-4 z-50 space-y-4';
        document.body.appendChild(toastContainer);
    }
    
    // Create toast element
    const toast = document.createElement('div');
    toast.className = 'bg-white dark:bg-gray-800 shadow-lg rounded-lg p-4 max-w-sm w-full transform transition-all duration-300 opacity-0 translate-x-8';
    toast.innerHTML = `
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
            </div>
            <div class="ml-3 w-0 flex-1">
                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">${notification.data.title || 'New Notification'}</p>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">${notification.data.message || ''}</p>
            </div>
            <div class="ml-4 flex-shrink-0 flex">
                <button class="bg-white dark:bg-gray-800 rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none">
                    <span class="sr-only">Close</span>
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    `;
    
    // Add to container
    toastContainer.appendChild(toast);
    
    // Animate in
    setTimeout(() => {
        toast.classList.remove('opacity-0', 'translate-x-8');
    }, 10);
    
    // Close button functionality
    toast.querySelector('button').addEventListener('click', () => {
        removeToast(toast);
    });
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        removeToast(toast);
    }, 5000);
}

/**
 * Remove a toast with animation
 * 
 * @param {HTMLElement} toast The toast element to remove
 */
function removeToast(toast) {
    toast.classList.add('opacity-0', 'translate-x-8');
    setTimeout(() => {
        toast.remove();
    }, 300);
}

/**
 * Format a date for display
 * 
 * @param {string} dateString The date string to format
 * @returns {string} The formatted date
 */
function formatDate(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diffInSeconds = Math.floor((now - date) / 1000);
    
    if (diffInSeconds < 60) {
        return 'Just now';
    }
    
    const diffInMinutes = Math.floor(diffInSeconds / 60);
    if (diffInMinutes < 60) {
        return `${diffInMinutes} minute${diffInMinutes !== 1 ? 's' : ''} ago`;
    }
    
    const diffInHours = Math.floor(diffInMinutes / 60);
    if (diffInHours < 24) {
        return `${diffInHours} hour${diffInHours !== 1 ? 's' : ''} ago`;
    }
    
    const diffInDays = Math.floor(diffInHours / 24);
    if (diffInDays < 7) {
        return `${diffInDays} day${diffInDays !== 1 ? 's' : ''} ago`;
    }
    
    return date.toLocaleDateString();
}

// Make functions available globally
window.markAsRead = markAsRead;
window.markAllAsRead = markAllAsRead;
