const localStorage = window.localStorage;

function setCart(cart) {
    localStorage.setItem("app/cart", JSON.stringify(cart));
}

export function getProductsFromCart() {
    return JSON.parse(localStorage.getItem("app/cart"));
}

export function addProductToCart(id, quantity) {
    let cart = getProductsFromCart();
    if (cart === null) {
        cart = [];
    }
    cart.push({"id": id, "quantity": quantity});
    setCart(cart);
}

export function removeProductFromCart(index) {
    let cart = getProductsFromCart();
    cart.splice(index, 1);
    setCart(cart);
}