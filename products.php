<?php
session_start();
include 'service.php';
include 'database.php';

// Inicialize um array para os produtos
$products = [];

// Execute a consulta ao banco de dados para buscar os produtos
try {
    $stmt = $conn->prepare("SELECT id, name, price, description, photo_link FROM products");
    $stmt->execute();
    $products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
} catch (mysqli_sql_exception $e) {
    die("Erro ao buscar produtos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listagem de Produtos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Listagem de Produtos</h1>
        <a href="index.php">Voltar para a Página Inicial</a>
    </header>
    <main>
        <div class="product-list">
            <?php foreach ($products as $product): ?>
                <div class="product">
                    <h2><?= htmlspecialchars($product['name']) ?></h2>
                    <p>R$ <?= number_format($product['price'], 2, ',', '.') ?></p>
                    
                    <!-- Exibir imagem do produto -->
                    <?php if (!empty($product['photo_link'])): ?>
                        <div class="product-image">
                            <img src="<?= htmlspecialchars($product['photo_link']) ?>" alt="Imagem de <?= htmlspecialchars($product['name']) ?>" />
                        </div>
                    <?php else: ?>
                        <p>Sem imagem disponível</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>
