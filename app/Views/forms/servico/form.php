<section>
    <div class="d-flex align-items-center gap-3 mt-3 mb-4">
        <a href="javascript:history.back()">
            <button class="btn btn-danger"><i class="bi bi-arrow-left-circle"></i> Início</button>
        </a>
        <h3 class="mb-2"><?= TITLE ?></h3>
    </div>

    <form method="post">
        <div class="row g-2 mb-3">
            <label class="form-label">Categoria do Serviço</label>
            <select name="cat" class="form-select" required>
                <option value="" disabled selected>Escolha a categoria do Serviço</option>
                <option value="c" <?= $objServico->cat == 'c' ? 'selected' : '' ?>>Cabelo</option>
                <option value="u" <?= $objServico->cat == 'u' ? 'selected' : '' ?>>Unha</option>
                <option value="a" <?= $objServico->cat == 'e' ? 'selected' : '' ?>>Estética</option>
            </select>
        </div>

        <div class="row g-2 mb-3">
            <div class="col">
                <label class="form-label">Nome do Serviço</label>
                <input type="text" name="name" class="form-control" value="<?= $objServico->name ?>" required>
            </div>
        </div>

        <div class="row g-2 mb-3">
            <div class="col-6">
                <label class="form-label">Duração em minutos</label>
                <input type="number" name="duration_min" class="form-control" min="0" value="<?= $objServico->duration_min ?>" required>
            </div>
            <div class="col-6">
                <label class="form-label">Preço do Serviço</label>
                <input type="number" name="price" class="form-control" min="0" step="0.01" value="<?= $objServico->price ?>" required>
            </div>
        </div>

        <div class="row g-2">
            <div class="col d-grid">
                <button class="btn btn-primary" type="submit">Enviar</button>
            </div>
        </div>
    </form>
</section>
