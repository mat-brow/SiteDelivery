<?php
// Configuração da conexão com o MySQL (padrão do XAMPP)
$host  = 'localhost';
$user  = 'root';
$pass  = '';
$banco = 'pw2';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($host, $user, $pass, $banco);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    die('Não foi possível conectar ao banco "pw2". Verifique se o MySQL está ligado e se o pw2.sql foi importado.');
}

// Status: chave => [rótulo, classes do badge Bootstrap, ícone]
const STATUS = [
    'pendente'   => ['Pendente',   'warning text-dark', 'bi-hourglass-split'],
    'preparando' => ['Preparando', 'info text-dark',    'bi-fire'],
    'entregue'   => ['Entregue',   'success',           'bi-check2-circle'],
];

function e($texto) {
    return htmlspecialchars((string)$texto, ENT_QUOTES, 'UTF-8');
}

function moeda($valor) {
    return 'R$ ' . number_format((float)$valor, 2, ',', '.');
}
