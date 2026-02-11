import Tooltip from "@ryangjchandler/alpine-tooltip";

document.addEventListener('alpine:init', () => {
    Alpine.plugin(Tooltip.defaultProps({
        theme: 'light',
        arrow: false,
    }));
});
