const quantityInput = document.querySelector("#quantity")

function addToCart(id) {
    this.assets.addProductToCart(id, quantityInput.value);
    window.location.href = "/product/cart";
}