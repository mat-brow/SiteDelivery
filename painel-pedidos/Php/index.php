<?php
require 'conexao.php';

$filtro = $_GET['status'] ?? '';
$busca  = trim($_GET['busca'] ?? '');

$where = []; $tipos = ''; $params = [];
if (isset(STATUS[$filtro])) { $where[] = 'status = ?'; $tipos .= 's'; $params[] = $filtro; }
if ($busca !== '') {
    $where[] = '(cliente LIKE ? OR itens LIKE ?)';
    $tipos .= 'ss'; $params[] = "%$busca%"; $params[] = "%$busca%";
}
$sql = 'SELECT * FROM pedidos' . ($where ? ' WHERE ' . implode(' AND ', $where) : '') . ' ORDER BY id DESC';
$stmt = $conn->prepare($sql);
if ($params) $stmt->bind_param($tipos, ...$params);
$stmt->execute();
$pedidos = $stmt->get_result();

$mensagens = [
    'criado'   => 'Pedido criado com sucesso.',
    'editado'  => 'Pedido atualizado.',
    'excluido' => 'Pedido excluído.',
];
$msg = $mensagens[$_GET['msg'] ?? ''] ?? null;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pedidos | Painel de Pedidos</title>
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
            <a class="nav-link active" href="index.php">Pedidos</a>
            <a class="nav-link" href="criar.php">Novo pedido</a>
        </div>
    </div>
</nav>

<main class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <h1 class="h3 m-0">Pedidos</h1>
        <a href="criar.php" class="btn btn-brand"><i class="bi bi-plus-lg me-1"></i>Novo pedido</a>
    </div>

    <form method="get" class="row g-2 mb-3">
        <div class="col-12 col-md-6">
            <input type="search" name="busca" value="<?= e($busca) ?>" class="form-control" placeholder="Buscar por cliente ou item">
        </div>
        <div class="col-8 col-md-4">
            <select name="status" class="form-select" data-autosubmit>
                <option value="">Todos os status</option>
                <?php foreach (STATUS as $k => $s): ?>
                    <option value="<?= $k ?>" <?= $filtro === $k ? 'selected' : '' ?>><?= $s[0] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-4 col-md-2 d-grid"><button class="btn btn-outline-secondary">Buscar</button></div>
    </form>

    <div class="card painel">
        <div class="table-responsive">
            <table class="table table-hover align-middle m-0">
                <thead>
                    <tr><th>#</th><th>Cliente</th><th>Itens</th><th>Total</th><th>Status</th><th>Data</th><th class="text-end">Ações</th></tr>
                </thead>
                <tbody>
                <?php if ($pedidos->num_rows === 0): ?>
                    <tr><td colspan="7" class="text-center text-muted py-5">Nenhum pedido encontrado. <a href="criar.php">Cadastre o primeiro pedido</a>.</td></tr>
                <?php endif; ?>
                <?php $i = 0; while ($p = $pedidos->fetch_assoc()): $s = STATUS[$p['status']]; ?>
                    <tr class="linha" style="--i:<?= min($i++, 12) ?>">
                        <td class="text-muted"><?= $p['id'] ?></td>
                        <td class="fw-semibold"><?= e($p['cliente']) ?></td>
                        <td class="itens"><?= nl2br(e($p['itens'])) ?></td>
                        <td class="text-nowrap"><?= moeda($p['valor_total']) ?></td>
                        <td><span class="badge bg-<?= $s[1] ?>"><i class="bi <?= $s[2] ?> me-1"></i><?= $s[0] ?></span></td>
                        <td class="text-muted text-nowrap"><?= date('d/m H:i', strtotime($p['criado_em'])) ?></td>
                        <td class="text-end text-nowrap">
                            <a href="editar.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary" title="Editar"><i class="bi bi-pencil"></i></a>
                            <button type="button" class="btn btn-sm btn-outline-danger" title="Excluir"
                                    data-bs-toggle="modal" data-bs-target="#modalExcluir"
                                    data-id="<?= $p['id'] ?>" data-cliente="<?= e($p['cliente']) ?>"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<div class="modal fade" id="modalExcluir" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" method="post" action="excluir.php">
            <div class="modal-header"><h5 class="modal-title">Excluir pedido</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <input type="hidden" name="id">
                Deseja excluir o pedido de <strong id="nomeExcluir"></strong>? Essa ação não pode ser desfeita.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-danger">Excluir pedido</button>
            </div>
        </form>
    </div>
</div>

<?php if ($msg): ?>
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div class="toast text-bg-dark border-0"><div class="toast-body"><i class="bi bi-check-circle me-2"></i><?= $msg ?></div></div>
</div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/script.js"></script>
</body>
</html>
