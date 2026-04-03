<section>
    <div class="d-flex align-items-center gap-3 mt-3 mb-4">
        <a href="javascript:history.back()">
            <button class="btn btn-danger"><i class="bi bi-arrow-left-circle"></i> Início</button>
        </a>
        <h3 class="mb-0"><?= TITLE ?></h3>
    </div>

    <form method="post">
        <div class="row g-2 mb-3">
            <div class="col-4">
                <label class="form-label">Nome</label>
                <input type="text" name="name" class="form-control" value="<?= $objUsuario->name ?>" required>
            </div>

            <div class="col-8">
                <label class="form-label">Sobrenome</label>
                <input type="text" name="surname" class="form-control" value="<?= $objUsuario->surname ?>" required>
            </div>
        </div>

        <div class="row g-2 mb-3">
            <div class="col-8">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?= $objUsuario->email ?>" required>
            </div>
            <div class="col-4">
                <label class="form-label">Telefone</label>
                <input type="tel" name="phone_n" class="phone-mask form-control" value="<?= $objUsuario->phone_n ?>" required>
            </div>
        </div>

        <div class="row g-2 mb-3">
            <div class="col">
                <label class="form-label">Senha</label>
                <input type="password" name="pass" class="form-control" required>
            </div>
        </div>

        <div class="row g-2 mb-3">
            <div class="col">
                <label class="form-label">Função</label>
                <select name="role" class="form-select" required>
                    <option disabled>Escolha a função do Usuário</option>
                    <option value="c" <?= $objUsuario->role == 'c' ? 'selected' : '' ?>>Cliente</option>
                    <option value="f" <?= $objUsuario->role == 'f' ? 'selected' : '' ?>>Funcionário</option>
                    <option value="a" <?= $objUsuario->role == 'a' ? 'selected' : '' ?>>Admin</option>
                </select>
            </div>
        </div>

        <div class="row g-2">
            <div class="col d-grid">
                <button class="btn btn-primary" type="submit">Enviar</button>
            </div>
        </div>
    </form>
</section>