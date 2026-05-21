<?php
session_start();
require_once 'conexion.php';

$buscar = $_GET['buscar'] ?? '';
$reportes = [];
if (!empty($buscar)) {
    $buscar = mysqli_real_escape_string($conexion, $buscar);
    $query = "SELECT * FROM reportes_denuncias 
              WHERE nombre_reportante LIKE '%$buscar%' 
                 OR email LIKE '%$buscar%'
                 OR id = '$buscar'
              ORDER BY fecha_creacion DESC";
    $resultado = mysqli_query($conexion, $query);
    $reportes = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Reportes - EcoManagua</title>
    <style>
        *{margin:0;padding:0;box-sizing:border-box;}
        body{font-family:'Segoe UI',sans-serif;background:#f0f7f0;padding:2rem;}
        .container{max-width:900px;margin:0 auto;}
        .header{background:white;border-radius:20px;padding:1.5rem;margin-bottom:2rem;}
        .buscar-form{display:flex;gap:1rem;margin-bottom:2rem;}
        .buscar-form input{flex:1;padding:0.8rem;border-radius:50px;border:1px solid #ddd;}
        .buscar-form button{background:#ff9800;border:none;padding:0.8rem 1.5rem;border-radius:50px;cursor:pointer;color:white;font-weight:bold;}
        .reporte-card{background:white;border-radius:16px;padding:1.5rem;margin-bottom:1rem;box-shadow:0 2px 8px rgba(0,0,0,0.1);}
        .badge{padding:0.3rem 0.8rem;border-radius:50px;font-size:0.8rem;}
        .pendiente{background:#ff9800;color:white;}
        .en_proceso{background:#2196f3;color:white;}
        .resuelto{background:#4caf50;color:white;}
        .btn-volver{background:#2e7d32;color:white;padding:0.5rem 1rem;border-radius:50px;text-decoration:none;display:inline-block;}
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>📋 Consultar Reportes y Denuncias</h1>
        <p>Ingresa tu nombre, correo o el código de seguimiento</p>
        <a href="inicio_informativo.php?tipo=<?php echo $_SESSION['tipo_usuario'] ?? 'comunidad'; ?>" class="btn-volver">← Volver al inicio</a>
    </div>
    
    <form method="GET" class="buscar-form">
        <input type="text" name="buscar" placeholder="Tu nombre, correo o código (ej: REP-00001)" value="<?php echo htmlspecialchars($buscar); ?>">
        <button type="submit">🔍 Buscar</button>
    </form>
    
    <?php if ($buscar && empty($reportes)): ?>
        <div class="reporte-card" style="text-align:center;"><p>⚠️ No se encontraron reportes con "<?php echo htmlspecialchars($buscar); ?>"</p></div>
    <?php endif; ?>
    
    <?php foreach ($reportes as $r): ?>
        <div class="reporte-card">
            <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;">
                <h3><?php echo $r['tipo'] == 'reporte' ? '📍' : '⚠️'; ?> <?php echo ucfirst($r['tipo']); ?>: <?php echo $r['tipo_problema']; ?></h3>
                <span class="badge <?php echo $r['estado']; ?>"><?php echo ucfirst(str_replace('_', ' ', $r['estado'])); ?></span>
            </div>
            <p><strong>📍 Ubicación:</strong> <?php echo $r['ubicacion']; ?></p>
            <p><strong>🏘️ Barrio:</strong> <?php echo $r['barrio']; ?></p>
            <p><strong>📝 Descripción:</strong> <?php echo substr($r['descripcion'], 0, 150); ?>...</p>
            <p><strong>📅 Fecha:</strong> <?php echo date('d/m/Y H:i', strtotime($r['fecha_creacion'])); ?></p>
            <?php if ($r['foto_path']): ?>
                <a href="<?php echo $r['foto_path']; ?>" target="_blank">📸 Ver foto</a>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>