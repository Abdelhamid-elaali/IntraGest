import './bootstrap';
import './search';

import Alpine from 'alpinejs';

// Dark mode functionality
document.addEventListener('alpine:init', () => {
    Alpine.store('darkMode', {
        on: localStorage.getItem('darkMode') === 'true',
        toggle() {
            this.on = !this.on;
            localStorage.setItem('darkMode', this.on);
            this.updateClasses();
        },
        updateClasses() {
            if (this.on) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        },
        init() {
            this.updateClasses();
            // Listen for changes from settings page
            window.addEventListener('dark-mode-changed', (event) => {
                this.on = event.detail;
                localStorage.setItem('darkMode', this.on);
                this.updateClasses();
            });
        }
    });
});

window.Alpine = Alpine;
Alpine.start();
