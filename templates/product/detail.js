const quantityInput = document.querySelector("#quantity")

function addToCart(id) {
    if (quantityInput.value > 0) {
        window.location.href = "/cart/add?id=" + id + "&quantity=" + quantityInput.value;
    }
}