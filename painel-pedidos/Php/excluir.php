<?php
require 'conexao.php';

// Só exclui via POST (formulário do modal), nunca por link direto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $stmt = $conn->prepare('DELETE FROM pedidos WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    header('Location: index.php?msg=excluido');
    exit;
}
header('Location: index.php');
