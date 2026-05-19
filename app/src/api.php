<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/config.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

$pdo = getConnection();

if ($method === 'POST' && $action === 'cadastrar') {
    $data = json_decode(file_get_contents('php://input'), true);

    $nome      = trim($data['nome'] ?? '');
    $sobrenome = trim($data['sobrenome'] ?? '');
    $endereco  = trim($data['endereco'] ?? '');

    if (!$nome || !$sobrenome || !$endereco) {
        http_response_code(400);
        echo json_encode(['erro' => 'Todos os campos são obrigatórios.']);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO pessoas (nome, sobrenome, endereco) VALUES (?, ?, ?)");
    $stmt->execute([$nome, $sobrenome, $endereco]);

    echo json_encode([
        'sucesso' => true,
        'mensagem' => 'Pessoa cadastrada com sucesso!',
        'id' => $pdo->lastInsertId()
    ]);

} elseif ($method === 'GET' && $action === 'listar') {
    $stmt = $pdo->query("SELECT * FROM pessoas ORDER BY criado_em DESC");
    $pessoas = $stmt->fetchAll();
    echo json_encode($pessoas);

} elseif ($method === 'DELETE' && $action === 'deletar') {
    $id = intval($_GET['id'] ?? 0);
    if (!$id) {
        http_response_code(400);
        echo json_encode(['erro' => 'ID inválido.']);
        exit;
    }
    $stmt = $pdo->prepare("DELETE FROM pessoas WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode(['sucesso' => true, 'mensagem' => 'Registro removido.']);

} else {
    http_response_code(404);
    echo json_encode(['erro' => 'Rota não encontrada.']);
}
