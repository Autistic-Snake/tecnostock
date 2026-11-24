// validaciones.js: validaciones en el navegador (también se repiten en PHP)
(function () {
    'use strict';

    // 1) El código del producto se escribe siempre en mayúsculas
    var codigo = document.getElementById('codigo');
    if (codigo) {
        codigo.addEventListener('input', function () {
            codigo.value = codigo.value.toUpperCase();
        });
    }

    // 2) En movimientos, una salida no puede superar el stock disponible
    var form = document.getElementById('form-movimiento');
    if (form) {
        var producto = document.getElementById('id_producto');
        var cantidad = document.getElementById('cantidad');

        var revisarStock = function () {
            var opcion = producto.options[producto.selectedIndex];
            var stock = parseInt(opcion.getAttribute('data-stock') || '0', 10);
            var tipo = form.querySelector('input[name="tipo"]:checked');
            var pedida = parseInt(cantidad.value, 10);

            if (tipo && tipo.value === 'salida' && pedida > stock) {
                cantidad.setCustomValidity('La salida supera el stock disponible (' + stock + ').');
            } else {
                cantidad.setCustomValidity('');
            }
        };

        form.addEventListener('input', revisarStock);
        form.addEventListener('change', revisarStock);
        revisarStock();
    }
})();