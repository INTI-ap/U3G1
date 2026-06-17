<?php include "../extend/header.php"; ?>
<div class="row">
  <div class="col s12 m8 offset-m2">
    <div class="card">
      <div class="card-content">
        <span class="card-title">Mi Perfil</span>
        
        <div class="center-align">
          <img src="<?php echo $_SESSION['foto'] ?>" class="circle" width="150" height="150" style="object-fit: cover;">
        </div>
        
        <form action="up_perfil.php" method="post">
          <div class="input-field">
            <input type="text" name="nombre" id="nombre" value="<?php echo $_SESSION['nombre'] ?>" required>
            <label for="nombre">Nombre completo</label>
          </div>
          <div class="input-field">
            <input type="email" name="correo" id="correo" value="<?php echo $_SESSION['correo'] ?>" required>
            <label for="correo">Correo electrónico</label>
          </div>
          <button type="submit" class="btn blue">Actualizar datos</button>
        </form>
        
        <div class="divider" style="margin: 20px 0;"></div>
        
        <h5>Cambiar contraseña</h5>
        <form action="up_pass.php" method="post">
          <div class="input-field">
            <input type="password" name="pass_actual" id="pass_actual" required>
            <label for="pass_actual">Contraseña actual</label>
          </div>
          <div class="input-field">
            <input type="password" name="pass_nueva" id="pass_nueva" required pattern="[A-Za-z0-9]{8,15}">
            <label for="pass_nueva">Nueva contraseña</label>
          </div>
          <div class="input-field">
            <input type="password" name="pass_confirm" id="pass_confirm" required>
            <label for="pass_confirm">Confirmar nueva contraseña</label>
          </div>
          <button type="submit" class="btn orange">Cambiar contraseña</button>
        </form>
        
        <div class="divider" style="margin: 20px 0;"></div>
        
        <h5>Cambiar foto de perfil</h5>
        <form action="up_foto.php" method="post" enctype="multipart/form-data">
          <div class="file-field input-field">
            <div class="btn">
              <span>Foto</span>
              <input type="file" name="foto" accept="image/png,image/jpeg" required>
            </div>
            <div class="file-path-wrapper">
              <input class="file-path validate" type="text" placeholder="Seleccione una imagen">
            </div>
          </div>
          <button type="submit" class="btn green">Actualizar foto</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include "../extend/scripts.php"; ?>
</body>
</html>