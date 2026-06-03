// Función de búsqueda para usuarios
document.getElementById('buscarU').addEventListener('keyup', function() {
    var input = document.getElementById('buscarU');
    var filter = input.value.toLowerCase();
    var table = document.getElementById('tabla-usuarios');
    var rows = table.getElementsByClassName('usuario-row');
    var totalMostrados = 0;

    // Recorrer todas las filas de la tabla
    for (var i = 0; i < rows.length; i++) {
        var row = rows[i];
        var cells = row.getElementsByTagName('td');
        var found = false;

        // Buscar en las primeras 3 columnas (Nick, Nombre, Correo)
        for (var j = 0; j < 3; j++) {
            if (cells[j]) {
                var textValue = cells[j].textContent || cells[j].innerText;
                if (textValue.toLowerCase().indexOf(filter) > -1) {
                    found = true;
                    break;
                }
            }
        }

        // Mostrar u ocultar la fila
        if (found) {
            row.style.display = '';
            totalMostrados++;
        } else {
            row.style.display = 'none';
        }
    }

    // Actualizar el contador de resultados
    var resultadoSpan = document.getElementById('resultado-busquedaU');
    var totalSpan = document.getElementById('total-usuarios');

    if (filter.length > 0) {
        resultadoSpan.innerHTML = `- Mostrando ${totalMostrados} de ${totalSpan.textContent} usuarios`;
        resultadoSpan.style.display = 'inline';
    } else {
        resultadoSpan.innerHTML = '';
        resultadoSpan.style.display = 'none';
    }

    // Mostrar mensaje si no hay resultados
    var tbody = table.getElementsByTagName('tbody')[0];
    var noResultRow = document.getElementById('no-resultadosU');

    if (totalMostrados === 0 && filter.length > 0) {
        if (!noResultRow) {
            var newRow = tbody.insertRow();
            newRow.id = 'no-resultadosU';
            var cell = newRow.insertCell(0);
            cell.colSpan = 8;
            cell.textContent = 'No se encontraron usuarios con ese criterio de búsqueda';
            cell.style.textAlign = 'center';
            cell.style.padding = '20px';
            cell.style.color = '#666';
        }
    } else {
        if (noResultRow) {
            noResultRow.remove();
        }
    }
});

// Botones para limpiar búsqueda
document.getElementById('clear-searchU').addEventListener('click', function() {
    var searchInput = document.getElementById('buscarU');
    searchInput.value = '';
    var event = new Event('keyup');
    searchInput.dispatchEvent(event);
    searchInput.focus();
    function may(valor, id) {
    if (id === 'nombre') {
        document.getElementById(id).value = valor.toUpperCase();
    }
}
});