import intlTelInput from 'intl-tel-input';
import 'intl-tel-input/build/css/intlTelInput.css';
import utilsScriptUrl from 'intl-tel-input/build/js/utils.js?url';

let iti = null;

function initIntlTelInput() {
    const display = document.getElementById('phone_display');
    const hidden = document.getElementById('phone');

    if (!display || !hidden) {
        return;
    }

    // destroy previous instance if exists
    if (iti && typeof iti.destroy === 'function') {
        try { iti.destroy(); } catch (e) { /* ignore */ }
        iti = null;
    }

    if (typeof intlTelInput === 'undefined') {
        console.error('intlTelInput not found. Ensure the library is loaded.');
        return;
    }

    iti = intlTelInput(display, {
        utilsScript: utilsScriptUrl,
        separateDialCode: false,
        initialCountry: 'auto',
        geoIpLookup: function (callback) {
            callback('it'); // optional: implement real geoIP if desired
        },
    });

    // if Livewire already has a value, set it so iti shows it correctly
    if (hidden.value) {
        try { iti.setNumber(hidden.value); } catch (e) { /* ignore */ }
    }

    function syncToHidden() {
        try {
            const e164 = iti.getNumber();
            hidden.value = e164 || display.value || '';
        } catch (e) {
            hidden.value = display.value || '';
        }
        hidden.dispatchEvent(new Event('input', { bubbles: true }));
    }

    if (display.value && !hidden.value) {
        syncToHidden();
    }

    if (display.dataset.itiBound !== 'true') {
        display.addEventListener('input', syncToHidden);
        display.addEventListener('countrychange', syncToHidden);
        display.addEventListener('blur', syncToHidden);
        display.addEventListener('change', syncToHidden);
        display.addEventListener('keyup', syncToHidden);
        display.dataset.itiBound = 'true';
    }
}

// auto-init on Livewire + DOM lifecycle
document.addEventListener('livewire:init', () => {
    initIntlTelInput();

    if (window.Livewire && window.Livewire.hook) {
        window.Livewire.hook('message.processed', () => {
            initIntlTelInput();
        });
    }
});

// also init on DOMContentLoaded for non-livewire preview pages
document.addEventListener('DOMContentLoaded', () => {
    initIntlTelInput();
});

export { initIntlTelInput };