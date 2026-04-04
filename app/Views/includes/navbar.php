<?php

use App\Session\Login;

$userLogado = Login::getUsuarioLoged();
$role       = ($userLogado['role'] ?? '') === 'a' ? 'Admin' : 'Cliente';
$isAdmin    = ($userLogado['role'] ?? '') === 'a';
$fullname   = htmlspecialchars($userLogado['name']);

?>

<nav class="navbar navbar-expand-lg bg-body-tertiary shadow-sm">
    <div class="container-fluid">
        <span class="navbar-brand fw-semibold">Cabeleleila Leila</span>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <!-- Links de navegação -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/"><i class="bi bi-house-door me-1"></i>Início</a>
                </li>
                <?php if ($isAdmin): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/usuarios"><i class="bi bi-people me-1"></i>Usuários</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/servicos"><i class="bi bi-scissors me-1"></i>Serviços</a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link" href="/agendamentos"><i class="bi bi-calendar-check me-1"></i>Agendamentos</a>
                </li>
            </ul>

            <!-- Dropdown do usuário logado -->
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle d-flex align-items-center gap-2"
                    type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle fs-5"></i>
                    <span class="d-none d-md-inline">
                        <?= $fullname ?> <span class="badge bg-secondary ms-1"><?= $role ?></span>
                    </span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <!-- Nome visível só no mobile (quando o navbar está colapsado) -->
                    <li>
                        <span class="dropdown-item-text d-md-none fw-semibold">
                            <?= $fullname ?>
                            <span class="badge bg-secondary ms-1"><?= $role ?></span>
                        </span>
                    </li>
                    <li class="d-md-none">
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item" href="/minha-conta">
                            <i class="bi bi-person-gear me-2"></i>Minha Conta
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="/minha-conta/senha">
                            <i class="bi bi-key me-2"></i>Alterar Senha
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item text-danger" href="/logout">
                            <i class="bi bi-box-arrow-right me-2"></i>Sair
                        </a>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</nav>

<main class="container p-5">