function changeQuantity(id, input) {
    if (input.value > 0) {
        window.location.href = "/cart/changeQuantity?id=" + id + "&quantity=" + input.value;
    }
}