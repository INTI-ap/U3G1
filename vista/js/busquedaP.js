function may(texto, id) {
  document.getElementById(id).value = texto.toUpperCase();
}

document.getElementById('buscarP').addEventListener('keyup', function() {
    var input = document.getElementById('buscarP');
    var filter = input.value.toLowerCase();
    var table = document.getElementById('tabla-productos');
    var rows = table.getElementsByClassName('producto-row');
    var totalMostrados = 0;

    for (var i = 0; i < rows.length; i++) {
        var row = rows[i];
        var cells = row.getElementsByTagName('td');
        var found = false;

        for (var j = 0; j < 5; j++) {
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

    var resultadoSpan = document.getElementById('resultado-busquedaP');
    var totalSpan = document.getElementById('total-productos');

    if (filter.length > 0) {
        resultadoSpan.innerHTML = `- Mostrando ${totalMostrados} de ${totalSpan.textContent} productos`;
        resultadoSpan.style.display = 'inline';
    } else {
        resultadoSpan.innerHTML = '';
        resultadoSpan.style.display = 'none';
    }

    var tbody = table.getElementsByTagName('tbody')[0];
    var noResultRow = document.getElementById('no-resultadosP');

    if (totalMostrados === 0 && filter.length > 0) {
        if (!noResultRow) {
            var newRow = tbody.insertRow();
            newRow.id = 'no-resultadosP';
            var cell = newRow.insertCell(0);
            cell.colSpan = 8;
            cell.textContent = 'No se encontraron productos con ese criterio de búsqueda';
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

// Botón para limpiar búsqueda de productos
document.getElementById('clear-searchP').addEventListener('click', function() {
    var searchInput = document.getElementById('buscarP');
    searchInput.value = '';
    var event = new Event('keyup');
    searchInput.dispatchEvent(event);
    searchInput.focus();
});