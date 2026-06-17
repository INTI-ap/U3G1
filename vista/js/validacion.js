// ==================== FUNCIONES GENERALES ====================

$(document).ready(function() {
    // Selects
    $('select').not('.browser-default').formSelect();
    // Modales
    $('.modal').modal();
    // Datepickers (si existen)
    if ($('.datepicker').length) {
        $('.datepicker').datepicker({ format: 'dd/mm/yyyy' });
    }
    // Tooltips
    $('.tooltipped').tooltip();
});

// ==================== VALIDACIÓN DE USUARIOS ====================

$(document).on('change', '#nick', function() {
    var nick = $(this).val();
    if (nick.length >= 8) {
        $.ajax({
            url: '/U3G2/controlador/usuarios/validacion_nick.php',
            type: 'POST',
            data: { nick: nick },
            beforeSend: function() {
                $('.validation').html('<span class="blue-text">Espere un momento por favor...</span>');
            },
            success: function(respuesta) {
                $('.validation').html(respuesta);
            },
            error: function() {
                $('.validation').html('<span class="red-text">Error en la validación</span>');
            }
        });
    } else {
        $('.validation').html('<span class="orange-text">El nick debe tener al menos 8 caracteres</span>');
    }
});

$(document).on('change', '#pass2', function() {
    var pass1 = $('#pass1').val();
    var pass2 = $(this).val();
    if (pass1 === pass2 && pass1 !== '') {
        Swal.fire({
            title: 'Bien hecho...',
            text: 'Las contraseñas coinciden',
            icon: 'success',
            timer: 1500,
            showConfirmButton: false
        });
        $('#btn_guardar').show();
    } else if (pass1 !== pass2 && pass2 !== '') {
        Swal.fire({
            title: 'Oppss...',
            text: 'Las contraseñas no coinciden',
            icon: 'error',
            timer: 1500,
            showConfirmButton: false
        });
        $('#btn_guardar').hide();
    }
});

$(document).on('keypress', '.form', function(e) {
    if (e.which === 13 && !$(e.target).is('textarea')) {
        e.preventDefault();
        return false;
    }
});

// ==================== VALIDACIÓN DE CLIENTES ====================

$(document).on('change', 'input[name="correo"]', function() {
    var correo = $(this).val();
    var container = $(this).closest('.input-field').find('.validation-correo');
    if (!container.length) {
        container = $('<div class="validation-correo"></div>');
        $(this).closest('.input-field').append(container);
    }
    if (correo !== '') {
        $.ajax({
            url: '/U3G2/controlador/clientes/validacion_correo.php',
            type: 'POST',
            data: { correo: correo },
            beforeSend: function() {
                container.html('<span class="blue-text">Verificando correo...</span>');
            },
            success: function(resp) {
                container.html(resp);
            },
            error: function() {
                container.html('<span class="red-text">Error al verificar</span>');
            }
        });
    } else {
        container.html('');
    }
});

// ==================== VALIDACIÓN DE PRODUCTOS ====================

$(document).on('change', '#nombre_producto', function() {
    var nombre_producto = $(this).val();
    if (nombre_producto.length >= 8) {
        $.ajax({
            url: '/U3G2/controlador/usuarios/validacion_producto.php',
            type: 'POST',
            data: { nombre_producto: nombre_producto },
            beforeSend: function() {
                $('.validation').html('<span class="blue-text">Espere un momento por favor...</span>');
            },
            success: function(respuesta) {
                $('.validation').html(respuesta);
            },
            error: function() {
                $('.validation').html('<span class="red-text">Error en la validación</span>');
            }
        });
    } else {
        $('.validation').html('<span class="orange-text">El nombre del producto debe tener al menos 8 caracteres</span>');
    }
});


$(document).on('change', 'input[name="precio"]', function() {
    var precio = parseFloat($(this).val());
    if (isNaN(precio) || precio < 0) {
        $(this).val('');
        Swal.fire('Error', 'El precio debe ser un número mayor o igual a 0', 'error');
    }
});

$(document).on('change', 'input[name="stock"]', function() {
    var stock = parseInt($(this).val(), 10);
    if (isNaN(stock) || stock < 0) {
        $(this).val('');
        Swal.fire('Error', 'El stock debe ser un número entero no negativo', 'error');
    }
});

// ==================== VALIDACIÓN DE VENTAS ====================

$(document).on('change', '.cantidad', function() {
    var row = $(this).closest('.producto-row');
    var productoSelect = row.find('.producto-select');
    var selectedOption = productoSelect.find('option:selected');
    var stockDisponible = selectedOption.data('stock');
    var cantidad = parseInt($(this).val(), 10);
    
    if (!isNaN(stockDisponible) && cantidad > stockDisponible) {
        Swal.fire('Stock insuficiente', 'Solo hay ' + stockDisponible + ' unidades disponibles', 'warning');
        $(this).val(stockDisponible);
    }
    if (isNaN(cantidad) || cantidad < 1) {
        $(this).val(1);
    }
});

$(document).on('change', '.producto-select', function() {
    var selected = $(this).find('option:selected');
    var precio = selected.data('precio');
    var row = $(this).closest('.producto-row');
    var precioSpan = row.find('.precio-unitario');
    if (precioSpan.length === 0) {
        // Si no existe, lo creamos al lado de la cantidad
        row.find('.col.s3').after('<div class="col s2"><span class="precio-unitario">$' + precio.toFixed(2) + '</span></div>');
    } else {
        precioSpan.text('$' + precio.toFixed(2));
    }
});

// ========================================

$(document).on('blur', 'input[name="nombre"], input[name="apellido"]', function() {
    $(this).val($(this).val().toUpperCase());
});


$(document).on('click', '#clear-search', function() {
    $('#buscarU, #buscarC, #buscarP').val('').trigger('keyup');
});