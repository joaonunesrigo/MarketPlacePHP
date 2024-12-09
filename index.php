<?php
include 'service.php'; 
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Bem-vindo ao Marketplace</h1>
    </header>
    <main>
        <button id="openModal">Adicionar Produto</button>
        <button id="openCart">Ver Carrinho</button>
        <div class="product-list">
            <?php foreach ($_SESSION['products'] as $product): ?>
                <div class="product">
                    <h2><?= $product['name'] ?></h2>
                    <p>R$ <?= number_format($product['price'], 2, ',', '.') ?></p>
                    <button class="addToCart" data-id="<?= $product['id'] ?>" data-name="<?= $product['name'] ?>" data-price="<?= $product['price'] ?>">Adicionar ao Carrinho</button>

                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="remove_id" value="<?= $product['id'] ?>">
                        <button type="submit" class="removeProduct">Remover</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <div class="modal" id="productModal">
        <div class="modal-content">
            <h2>Adicionar Produto</h2>
            <form method="POST">
                <label for="name">Nome do Produto:</label>
                <input type="text" id="name" name="name" required>
                <label for="price">Preço:</label>
                <input type="number" id="price" name="price" step="0.01" required>
                <button type="submit">Adicionar</button>
            </form>
            <div class="close-modal" id="closeModal">Fechar</div>
        </div>
    </div>

    <div class="cart" id="cart">
        <div class="cart-header">
            <h2>Seu Carrinho</h2>
            <button id="closeCart">✖</button>
        </div>
        <ul id="cartItems"></ul>
        <div class="cart-footer">
            <p>Total: R$ <span id="cartTotal">0,00</span></p>
            <button id="checkout">Finalizar Compra</button>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
