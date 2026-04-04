<?php

use App\Session\Login;

$userLogado = Login::getUsuarioLoged();

$user = $userLogado['name'] . ' ' . $userLogado['surname'] . ' <a href="/logout" class="font-weight-bold"> Sair</a>';

?>

<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Cabeleleila Leila</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/">Início</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/usuarios">Usuários</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/servicos">Serviços</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/">Agendamentos</a>
                </li>
            </ul>
            <div>
                <?= $user ?>
            </div>

        </div>
</nav>

<main class="container p-5">