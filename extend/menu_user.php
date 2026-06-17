<nav class="blue darken-3">
  <div class="nav-wrapper">
    <a href="#" data-activates="menu" class="button-collapse show-on-large">
      <i class="material-icons">menu</i>
    </a>
    <span class="brand-logo center">Sistema de Ventas</span>
  </div>
</nav>

<ul id="menu" class="side-nav fixed grey lighten-4">
  <li>
    <div class="userView">
      <div class="background blue lighten-4">
        <img src="/U3G2/vista/img/sis.png" alt="Fondo">
      </div>
      <br><br><br>
      <!-- Aquí puedes cargar dinámicamente la foto, nombre y correo del usuario logueado -->
      <a href="#"><img src="/U3G2/vista/img/avatar_default.png" class="circle" alt="Usuario"></a>
      <a href="#" class="black-text name">Bienvenido</a>
      <a href="#" class="black-text email">admin@sistema.com</a>  
    </div>
  </li>

  <!-- INICIO -->
  <li><a href="/U3G2/inicio/index.php" class="waves-effect"><i class="material-icons">home</i>Inicio</a></li>
  <li><div class="divider"></div></li>

  <!-- MÓDULOS PRINCIPALES (sin acordeón, directo) -->
  <li><a href="/U3G2/controlador/venta/" class="waves-effect"><i class="material-icons">attach_money</i>Ventas</a></li>
  <li><div class="divider"></div></li>

  <!-- SALIR -->
  <li><a href="/U3G2/" class="waves-effect"><i class="material-icons">exit_to_app</i>Salir</a></li>
  <li><div class="divider"></div></li>
</ul>

<!-- Botones flotantes (pueden personalizarse o eliminarse) -->
<div class="fixed-action-btn vertical click-to-toggle">
  <a class="btn-floating red">
    <i class="material-icons">settings</i>
  </a>
  <ul>
    <li><a href="/U3G2/controlador/usuarios/" class="btn-floating blue"><i class="material-icons">person_add</i></a></li>
    <li><a href="/U3G2/controlador/productos/" class="btn-floating green"><i class="material-icons">add_shopping_cart</i></a></li>
    <li><a href="/U3G2/controlador/clientes/" class="btn-floating orange"><i class="material-icons">group_add</i></a></li>
  </ul>
</div>