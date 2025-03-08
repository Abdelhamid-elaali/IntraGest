// Check for system dark mode preference
const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

// Get saved preference or use system preference
const savedTheme = localStorage.getItem('darkMode');
const initialDarkMode = savedTheme !== null ? savedTheme === 'true' : systemPrefersDark;

// Set initial theme
if (initialDarkMode) {
    document.documentElement.classList.add('dark');
} else {
    document.documentElement.classList.remove('dark');
}

// Listen for system theme changes
window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
    if (localStorage.getItem('darkMode') === null) {
        if (e.matches) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }
});
