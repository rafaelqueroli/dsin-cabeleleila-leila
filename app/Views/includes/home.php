<?php

use App\Session\Login;

$userLogado = Login::getUsuarioLoged();
$isAdmin    = ($userLogado['role'] ?? '') === 'a';
?>

<section class="py-5 text-center border-bottom mb-5">
    <i class="bi bi-scissors display-4 text-primary"></i>
    <h1 class="mt-3 fw-semibold">Salão de Beleza - CABELELEILA LEILA</h1>
    <br />
    <h3 class="fw-semibold">ONDE TUDO É UMA BELEZA</h3>
    <p class="text-center">
        Quem não conhece o nome, certamente reconhece o cuidado. Aqui, cuidamos dos seus cabelos, das suas unhas e da sua autoestima com a dedicação que você merece. Venha viver a experiência completa no salão mais icônico da região.<br><strong>Cabeleleila Leila: Cabelos, Unhas, Estética e Você.</strong>
    </p>

    <p class="text-muted">Bem-vindo(a), <?= htmlspecialchars($userLogado['name']) ?>!</p>
</section>