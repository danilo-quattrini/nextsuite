document.addEventListener('alpine:init', () => {

    Alpine.data('tableView', (state = 'table') => ({
        current: state,
    }));

    Alpine.store('views', {
        current: localStorage.getItem('tableView') || 'table',
        items: ['table', 'card'],

        set(view) {
            this.current = view;
            localStorage.setItem('tableView', view);
        },
    });
});
