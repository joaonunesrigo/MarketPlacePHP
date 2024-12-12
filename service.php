<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['products'])) {
    $_SESSION['products'] = [];
}

// Função para adicionar um produto
function addProduct($name, $price) {
    $newProduct = [
        'id' => count($_SESSION['products']) + 1,
        'name' => htmlspecialchars($name),
        'price' => (float)$price,
    ];
    $_SESSION['products'][] = $newProduct;
}

// Função para remover um produto pelo ID
function removeProduct($removeId) {
    // Filtra o produto pelo ID
    $_SESSION['products'] = array_filter($_SESSION['products'], fn($product) => $product['id'] !== (int)$removeId);
    $_SESSION['products'] = array_values($_SESSION['products']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Adicionar produto
    if (isset($_POST['name']) && isset($_POST['price'])) {
        addProduct($_POST['name'], $_POST['price']);
    }

    // Remover produto
    if (isset($_POST['remove_id'])) {
        removeProduct($_POST['remove_id']);
    }
}
?>
