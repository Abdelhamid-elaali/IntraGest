/**
 * Dashboard component for real-time updates
 * This component handles the real-time updates for the dashboard using Laravel Echo
 */
window.dashboard = function() {
    return {
        stats: {
            total_students: 0,
            available_rooms: 0,
            total_rooms: 0,
            recent_payments: 0
        },
        expenseStats: {
            supplies: 0,
            services: 0,
            other: 0
        },
        recentTransactions: [],
        refreshInterval: null,
        sidebarOpen: localStorage.getItem('sidebarState') === 'true',
        
        // Initialize the dashboard
        initDashboard() {
            // Set initial values from PHP
            this.stats = JSON.parse(document.getElementById('dashboard-stats').textContent);
            this.expenseStats = JSON.parse(document.getElementById('expense-stats').textContent);
            this.recentTransactions = JSON.parse(document.getElementById('recent-transactions').textContent);
            
            // Listen for real-time updates
            this.listenForUpdates();
            
            // Listen for sidebar toggle events
            window.addEventListener('sidebar-toggled', (event) => {
                this.sidebarOpen = event.detail.isOpen;
                // Redraw charts if they exist after sidebar toggle (to handle responsive resizing)
                setTimeout(() => {
                    if (window.expensesChart) {
                        window.expensesChart.update();
                    }
                }, 300); // Small delay to allow transition to complete
            });
            
            // Set up auto-refresh every 30 seconds as a fallback
            this.refreshInterval = setInterval(() => {
                this.fetchLatestStats();
            }, 30000);
        },
        
        // Listen for real-time updates via Laravel Echo
        listenForUpdates() {
            if (typeof window.Echo !== 'undefined') {
                window.Echo.channel('dashboard')
                    .listen('.stats.updated', (data) => {
                        this.updateDashboard(data);
                    });
            }
        },
        
        // Update dashboard with new data
        updateDashboard(data) {
            this.stats = data.stats;
            this.expenseStats = data.expenseStats;
            this.recentTransactions = data.recentTransactions;
                    
            // Update charts if they exist
            if (window.expensesChart) {
                window.expensesChart.data.datasets[0].data = [
                    this.expenseStats.supplies,
                    this.expenseStats.services,
                    this.expenseStats.other
                ];
                window.expensesChart.update();
            }
        },
        
        // Fetch the latest stats via AJAX
        fetchLatestStats() {
            fetch('/dashboard/stats')
                .then(response => response.json())
                .then(data => {
                    this.stats = data.stats;
                    this.expenseStats = data.expenseStats;
                    this.recentTransactions = data.recentTransactions;
                    
                    // Update charts if they exist
                    if (window.expensesChart) {
                        window.expensesChart.data.datasets[0].data = [
                            this.expenseStats.supplies,
                            this.expenseStats.services,
                            this.expenseStats.other
                        ];
                        window.expensesChart.update();
                    }
                })
                .catch(error => console.error('Error fetching dashboard stats:', error));
        },
        
        // Format number with commas
        formatNumber(number) {
            return new Intl.NumberFormat().format(number || 0);
        },
        
        // Format date for display
        formatDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric'
            });
        }
    };
}
