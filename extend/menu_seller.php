<nav class="orange darken-3">
  <div class="nav-wrapper">
    <a href="#" data-activates="menu" class="button-collapse show-on-large">
      <i class="material-icons">menu</i>
    </a>
    <span class="brand-logo center">Sistema X - Vendedor</span>
  </div>
</nav>

<ul id="menu" class="side-nav fixed grey lighten-4">
  <li>
    <div class="userView">
      <div class="background orange lighten-4">
        <img src="/U3G1/vista/img/sis.png" alt="Fondo">
      </div>
      <br><br><br>
      <a href="/U3G1/perfil/perfil.php">
        <img src="<?php echo $_SESSION['foto'] ?>" class="circle" alt="Usuario" width="80" height="80">
      </a>
      <a href="/U3G1/perfil/perfil.php" class="black-text name"><?php echo $_SESSION['nombre'] ?></a>
      <a href="#" class="black-text email"><?php echo $_SESSION['correo'] ?></a>
    </div>
  </li>

  <li><a href="/U3G1/inicio/index.php" class="waves-effect"><i class="material-icons">home</i>Inicio</a></li>
  <li><div class="divider"></div></li>

  <!-- Módulos que ve el Vendedor (solo ventas) -->
  <li><a href="/U3G1/controlador/clientes/" class="waves-effect"><i class="material-icons">assignment_ind</i>Clientes</a></li>
  <li><a href="/U3G1/controlador/venta/" class="waves-effect"><i class="material-icons">attach_money</i>Ventas</a></li>
  <li><div class="divider"></div></li>

  <li><a href="/U3G1/perfil/perfil.php" class="waves-effect"><i class="material-icons">person</i>Mi Perfil</a></li>
  <li><div class="divider"></div></li>
  <li><a href="/U3G1/login/salir.php" class="waves-effect"><i class="material-icons">exit_to_app</i>Salir</a></li>
</ul>

<script>
$(document).ready(function(){
  $('.button-collapse').sideNav();
  $('.collapsible').collapsible();
});
</script>