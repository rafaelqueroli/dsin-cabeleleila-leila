<section>
    <div class="d-flex align-items-center gap-3 mt-3 mb-4">
        <a href="/minha-conta">
            <button class="btn btn-danger"><i class="bi bi-arrow-left-circle"></i> Voltar</button>
        </a>
        <h3 class="mb-0"><?= TITLE ?></h3>
    </div>

    <?php if (isset($alert) && $alert === 'error-old'): ?>
        <div class="alert alert-danger mb-4">
            <i class="bi bi-x-circle me-2"></i> Senha atual incorreta.
        </div>
    <?php elseif (isset($alert) && $alert === 'error-match'): ?>
        <div class="alert alert-danger mb-4">
            <i class="bi bi-x-circle me-2"></i> A nova senha e a confirmação não coincidem.
        </div>
    <?php endif; ?>

    <form method="post" action="/minha-conta/senha">
        <div class="row g-2 mb-3">
            <div class="col-12 col-md-6">
                <label class="form-label">Senha Atual</label>
                <input type="password" name="pass_old" class="form-control" required>
            </div>
        </div>

        <div class="row g-2 mb-3">
            <div class="col-12 col-md-6">
                <label class="form-label">Nova Senha</label>
                <input type="password" name="pass_new" class="form-control" required>
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label">Confirmar Nova Senha</label>
                <input type="password" name="pass_confirm" class="form-control" required>
            </div>
        </div>

        <div class="row g-2">
            <div class="col d-grid">
                <button class="btn btn-primary" type="submit">Alterar Senha</button>
            </div>
        </div>
    </form>
</section>