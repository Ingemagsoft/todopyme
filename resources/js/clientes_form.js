/**
 * clientes_form.js
 *
 * Comportamiento dinámico del formulario de clientes (create_view y edit_view):
 * el campo Dígito de Verificación (DV) es el disparador — si tiene contenido,
 * se asume persona jurídica (muestra razón social + rep. legal, fija tipo de
 * documento en NIT); si está vacío, se asume persona natural (muestra nombre/
 * apellido, libera el tipo de documento). Misma lógica usada en click-pyme.
 *
 * También auto-completa el número de documento con el código del cliente,
 * si el usuario no lo ha llenado manualmente.
 */
$(function () {

    const $dv = $('#digito_verificacion');
    const $codigo = $('#codigo');
    const $numDoc = $('#numero_documento');
    const $tipoDoc = $('#tipo_documento');
    const $bloqueJuridico = $('#bloque-juridico');
    const $bloqueNatural = $('#bloque-natural');

    function actualizarModo() {
        const tieneDV = $dv.val().trim() !== '';

        if (tieneDV) {
            $bloqueJuridico.removeClass('d-none');
            $bloqueNatural.addClass('d-none');
            $tipoDoc.val('NIT');
        } else {
            $bloqueJuridico.addClass('d-none');
            $bloqueNatural.removeClass('d-none');
            if ($tipoDoc.val() === 'NIT') {
                $tipoDoc.val('CC');
            }
        }
    }

    // Autocompletar numero_documento con el código, si está vacío
    $codigo.on('blur', function () {
        if ($numDoc.val() === '') {
            $numDoc.val($(this).val());
        }
    });

    $dv.on('input', actualizarModo);

    // Evaluar el estado inicial al cargar (importante en edit_view)
    actualizarModo();

});