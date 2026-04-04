<?php

    $alert_login    = strlen($alert_login) ? '<div class="alert alert-danger">' .$alert_login. '</div>' : '';
    $alert_register = strlen($alert_register) ? '<div class="alert alert-danger">' .$alert_register. '</div>' : '';

?>

<div class="container">
    <div class="row g-5">
        <div class="col">
            <form method="post">
                <div class="col">

                    <h2 class="text-center">Login</h2>

                    <?= $alert_login ?>

                    <div class="row mb-2">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>

                    <div class="row mb-2">
                        <label class="form-label">Senha</label>
                        <input type="password" class="form-control" name="pass" required>
                    </div>

                    <div class="row d-grid">
                        <button type="submit" name="action" value="login" class="btn btn-primary">Efetuar Login</button>
                    </div>
                </div>

            </form>
        </div>

        <div class="col">
            <form method="post">
                <input type="hidden" name="role" value="c">

                <div class="col">

                    <h2 class="text-center">Cadastrar-se</h2>

                    <?= $alert_register ?>

                    <div class="row mb-2">
                        <label class="form-label">Nome</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="row mb-2">
                        <label class="form-label">Sobrenome</label>
                        <input type="text" name="surname" class="form-control" required>
                    </div>

                    <div class="row mb-2">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="row mb-2">
                        <label class="form-label">Telefone</label>
                        <input type="tel" name="phone_n" class="phone-mask form-control" required>
                    </div>

                    <div class="row mb-2">
                        <label class="form-label">Senha</label>
                        <input type="password" name="pass" class="form-control" required>
                    </div>

                    <div class="row d-grid">
                        <button type="submit" name="action" value="register" class="btn btn-primary">Efetuar Registro</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Máscara
        const inputs = document.querySelectorAll('.phone-mask');
        inputs.forEach(input => {
            input.addEventListener('input', function() {
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
    // Remove máscara antes de enviar
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            form.querySelectorAll('.phone-mask').forEach(input => {
                input.value = input.value.replace(/\D/g, '');
            });
        });
    });
</script>