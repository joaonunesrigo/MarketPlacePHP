<?php
session_start();

if (!isset($_SESSION['products'])) {
    $_SESSION['products'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['name']) && isset($_POST['price'])) {
        $newProduct = [
            'id' => count($_SESSION['products']) + 1,
            'name' => $_POST['name'],
            'price' => (float)$_POST['price'],
        ];
        $_SESSION['products'][] = $newProduct;
    }

    if (isset($_POST['remove_id'])) {
        $removeId = (int)$_POST['remove_id'];
        $_SESSION['products'] = array_filter($_SESSION['products'], fn($product) => $product['id'] !== $removeId);
        $_SESSION['products'] = array_values($_SESSION['products']);
    }
}
?>
