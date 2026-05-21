<?php
session_start();
require_once 'conexion.php';

$stats = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT 
    COUNT(CASE WHEN tipo='reporte' THEN 1 END) as reportes,
    COUNT(CASE WHEN tipo='denuncia' THEN 1 END) as denuncias,
    COUNT(CASE WHEN estado='pendiente' THEN 1 END) as pendientes,
    COUNT(CASE WHEN estado='en_proceso' THEN 1 END) as proceso,
    COUNT(CASE WHEN estado='resuelto' THEN 1 END) as resueltos
FROM reportes_denuncias"));

$resultados = mysqli_query($conexion, "SELECT * FROM reportes_denuncias ORDER BY fecha_creacion DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Admin - EcoManagua</title>
    <style>
        *{margin:0;padding:0;box-sizing:border-box;}
        body{font-family:'Segoe UI',sans-serif;background:#f0f7f0;}
        .header{background:#1b5e20;color:white;padding:1rem 2rem;display:flex;justify-content:space-between;}
        .stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:1rem;padding:2rem;}
        .stat-card{background:white;padding:1.5rem;border-radius:16px;text-align:center;box-shadow:0 2px 8px rgba(0,0,0,0.1);}
        .stat-number{font-size:2rem;font-weight:bold;color:#2e7d32;}
        .tabla-container{padding:2rem;overflow-x:auto;}
        table{width:100%;background:white;border-radius:16px;overflow:hidden;}
        th,td{padding:1rem;text-align:left;border-bottom:1px solid #eee;}
        th{background:#2e7d32;color:white;}
        .badge{padding:0.3rem 0.8rem;border-radius:50px;font-size:0.8rem;}
        .pendiente{background:#ff9800;color:white;}
        .en_proceso{background:#2196f3;color:white;}
        .resuelto{background:#4caf50;color:white;}
        select{padding:0.3rem;border-radius:8px;}
        .btn-actualizar{background:#ff9800;border:none;padding:0.3rem 0.8rem;border-radius:8px;cursor:pointer;}
    </style>
</head>
<body>
<div class="header"><h1>🌱 Panel Administración - EcoManagua Distrito V</h1><a href="index.php" style="color:white;">Salir</a></div>
<div class="stats">
    <div class="stat-card"><div class="stat-number"><?php echo $stats['reportes']; ?></div><p>Reportes</p></div>
    <div class="stat-card"><div class="stat-number"><?php echo $stats['denuncias']; ?></div><p>Denuncias</p></div>
    <div class="stat-card"><div class="stat-number"><?php echo $stats['pendientes']; ?></div><p>Pendientes</p></div>
    <div class="stat-card"><div class="stat-number"><?php echo $stats['proceso']; ?></div><p>En Proceso</p></div>
    <div class="stat-card"><div class="stat-number"><?php echo $stats['resueltos']; ?></div><p>Resueltos</p></div>
</div>
<div class="tabla-container">
    <h2>📋 Todos los Reportes y Denuncias</h2>
    <table><thead><tr><th>ID</th><th>Tipo</th><th>Problema</th><th>Barrio</th><th>Reportante</th><th>Estado</th><th>Fecha</th><th>Acciones</th></tr></thead>
    <tbody><?php while($row = mysqli_fetch_assoc($resultados)): ?>
        <tr><td><?php echo $row['id']; ?></td><td><?php echo ucfirst($row['tipo']); ?></td><td><?php echo $row['tipo_problema']; ?></td><td><?php echo $row['barrio']; ?></td><td><?php echo $row['nombre_reportante']; ?></td>
        <td><form method="POST" action="actualizar_estado.php" style="display:flex;gap:0.5rem;"><input type="hidden" name="id" value="<?php echo $row['id']; ?>"><select name="estado"><option <?php echo $row['estado']=='pendiente'?'selected':''; ?>>pendiente</option><option <?php echo $row['estado']=='en_proceso'?'selected':''; ?>>en_proceso</option><option <?php echo $row['estado']=='resuelto'?'selected':''; ?>>resuelto</option></select><button type="submit" class="btn-actualizar">Actualizar</button></form></td>
        <td><?php echo date('d/m/Y', strtotime($row['fecha_creacion'])); ?></td><td><a href="ver_detalle.php?id=<?php echo $row['id']; ?>">Ver</a></td></tr>
    <?php endwhile; ?></tbody>
    </table>
</div>
</body>
</html>