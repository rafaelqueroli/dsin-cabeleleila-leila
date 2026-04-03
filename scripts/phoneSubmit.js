document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');

    form.addEventListener('submit', function () {
        const inputs = document.querySelectorAll('.phone-mask');

        inputs.forEach(input => {
            input.value = input.value.replace(/\D/g, '');
        });
    });
});