<?php 
include '../extend/header.php';

$sel = $con->query("SELECT * FROM usuario ORDER BY idUsuario DESC");
$row = mysqli_num_rows($sel);
?>

<div class="row">
  <div class="col s12">
    <div class="card">
      <div class="card-content">
        <span class="card-title">Usuarios (<?php echo $row ?>)</span>
        <table class="striped responsive-table">
          <thead>
            <tr>
              <th>Nick</th>
              <th>Nombre</th>
              <th>Correo</th>

            </tr>
          </thead>
          <tbody>
            <?php while($f = $sel->fetch_assoc()){ ?>
              <tr>
                <td><?php echo htmlspecialchars($f['nick']) ?></td>
                <td><?php echo htmlspecialchars($f['nombre']) ?></td>
                <td><?php echo htmlspecialchars($f['correo']) ?></td>
                <td>
                <input type="hidden" name="id" value="<?php echo $f['idUsuario'] ?>">
                <select name="nivel" required>
                  <option value="<?php echo htmlspecialchars($f['nivel']) ?>" selected><?php echo htmlspecialchars($f['nivel']) ?></option>
                  <option value="ADMINISTRADOR">ADMINISTRADOR</option>
                  <option value="ASESOR">ASESOR</option>
                </select>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php $sel = $con->query("SELECT * FROM Producto ORDER BY idProducto DESC");
$row = mysqli_num_rows($sel);
 ?>

<div class="row"> 
  <div class="col s12">
    <div class="card">
      <div class="card-content">
        <span class="card-title">Productos (<span id="total-productos"><?php echo $row ?></span>) 
          <span id="resultado-busquedaP" style="font-size: 14px; color: #666; display: none;"></span>
        </span>
        <table class="striped responsive-table" id="tabla-productos">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Estado</th>
              <th>Precio</th>
              <th>Stock</th>
            </tr>
          </thead>
          <tbody>
          <?php while($f = $sel->fetch_assoc()){ ?>
            <tr class="producto-row">
              <td><?php echo $f['idProducto'] ?></td>
              <td><?php echo $f['nombre'] ?></td>
              <td><?php echo $f['estado'] ?></td>
              <td>$<?php echo number_format($f['precio'], 2) ?></td>
              <td><?php echo $f['stock'] ?></td>
          <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var selects = document.querySelectorAll('select');
        M.FormSelect.init(selects);
    });
</script>
<?php include '../extend/scripts.php'; ?>