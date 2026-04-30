<?php

$alert_login    = strlen($alert_login) ? '<div class="alert alert-danger">' . $alert_login . '</div>' : '';
$alert_register = strlen($alert_register) ? '<div class="alert alert-danger">' . $alert_register . '</div>' : '';

?>

<main class="container py-5">
    <div class="row g-4 justify-content-center">
        
        <div class="col-md-5">
            <div class="card shadow-sm h-100">
                <div class="card-body p-4">
                    <form method="post">
                        <h2 class="text-center mb-4 fw-bold text-primary">Login</h2>

                        <?= $alert_login ?>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" class="form-control form-control-lg" name="email" placeholder="seu@email.com" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Senha</label>
                            <input type="password" class="form-control form-control-lg" name="pass" placeholder="••••••••" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" name="action" value="login" class="btn btn-primary btn-lg">
                                <i class="bi bi-box-arrow-in-right"></i> Efetuar Login
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm border-light">
                <div class="card-body p-4">
                    <form method="post">
                        <input type="hidden" name="role" value="c">

                        <h2 class="text-center mb-4 fw-bold text-secondary">Cadastrar-se</h2>

                        <?= $alert_register ?>

                        <div class="row g-2">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Nome</label>
                                <input type="text" name="name" class="form-control" placeholder="Ex: João" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Sobrenome</label>
                                <input type="text" name="surname" class="form-control" placeholder="Ex: Silva" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="seu@email.com" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Telefone</label>
                            <input type="tel" name="phone_n" class="phone-mask form-control" placeholder="(00) 00000-0000" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Senha</label>
                            <input type="password" name="pass" class="form-control" placeholder="••••••••" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" name="action" value="register" class="btn btn-outline-primary btn-lg">
                                <i class="bi bi-person-plus-fill"></i> Criar Minha Conta
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <script>
        // Adiciona á máscara na hora de digitar o número do telefone
        document.addEventListener('DOMContentLoaded', function() {
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

        // Remove a máscara para enviar o número de telefone para a tabela
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                form.querySelectorAll('.phone-mask').forEach(input => {
                    input.value = input.value.replace(/\D/g, '');
                });
            });
        });
    </script>
</main>