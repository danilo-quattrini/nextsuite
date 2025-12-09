import.meta.glob(['/resources/fonts/**']);
import Alpine from 'alpinejs'
import intlTelInput from "intl-tel-input";
import "intl-tel-input/build/css/intlTelInput.css";
// Attach to all inputs with class .phone-input
document.addEventListener("DOMContentLoaded", () => {
    const phoneInputs = document.querySelectorAll(".phone-input");

    phoneInputs.forEach(input => {
        intlTelInput(input, {
            initialCountry: "it", // or "auto"
            utilsScript: "/utils.js",
        });
    });
});