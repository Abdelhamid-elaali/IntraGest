import './bootstrap';
import Alpine from 'alpinejs/dist/module.esm.js';

// Dark mode functionality
document.addEventListener('alpine:init', () => {
    Alpine.data('darkMode', () => ({
        dark: localStorage.getItem('darkMode') === 'true',
        init() {
            if (this.dark) {
                document.documentElement.classList.add('dark');
            }
        },
        toggle() {
            this.dark = !this.dark;
            localStorage.setItem('darkMode', this.dark);
            if (this.dark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
    }));
});

window.Alpine = Alpine;
Alpine.start();
