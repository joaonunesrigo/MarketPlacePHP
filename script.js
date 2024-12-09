
const modal = document.getElementById("productModal");
const openModal = document.getElementById("openModal");
const closeModal = document.getElementById("closeModal");

openModal.addEventListener("click", () => {
    modal.style.display = "flex";
});

closeModal.addEventListener("click", () => {
    modal.style.display = "none";
});

window.addEventListener("click", (e) => {
    if (e.target === modal) {
        modal.style.display = "none";
    }
});

document.addEventListener("DOMContentLoaded", () => {
    const openCart = document.getElementById("openCart");
    const closeCart = document.getElementById("closeCart");
    const cart = document.getElementById("cart");
    const cartItems = document.getElementById("cartItems");
    const cartTotal = document.getElementById("cartTotal");
    let total = 0;

    openCart.addEventListener("click", () => cart.classList.add("show"));
    closeCart.addEventListener("click", () => cart.classList.remove("show"));

    document.querySelectorAll(".addToCart").forEach(button => {
        button.addEventListener("click", () => {
            const name = button.dataset.name;
            const price = parseFloat(button.dataset.price);

            const li = document.createElement("li");
            li.textContent = `${name} - R$ ${price.toFixed(2).replace(".", ",")}`;
            cartItems.appendChild(li);

            total += price;
            cartTotal.textContent = total.toFixed(2).replace(".", ",");
        });
    });
});