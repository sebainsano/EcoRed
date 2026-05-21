<?php
session_start();
require_once 'conexion.php';
$tipo_usuario = $_SESSION['tipo_usuario'] ?? 'colegio';

// Ranking por colegios (agrupando por nombre_reportante que sea colegio)
$ranking_sql = "SELECT nombre_reportante, COUNT(*) as total 
                FROM reportes_denuncias 
                WHERE usuario_tipo = 'colegio' AND nombre_reportante != 'Anónimo'
                GROUP BY nombre_reportante 
                ORDER BY total DESC 
                LIMIT 10";
$ranking_result = mysqli_query($conexion, $ranking_sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ranking Ecológico - EcoManagua</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .container { display: flex; }
        .main { flex: 1; padding: 2rem; }
        .ranking-table { background: white; border-radius: 16px; overflow: hidden; width: 100%; }
        th, td { padding: 1rem; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #1b5e20; color: white; }
        .medal { font-size: 1.5rem; }
    </style>
</head>
<body>
<div class="container">
    <div class="sidebar">
        <h2>🌱 EcoManagua</h2>
        <a href="inicio_informativo.php?tipo=colegio">🏠 Inicio</a>
        <a href="crear_reporte.php?tipo=colegio">📍 Nuevo Reporte</a>
        <a href="crear_denuncia.php?tipo=colegio">⚠️ Nueva Denuncia</a>
        <a href="index.php" style="background:#ff9800;">🔄 Cambiar perfil</a>
    </div>
    <div class="main">
        <h1>🏆 Ranking Ecológico de Colegios</h1>
        <p>Los colegios más activos en la protección del ambiente en el Distrito V</p>
        <table class="ranking-table">
            <thead><tr><th>Posición</th><th>Colegio</th><th>Reportes/Denuncias</th></tr></thead>
            <tbody>
                <?php $pos = 1; while($row = mysqli_fetch_assoc($ranking_result)): ?>
                <tr>
                    <td class="medal"><?php if($pos==1) echo '🥇'; elseif($pos==2) echo '🥈'; elseif($pos==3) echo '🥉'; else echo $pos; ?></td>
                    <td><?php echo $row['nombre_reportante']; ?></td>
                    <td><?php echo $row['total']; ?> contribuciones</td>
                </tr>
                <?php $pos++; endwhile; ?>
                <?php if($pos == 1): ?>
                    <tr><td colspan="3" style="text-align:center">Aún no hay colegios registrados. ¡Sé el primero!</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
