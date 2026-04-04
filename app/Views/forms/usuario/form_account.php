<section>
    <div class="d-flex align-items-center gap-3 mt-3 mb-4">
        <a href="/">
            <button class="btn btn-danger"><i class="bi bi-arrow-left-circle"></i> Voltar</button>
        </a>
        <h3 class="mb-0"><?= TITLE ?></h3>
    </div>

    <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
        <div class="alert alert-success mb-4">
            <i class="bi bi-check-circle me-2"></i> Dados atualizados com sucesso!
        </div>
    <?php endif; ?>

    <?php if (isset($alert) && $alert === 'error-pass'): ?>
        <div class="alert alert-danger mb-4">
            <i class="bi bi-x-circle me-2"></i> Senha incorreta. Tente novamente.
        </div>
    <?php endif; ?>

    <form method="post" action="/minha-conta">
        <div class="row g-2 mb-3">
            <div class="col-4">
                <label class="form-label">Nome</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($objUsuario->name) ?>" required>
            </div>
            <div class="col-8">
                <label class="form-label">Sobrenome</label>
                <input type="text" name="surname" class="form-control" value="<?= htmlspecialchars($objUsuario->surname) ?>" required>
            </div>
        </div>

        <div class="row g-2 mb-3">
            <!-- E-mail exibido como somente leitura — não pode ser alterado pelo usuário -->
            <div class="col-8">
                <label class="form-label">Email <span class="text-muted small">(não editável)</span></label>
                <input type="email" class="form-control" value="<?= htmlspecialchars($objUsuario->email) ?>" disabled>
            </div>
            <div class="col-4">
                <label class="form-label">Telefone</label>
                <input type="tel" name="phone_n" class="phone-mask form-control" value="<?= htmlspecialchars($objUsuario->phone_n) ?>" required>
            </div>
        </div>

        <div class="row g-2 mb-4">
            <!-- Confirmação de senha obrigatória para salvar qualquer alteração -->
            <div class="col">
                <label class="form-label">Confirmar com sua senha atual</label>
                <input type="password" name="pass_confirm" class="form-control" required>
            </div>
        </div>

        <div class="row g-2 mb-3">
            <div class="col d-grid">
                <button class="btn btn-primary" type="submit">Salvar Alterações</button>
            </div>
        </div>
    </form>

    <hr>

    <!-- Atalho para alterar senha -->
    <div class="d-flex align-items-center justify-content-between mt-3">
        <span class="text-muted">Quer alterar sua senha?</span>
        <a href="/minha-conta/senha" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-key me-1"></i> Alterar Senha
        </a>
    </div>
</section>

<script>
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

        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                form.querySelectorAll('.phone-mask').forEach(input => {
                    input.value = input.value.replace(/\D/g, '');
                });
            });
        });
    });
</script>