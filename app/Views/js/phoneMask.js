document.addEventListener('DOMContentLoaded', function () {
    const inputs = document.querySelectorAll('.phone-mask');

    inputs.forEach(input => {
        input.addEventListener('input', function () {
            let value = input.value.replace(/\D/g, '');

            if (value.length > 11) value = value.slice(0, 11);

            if (value.length > 6) {
                value = value.replace(/(\d{2})(\d{5})(\d{0,4})/, '($1) $2-$3');
            } else if (value.length > 2) {
                value = value.replace(/(\d{2})(\d{0,5})/, '($1) $2');
            } else if (value.length > 0) {
                value = value.replace(/(\d*)/, '($1');
            }

            input.value = value;
        });
    });
});