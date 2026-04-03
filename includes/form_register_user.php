<section>
    <a href="index.php">
        <button class="btn btn-danger mt-3">Voltar</button>
    </a>

    <h2 class="mt-3">Registro de Usuário:</h2>

    <form method="post">
        <div class="row g-2 mb-3">
            <div class="col-4">
                <label class="form-label">Nome</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="col-8">
                <label class="form-label">Sobrenome</label>
                <input type="text" name="surname" class="form-control" required>
            </div>
        </div>

        <div class="row g-2 mb-3">
            <div class="col-8">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="col-4">
                <label class="form-label">Telefone</label>
                <input type="tel" name="phone_n" class="phone-mask form-control" required>
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
                <select name="role" class="form-select" aria-label="Selecione a categoria do usuário" required>
                    <option selected disabled>Escolha a função do Usuário</option>
                    <option value="c">Cliente</option>
                    <option value="f">Funcionário</option>
                    <option value="a">Admin</option>
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

<script src="scripts/phoneMask.js"></script>
<script src="scripts/phoneSubmit.js"></script>