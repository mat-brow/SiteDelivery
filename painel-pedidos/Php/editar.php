<?php
require 'conexao.php';

$id = (int)($_POST['id'] ?? $_GET['id'] ?? 0);
$stmt = $conn->prepare('SELECT * FROM pedidos WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$d = $stmt->get_result()->fetch_assoc();
if (!$d) { header('Location: index.php'); exit; }

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $d['cliente']     = trim($_POST['cliente'] ?? '');
    $d['itens']       = trim($_POST['itens'] ?? '');
    $d['valor_total'] = str_replace(',', '.', trim($_POST['valor_total'] ?? ''));
    $d['status']      = $_POST['status'] ?? '';

    if ($d['cliente'] === '' || $d['itens'] === '') {
        $erro = 'Preencha o cliente e os itens do pedido.';
    } elseif (!is_numeric($d['valor_total']) || $d['valor_total'] < 0) {
        $erro = 'Informe um valor total válido.';
    } elseif (!isset(STATUS[$d['status']])) {
        $erro = 'Escolha um status válido.';
    } else {
        $valor = (float)$d['valor_total'];
        $stmt = $conn->prepare('UPDATE pedidos SET cliente = ?, itens = ?, valor_total = ?, status = ? WHERE id = ?');
        $stmt->bind_param('ssdsi', $d['cliente'], $d['itens'], $valor, $d['status'], $id);
        $stmt->execute();
        header('Location: index.php?msg=editado');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar pedido | Painel de Pedidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand navbar-dark topo">
    <div class="container">
        <a class="navbar-brand fw-bold" href="../index.php"><i class="bi bi-bag-check me-2"></i>Painel de Pedidos</a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link" href="index.php">Pedidos</a>
            <a class="nav-link" href="criar.php">Novo pedido</a>
        </div>
    </div>
</nav>

<main class="container py-4" style="max-width: 720px;">
    <h1 class="h3 mb-3">Editar pedido</h1>
    <?php if ($erro): ?><div class="alert alert-danger"><?= e($erro) ?></div><?php endif; ?>

    <form method="post" class="card painel p-4 needs-validation" novalidate>
        <input type="hidden" name="id" value="<?= (int)$d['id'] ?>">
        <div class="mb-3">
            <label class="form-label" for="cliente">Cliente</label>
            <input type="text" id="cliente" name="cliente" class="form-control" maxlength="100" required value="<?= e($d['cliente']) ?>">
            <div class="invalid-feedback">Informe o nome do cliente.</div>
        </div>
        <div class="mb-3">
            <label class="form-label" for="itens">Itens do pedido</label>
            <textarea id="itens" name="itens" class="form-control" rows="4" required placeholder="Um item por linha. Ex.: 2x Pizza calabresa"><?= e($d['itens']) ?></textarea>
            <div class="invalid-feedback">Informe pelo menos um item.</div>
        </div>
        <div class="row g-3 mb-4">
            <div class="col-sm-6">
                <label class="form-label" for="valor_total">Valor total (R$)</label>
                <input type="number" id="valor_total" name="valor_total" class="form-control" step="0.01" min="0" required value="<?= e($d['valor_total']) ?>">
                <div class="invalid-feedback">Informe um valor válido.</div>
            </div>
            <div class="col-sm-6">
                <label class="form-label" for="status">Status</label>
                <select id="status" name="status" class="form-select">
                    <?php foreach (STATUS as $k => $s): ?>
                        <option value="<?= $k ?>" <?= $d['status'] === $k ? 'selected' : '' ?>><?= $s[0] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-brand" type="submit">Salvar alterações</button>
            <a href="index.php" class="btn btn-light">Cancelar</a>
        </div>
    </form>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/script.js"></script>
</body>
</html>
