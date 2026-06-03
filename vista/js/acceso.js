//mis funciones
function ingreso() {
    var usuario = document.getElementById("usuario").value;
    var contra = document.getElementById("contra").value;
    if ((usuario == "A") && (contra == "123")) {
        Swal.fire('Datos Correctos');
        window.location = "/U3G1/inicio/index.php";
    } else {
        Swal.fire('Datos Incorrectos');
    }
}
document.getElementById('boton').onclick = function() { ingreso(); }
