/**
 * Admin Dashboard JavaScript Utilities
 * Provides unified error handling and AJAX operations for admin panel
 */

class AdminUtils {
    constructor() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                        document.querySelector('input[name="_token"]')?.value;
    }

    /**
     * Make an AJAX request with proper error handling
     * @param {string} url - The URL to make the request to
     * @param {Object} options - Fetch options
     * @returns {Promise} - Promise that resolves with parsed response
     */
    async makeRequest(url, options = {}) {
        const defaultOptions = {
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.csrfToken,
                'Accept': 'application/json'
            }
        };

        const mergedOptions = { ...defaultOptions, ...options };
        
        // Merge headers properly
        if (options.headers) {
            mergedOptions.headers = { ...defaultOptions.headers, ...options.headers };
        }

        try {
            const response = await fetch(url, mergedOptions);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            // Check if response is JSON or HTML redirect
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                const data = await response.json();
                return { success: true, data };
            } else {
                // It's likely a redirect or HTML response
                return { success: true, data: null, isRedirect: true };
            }
        } catch (error) {
            console.error('AdminUtils Request Error:', error);
            throw error;
        }
    }

    /**
     * Delete an item with confirmation
     * @param {string} url - Delete URL
     * @param {string} itemName - Name of item being deleted (for confirmation)
     * @param {Function} onSuccess - Success callback
     * @param {Function} onError - Error callback
     */
    async deleteItem(url, itemName = 'item', onSuccess = null, onError = null) {
        if (!confirm(`Are you sure you want to delete this ${itemName}?`)) {
            return;
        }

        try {
            const result = await this.makeRequest(url, {
                method: 'DELETE'
            });

            if (result.success) {
                if (onSuccess) {
                    onSuccess(result.data);
                } else {
                    this.showNotification(`${itemName} deleted successfully!`, 'success');
                    location.reload();
                }
            } else {
                throw new Error(result.data?.message || 'Unknown error');
            }
        } catch (error) {
            console.error('Delete Error:', error);
            if (onError) {
                onError(error);
            } else {
                this.showNotification(`Error deleting ${itemName}`, 'error');
            }
        }
    }

    /**
     * Toggle status of an item
     * @param {string} url - Toggle URL
     * @param {Function} onSuccess - Success callback
     * @param {Function} onError - Error callback
     */
    async toggleStatus(url, onSuccess = null, onError = null) {
        try {
            const result = await this.makeRequest(url, {
                method: 'POST'
            });

            if (result.success) {
                if (onSuccess) {
                    onSuccess(result.data);
                } else {
                    this.showNotification('Status updated successfully!', 'success');
                    location.reload();
                }
            } else {
                throw new Error(result.data?.message || 'Unknown error');
            }
        } catch (error) {
            console.error('Toggle Status Error:', error);
            if (onError) {
                onError(error);
            } else {
                this.showNotification('Error updating status', 'error');
            }
        }
    }

    /**
     * Update position/order of items
     * @param {string} url - Update URL
     * @param {Object} data - Data to send
     * @param {Function} onSuccess - Success callback
     * @param {Function} onError - Error callback
     */
    async updatePosition(url, data, onSuccess = null, onError = null) {
        try {
            const result = await this.makeRequest(url, {
                method: 'POST',
                body: JSON.stringify(data)
            });

            if (result.success) {
                if (onSuccess) {
                    onSuccess(result.data);
                } else {
                    this.showNotification('Order updated successfully!', 'success');
                    location.reload();
                }
            } else {
                throw new Error(result.data?.message || 'Unknown error');
            }
        } catch (error) {
            console.error('Update Position Error:', error);
            if (onError) {
                onError(error);
            } else {
                this.showNotification('Error updating order', 'error');
            }
        }
    }

    /**
     * Show notification to user
     * @param {string} message - Notification message
     * @param {string} type - Notification type (success, error, warning, info)
     */
    showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm transform transition-all duration-300 translate-x-full`;
        
        // Set colors based on type
        const colors = {
            success: 'bg-green-500 text-white',
            error: 'bg-red-500 text-white',
            warning: 'bg-yellow-500 text-white',
            info: 'bg-blue-500 text-white'
        };
        
        notification.className += ` ${colors[type] || colors.info}`;
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${this.getIconForType(type)} mr-2"></i>
                <span>${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;

        document.body.appendChild(notification);

        // Animate in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);

        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 300);
        }, 5000);
    }

    /**
     * Get icon for notification type
     * @param {string} type - Notification type
     * @returns {string} - Font Awesome icon class
     */
    getIconForType(type) {
        const icons = {
            success: 'fa-check-circle',
            error: 'fa-exclamation-circle',
            warning: 'fa-exclamation-triangle',
            info: 'fa-info-circle'
        };
        return icons[type] || icons.info;
    }

    /**
     * Handle form submission with loading state
     * @param {HTMLFormElement} form - Form element
     * @param {string} url - Submission URL
     * @param {Object} options - Additional options
     */
    async handleFormSubmission(form, url, options = {}) {
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Show loading state
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
        submitBtn.disabled = true;

        try {
            const formData = new FormData(form);
            const result = await this.makeRequest(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken
                }
            });

            if (result.success) {
                this.showNotification('Operation completed successfully!', 'success');
                if (options.onSuccess) {
                    options.onSuccess(result.data);
                } else {
                    form.reset();
                }
            } else {
                throw new Error(result.data?.message || 'Unknown error');
            }
        } catch (error) {
            console.error('Form Submission Error:', error);
            this.showNotification('Error: ' + error.message, 'error');
            if (options.onError) {
                options.onError(error);
            }
        } finally {
            // Reset button state
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    }
}

// Create global instance
window.AdminUtils = new AdminUtils();
console.log('AdminUtils loaded successfully');

// Ensure AdminUtils is available immediately
document.addEventListener('DOMContentLoaded', function() {
    if (!window.AdminUtils) {
        window.AdminUtils = new AdminUtils();
        console.log('AdminUtils re-initialized on DOMContentLoaded');
    }
});

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = AdminUtils;
}
