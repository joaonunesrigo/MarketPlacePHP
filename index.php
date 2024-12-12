<?php
session_start();
include 'service.php'; 
include 'database.php'; 

// POST DE PRODUTOS
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_to_cart'])) {
        $product_id = $_POST['product_id'];

        // Buscar detalhes do produto
        $stmt = $conn->prepare("SELECT id, name, price, description, photo_link FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();

        // Adicionar o produto ao carrinho na sessão
        if ($product) {
            // Se o carrinho não existir, inicializa como array vazio
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            // Verifica se o produto já está no carrinho
            $found = false;
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['id'] === $product['id']) {
                    $item['quantity'] += 1;
                    $found = true;
                    break;
                }
            }
            
            if (!$found) {
                $product['quantity'] = 1;  // Define a quantidade inicial como 1
                $_SESSION['cart'][] = $product;
            }

            header('Location: index.php');
            exit();
        } else {
            echo "<script>alert('Produto não encontrado.');</script>";
        }
    }
}

// POST PARA ADICIONAR PRODUTO NO BANCO
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['price'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'] ?? '';
    $photo_link = $_POST['photo_link'] ?? '';

    // Inserir produto no banco de dados
    $stmt = $conn->prepare("INSERT INTO products (name, price, description, photo_link) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $price, $description, $photo_link);

    if ($stmt->execute()) {
        echo "<script>alert('Produto adicionado com sucesso!');</script>";
    } else {
        echo "<script>alert('Erro ao adicionar produto.');</script>";
    }
}

// Obter a lista de produtos
$products = [];
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
    <title>Marketplace</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Bem-vindo ao Marketplace</h1>        
    </header>

    <main>
        <button id="openModal">Adicionar Produto</button>
        <a href="cart.php">Ver Carrinho (<?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0 ?>)</a>

        <div class="product-list">
            <?php foreach ($products as $product): ?>
                <div class="product" title="<?= htmlspecialchars($product['description']) ?>">
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

                    <!-- Adicionar produto ao carrinho -->
                    <form method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <button type="submit" name="add_to_cart">Adicionar ao carrinho</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <!-- Modal para adicionar produto -->
    <div class="modal" id="productModal" style="display: none;">
        <div class="modal-content">
            <h2>Adicionar Produto</h2>
            <form method="POST">
                <label for="name">Nome do Produto:</label>
                <input type="text" id="name" name="name" required>
                <label for="price">Preço:</label>
                <input type="number" id="price" name="price" step="0.01" required>
                <label for="description">Descrição do Produto:</label>
                <input type="text" id="description" name="description">
                <label for="photo_link">Link da foto:</label>
                <input type="text" id="photo_link" name="photo_link">
                <button type="submit">Adicionar</button>
            </form>
            <div class="close-modal" id="closeModal">Fechar</div>
        </div>
    </div>

    <script>
        // Abrir o modal de adicionar produto
        document.getElementById("openModal").addEventListener("click", function() {
            document.getElementById("productModal").style.display = "flex"; // Alterar para "flex"
        });

        document.getElementById("closeModal").addEventListener("click", function() {
            document.getElementById("productModal").style.display = "none"; // Fechar o modal
        });
    </script>
</body>
</html>
