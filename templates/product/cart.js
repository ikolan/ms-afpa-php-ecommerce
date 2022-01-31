const cartItems = document.querySelector("#cart-items");
const emptyCartAlert = document.querySelector("#empty-cart-alert");

let cart = this.assets.getProductsFromCart();
let cartElements = [];

if (cart.length === 0) {
    cartItems.remove();
} else {
    emptyCartAlert.hidden = true;

    for (let i = 0; i < cart.length; i++) {
        cartElements[i] = document.createElement("li")
        cartElements[i].classList.add("list-group-item");
        cartElements[i].classList.add("d-flex");
        cartElements[i].classList.add("justify-content-between");

        let xhr = new XMLHttpRequest();
        xhr.open("GET", "/product/json/" + cart[i].id);
        xhr.send();
        xhr.onload = function() {
            let product = JSON.parse(xhr.response);
            let leftdiv = document.createElement("div");
            let rightdiv = document.createElement("div");
            leftdiv.innerHTML = "<a href='/product/" + product.id + "/" + product.slug + "'>" + product.name + "</p>";
            leftdiv.innerHTML += "<p>" + cart[i].quantity + " x " + product.price/100 + " € = <b>" + (product.price * cart[i].quantity) / 100 + " €</b></p>";
            cartElements[i].appendChild(leftdiv);
            rightdiv.innerHTML += "<div class='btn btn-danger' onclick='deleteCartItem(" + i + ");'><i class='fas fa-trash-alt'></i></div>";
            cartElements[i].appendChild(rightdiv);
        };
        cartItems.appendChild(cartElements[i]);
    }
}

function deleteCartItem(index) {
    this.assets.removeProductFromCart(index);
    window.location.href = "/product/cart";
}
