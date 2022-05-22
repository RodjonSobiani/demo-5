function toCart(product_id) {
    $.ajax({
        method: "GET",
        url: `/site/to-cart/?product_id=${product_id}`,
    })
        .done(function (msg) {
            alert(msg);
            $.pjax.reload({
                container: '#cart'
            })
        });
}

function addCart(product_id) {
    $.ajax({
        method: "GET",
        url: `/site/to-cart/?product_id=${product_id}`,
    })
        .done(function (msg) {
            $.pjax.reload({
                container: '#cart'
            })
        });
}

function removeCart(product_id) {
    $.ajax({
        method: "GET",
        url: `/site/remove-cart/?product_id=${product_id}`,
    })
        .done(function (msg) {
            $.pjax.reload({
                container: '#cart'
            })
        });
}

function byOrder() {
    const password = document.querySelector('.password');
    if (!password.value) {
        return alert('Пожалуйста, введите пароль для подтверждения заказа');
    }
    $.ajax({
        method: "GET",
        url: `/site/by-order/?password=${password.value}`,
    })
        .done(function (msg) {
            alert(msg);
            $.pjax.reload({
                container: '#cart'
            })
        });
}
