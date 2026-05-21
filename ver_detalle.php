<?php
require_once 'conexion.php';
$id = intval($_GET['id']);
$row = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT * FROM reportes_denuncias WHERE id=$id"));
?>
<!DOCTYPE html>
<html>
<head><title>Detalle - EcoManagua</title><style>body{font-family:Arial;background:#f0f7f0;padding:2rem;}.card{background:white;border-radius:20px;padding:2rem;max-width:800px;margin:auto;}</style></head>
<body>
<div class="card"><h1>Detalle #<?php echo $row['id']; ?></h1><p><strong>Tipo:</strong> <?php echo $row['tipo']; ?></p><p><strong>Problema:</strong> <?php echo $row['tipo_problema']; ?></p><p><strong>Ubicación:</strong> <?php echo $row['ubicacion']; ?></p><p><strong>Barrio:</strong> <?php echo $row['barrio']; ?></p><p><strong>Descripción:</strong> <?php echo nl2br($row['descripcion']); ?></p><p><strong>Reportante:</strong> <?php echo $row['nombre_reportante']; ?></p><p><strong>Estado:</strong> <?php echo $row['estado']; ?></p><p><strong>Fecha:</strong> <?php echo $row['fecha_creacion']; ?></p><?php if($row['foto_path']): ?><img src="<?php echo $row['foto_path']; ?>" style="max-width:100%;border-radius:12px;"><?php endif; ?><br><a href="admin_dashboard.php">← Volver</a></div>
</body>
</html>