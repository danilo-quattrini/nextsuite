window.searchableSelect = () => ({
    open: false,
    searchQuery: '',
    searchTimer: null,
    hasValue: false,
    init() {
        this.updateHasValue();
    },
    updateHasValue() {
        if (!this.$refs.select) return;
        this.hasValue = this.$refs.select.value !== '';
    },
    focusSelect() {
        if (!this.$refs.select) return;
        this.$refs.select.focus();
        if (typeof this.$refs.select.showPicker === 'function') {
            this.$refs.select.showPicker();
        } else {
            this.$refs.select.click();
        }
    },
    handleFocusIn() {
        this.open = true;
    },
    handleBlur() {
        this.open = false;
    },
    handleChange() {
        this.open = false;
        this.updateHasValue();
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
        const match = options.find(option => {
            if (option.disabled) return false;
            return option.text.toLowerCase().includes(this.searchQuery);
        });

        if (match) {
            this.$refs.select.value = match.value;
            this.$refs.select.dispatchEvent(new Event('change', { bubbles: true }));
        }
    }
});
