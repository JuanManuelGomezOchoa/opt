/**
 * Política de contraseñas en el cliente (solo UX; el servidor valida de todos modos).
 * Uso: <input type="password" data-password-policy data-feedback="id-contenedor">
 * Reglas: mínimo 8 caracteres, una minúscula, una mayúscula y un número.
 */
(function () {
    var REGLAS = [
        { texto: 'Mínimo 8 caracteres', ok: function (p) { return p.length >= 8; } },
        { texto: 'Al menos una minúscula', ok: function (p) { return /[a-z]/.test(p); } },
        { texto: 'Al menos una mayúscula', ok: function (p) { return /[A-Z]/.test(p); } },
        { texto: 'Al menos un número', ok: function (p) { return /[0-9]/.test(p); } }
    ];

    function validarPassword(p) {
        return REGLAS.filter(function (r) { return !r.ok(p); }).map(function (r) { return r.texto; });
    }

    function pintar(input, cont) {
        var p = input.value;
        cont.innerHTML = '';
        REGLAS.forEach(function (r) {
            var li = document.createElement('div');
            var cumple = r.ok(p);
            li.className = 'small ' + (cumple ? 'text-success' : 'text-danger');
            li.textContent = (cumple ? '✔ ' : '✖ ') + r.texto;
            cont.appendChild(li);
        });
    }

    document.querySelectorAll('input[data-password-policy]').forEach(function (input) {
        var cont = document.getElementById(input.getAttribute('data-feedback'));
        if (!cont) { return; }
        pintar(input, cont);
        input.addEventListener('input', function () { pintar(input, cont); });

        if (input.form) {
            input.form.addEventListener('submit', function (ev) {
                // Contraseña opcional (p. ej. edición): vacía = no se cambia
                if (input.hasAttribute('data-optional') && input.value === '') { return; }
                if (validarPassword(input.value).length) {
                    ev.preventDefault();
                    pintar(input, cont);
                    input.focus();
                }
            });
        }
    });

    window.validarPassword = validarPassword;
})();
