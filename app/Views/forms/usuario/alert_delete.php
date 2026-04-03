<section class="container">
    <h2 class="mt-3 text-center">Excluir Usuário</h2>

    <form method="post">

        <div class="row g-2">
            <div class="col">
                <p class="text-center">Você deseja realmente excluir o usuário <strong><?= $objUsuario->name . ' ' . $objUsuario->surname ?></strong></p>
            </div>
        </div>

        <div class="row g-2">
            <div class="col d-grid mb-3">
                <a href="?page=users" class="btn btn-warning">
                    Cancelar
                </a>
            </div>
        </div>

        <div class="row g-2">
            <div class="col d-grid">
                <button class="btn btn-danger" type="submit" name="delete">Excluir</button>
            </div>
        </div>
    </form>
</section>