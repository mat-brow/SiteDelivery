<?php
require 'Php/conexao.php';

$cont = ['pendente' => 0, 'preparando' => 0, 'entregue' => 0];
$total = 0;
$r = $conn->query('SELECT status, COUNT(*) AS c, SUM(valor_total) AS t FROM pedidos GROUP BY status');
while ($l = $r->fetch_assoc()) { $cont[$l['status']] = (int)$l['c']; $total += (float)$l['t']; }
$recentes = $conn->query('SELECT * FROM pedidos ORDER BY id DESC LIMIT 5');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Painel de Pedidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand navbar-dark topo">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php"><i class="bi bi-bag-check me-2"></i>Painel de Pedidos</a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link" href="Php/index.php">Pedidos</a>
            <a class="nav-link" href="Php/criar.php">Novo pedido</a>
        </div>
    </div>
</nav>

<main class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-2 mb-4">
        <div>
            <h1 class="h3 m-0">Resumo do dia</h1>
            <p class="text-muted m-0">Acompanhe o andamento dos pedidos da loja.</p>
        </div>
        <a href="Php/index.php" class="btn btn-brand">Ver todos os pedidos</a>
    </div>

    <div class="row g-3 mb-4">
        <?php foreach (STATUS as $k => $s): ?>
        <div class="col-6 col-lg-3">
            <div class="card painel resumo h-100 p-3">
                <div class="text-muted small"><i class="bi <?= $s[2] ?> me-1"></i><?= $s[0] ?></div>
                <div class="numero" data-count="<?= $cont[$k] ?>">0</div>
            </div>
        </div>
        <?php endforeach; ?>
        <div class="col-6 col-lg-3">
            <div class="card painel resumo destaque h-100 p-3">
                <div class="small opacity-75"><i class="bi bi-cash-coin me-1"></i>Valor em pedidos</div>
                <div class="numero" data-count="<?= $total ?>" data-moeda>R$ 0,00</div>
            </div>
        </div>
    </div>

    <div class="card painel">
        <div class="card-header bg-white fw-semibold">Últimos pedidos</div>
        <ul class="list-group list-group-flush">
            <?php if ($recentes->num_rows === 0): ?>
                <li class="list-group-item text-muted py-4 text-center">Sem pedidos ainda. <a href="Php/criar.php">Cadastre o primeiro</a>.</li>
            <?php endif; ?>
            <?php while ($p = $recentes->fetch_assoc()): $s = STATUS[$p['status']]; ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span><strong><?= e($p['cliente']) ?></strong> <span class="text-muted ms-2"><?= moeda($p['valor_total']) ?></span></span>
                <span class="badge bg-<?= $s[1] ?>"><?= $s[0] ?></span>
            </li>
            <?php endwhile; ?>
        </ul>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/script.js"></script>
</body>
</html>
