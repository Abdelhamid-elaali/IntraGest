// Shared state for trainee count
const TraineeCounter = {
    count: 0,
    subscribers: [],
    
    // Initialize with the current count
    init(count) {
        this.count = count;
        this.notifySubscribers();
    },
    
    // Update the count
    updateCount(newCount) {
        if (this.count !== newCount) {
            this.count = newCount;
            this.notifySubscribers();
            // Save to localStorage for cross-tab synchronization
            if (typeof localStorage !== 'undefined') {
                localStorage.setItem('traineeCount', this.count);
            }
        }
    },
    
    // Subscribe to count changes
    subscribe(callback) {
        this.subscribers.push(callback);
        // Return unsubscribe function
        return () => {
            this.subscribers = this.subscribers.filter(sub => sub !== callback);
        };
    },
    
    // Notify all subscribers
    notifySubscribers() {
        this.subscribers.forEach(callback => callback(this.count));
    },
    
    // Get current count
    getCount() {
        return this.count;
    }
};

// Listen for storage events to sync across tabs
if (typeof window !== 'undefined') {
    window.addEventListener('storage', (event) => {
        if (event.key === 'traineeCount') {
            const newCount = parseInt(event.newValue, 10);
            if (!isNaN(newCount)) {
                TraineeCounter.updateCount(newCount);
            }
        }
    });
}

export default TraineeCounter;
