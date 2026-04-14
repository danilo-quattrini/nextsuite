window.searchableSelect = () => ({
    open: false,
    searchQuery: '',
    searchTimer: null,
    hasValue: false,

    init() {
        /*
         * $nextTick ensures Livewire has hydrated the select value
         * before we check it (covers wire:model pre-fills on page load).
         */
        this.$nextTick(() => this.updateHasValue());
    },

    updateHasValue() {
        if (!this.$refs.select) return;
        this.hasValue = this.$refs.select.value !== '';
    },

    focusSelect() {
        if (!this.$refs.select) return;
        this.$refs.select.focus();
        if (typeof this.$refs.select.showPicker === 'function') {
            try {
                /*
                 * showPicker() throws if called outside a user gesture
                 * (e.g. programmatic calls). Wrap in try/catch so it
                 * silently falls back to .click() in those cases.
                 */
                this.$refs.select.showPicker();
            } catch {
                this.$refs.select.click();
            }
        } else {
            this.$refs.select.click();
        }
    },

    handleFocusIn() {
        this.open = true;
    },

    handleBlur(event) {
        /*
         * Only close if focus is leaving the entire wrapper element,
         * not just moving between children (e.g. wrapper div → select).
         * relatedTarget is null when focus leaves the page entirely,
         * which should also close the dropdown.
         */
        if (this.$el.contains(event.relatedTarget)) return;
        this.open = false;
    },

    handleChange() {
        this.open = false;
        /*
         * $nextTick so wire:model.defer and x-model have time to
         * commit the new value before we read it back.
         */
        this.$nextTick(() => this.updateHasValue());
    },

    handleTypeSearch(event) {
        if (!this.open || !this.$refs.select) return;
        if (event.altKey || event.ctrlKey || event.metaKey) return;
        if (event.key.length !== 1) return;

        if (document.activeElement !== this.$refs.select) {
            this.$refs.select.focus();
        }

        this.searchQuery += event.key.toLowerCase();
        clearTimeout(this.searchTimer);
        this.searchTimer = setTimeout(() => { this.searchQuery = ''; }, 500);

        const options = Array.from(this.$refs.select.options || []);
        const match = options.find(o => {
            if (o.disabled) return false;
            return o.text.toLowerCase().startsWith(this.searchQuery); /* ✅ startsWith is more precise than includes for type-ahead */
        });

        if (match) {
            this.$refs.select.value = match.value;
            this.$refs.select.dispatchEvent(new Event('change', { bubbles: true }));
        }
    }
});