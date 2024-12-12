<?php
session_start();
include 'service.php';
include 'database.php';

// Verifica se o carrinho está vazio
$cartEmpty = empty($_SESSION['cart']);

// Remover item do carrinho
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_id'])) {
    $remove_id = $_POST['remove_id'];
    
    // Remover o item do carrinho
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $remove_id) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }

    $_SESSION['cart'] = array_values($_SESSION['cart']);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Carrinho de Compras</h1>
        <a href="index.php">Voltar para a Página Inicial</a>
    </header>

    <main>
        <?php if ($cartEmpty): ?>
            <p>O seu carrinho está vazio.</p>
        <?php else: ?>
            <div class="product-list">
                <?php foreach ($_SESSION['cart'] as $product): ?>
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

                        <!-- Exibir a quantidade -->
                        <p>Quantidade: <?= $product['quantity'] ?></p>

                        <!-- Remover do carrinho -->
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="remove_id" value="<?= $product['id'] ?>">
                            <button type="submit">Remover do Carrinho</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Cotação do frete -->
            <div class="quotation">
                <label for="cep">Digite seu CEP:</label>
                <input type="text" id="cep" name="cep" placeholder="CEP" required>
                <button id="quoteShipping">Cotar Frete</button>
            </div>

            <!-- Exibir total do carrinho -->
            <div class="cart-total">
                <p><strong>Total: R$ <span id="totalAmount"><?= number_format(array_sum(array_map(fn($product) => $product['price'] * $product['quantity'], $_SESSION['cart'])), 2, ',', '.') ?></span></strong></p>
                <p><strong>Frete: R$ <span id="shippingCost">0,00</span></strong></p>
                <p><strong>Total Final: R$ <span id="finalTotal"><?= number_format(array_sum(array_map(fn($product) => $product['price'] * $product['quantity'], $_SESSION['cart'])), 2, ',', '.') ?></span></strong></p>
                <button id="finalizePurchase">Finalizar Compra</button>
            </div>
        <?php endif; ?>
    </main>
</body>
<script>
// Função para cotar o frete
document.getElementById('quoteShipping').addEventListener('click', function() {
    const cep = document.getElementById('cep').value;
    
    if (!cep || cep.length !== 8) {
        alert("Por favor, digite um CEP válido.");
        return;
    }

    // Acessar a API dos Correios para obter o endereço
    fetch(`https://viacep.com.br/ws/${cep}/json/`)
        .then(response => response.json())
        .then(data => {
            if (data.erro) {
                alert("CEP não encontrado.");
                return;
            }

            // Exibir o endereço para o usuário
            const endereco = `${data.logradouro}, ${data.bairro} - ${data.localidade}/${data.uf}`;
            alert("Endereço encontrado: " + endereco);

            // Gerar um valor aleatório para cotação
            const frete = (Math.random() * (100 - 10) + 10).toFixed(2);

            // Atualizar o valor do frete
            document.getElementById('shippingCost').textContent = frete;

            // Atualizar o valor total final
            const totalCarrinho = <?= number_format(array_sum(array_map(fn($product) => $product['price'] * $product['quantity'], $_SESSION['cart'])), 2, '.', '') ?>;
            const totalFinal = (parseFloat(totalCarrinho) + parseFloat(frete)).toFixed(2);
            document.getElementById('finalTotal').textContent = totalFinal;
        })
        .catch(error => {
            console.error("Erro ao buscar o endereço:", error);
            alert("Erro ao consultar o CEP.");
        });
});

// Função para finalizar a compra
document.getElementById('finalizePurchase').addEventListener('click', function() {
    const totalAmount = document.getElementById('finalTotal').textContent;
    
    alert(`Compra realizada com sucesso! Valor total: R$ ${totalAmount}`);
    
    // Zera o carrinho na sessão
    <?php
        $_SESSION['cart'] = [];
    ?>
    
    document.getElementById('totalAmount').textContent = '0,00';
    document.getElementById('finalTotal').textContent = '0,00';
    document.getElementById('shippingCost').textContent = '0,00';

    location.reload();
});
</script>
</html>