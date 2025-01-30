<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $requestType = $_POST['request'] ?? '';
        $target = $_POST['target'] ?? '';
        $ports = $_POST['ports'] ?? '';

        if (empty($requestType) || !file_exists($requestType . '.php')) {
            throw new Exception('Invalid request type');
        }

        require_once $requestType . '.php';
        $className = ucfirst($requestType);
        
        if (!class_exists($className)) {
            throw new Exception('Invalid operation');
        }

        $operation = new $className($ports);
        echo $operation->getOutput($target);
        
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>
