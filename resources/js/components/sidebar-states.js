document.addEventListener('alpine:init', () => {
    Alpine.store('sidebar', {
        current: JSON.parse(localStorage.getItem('sidebarCollapsed') || 'false'),

        set(value) {
            this.current = Boolean(value);
            localStorage.setItem('sidebarCollapsed', JSON.stringify(this.current));
        },

        toggle() {
            this.set(!this.current);
        },
    });

    Alpine.data('sidebarCollapsed', () => ({
        get collapsed() {
            return Alpine.store('sidebar').current;
        },
        set collapsed(value) {
            Alpine.store('sidebar').set(value);
        },
    }));
});
