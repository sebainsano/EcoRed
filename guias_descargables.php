<?php
session_start();
$tipo_usuario = $_SESSION['tipo_usuario'] ?? 'colegio';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Guías Descargables - EcoManagua</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .container { display: flex; }
        .main { flex: 1; padding: 2rem; }
        .guias-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px,1fr)); gap: 1.5rem; }
        .guia-card { background: white; border-radius: 16px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .btn-descargar { background: #ff9800; color: white; padding: 8px 16px; border-radius: 50px; text-decoration: none; display: inline-block; margin-top: 1rem; }
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
        <h1>📥 Guías Educativas Descargables</h1>
        <p>Material gratuito para tu colegio</p>
        <div class="guias-grid">
            <div class="guia-card"><h3>♻️ Guía de Reciclaje en el Aula</h3><p>Aprende a implementar un sistema de reciclaje en tu salón de clases.</p><a href="#" class="btn-descargar">📥 Descargar PDF</a></div>
            <div class="guia-card"><h3>🌱 Manual de Huerto Escolar</h3><p>Cómo crear y mantener un huerto orgánico en tu colegio.</p><a href="#" class="btn-descargar">📥 Descargar PDF</a></div>
            <div class="guia-card"><h3>💧 Cuidado del Agua</h3><p>Actividades para promover el ahorro de agua en la escuela.</p><a href="#" class="btn-descargar">📥 Descargar PDF</a></div>
            <div class="guia-card"><h3>🗑️ Reducción de Residuos</h3><p>Estrategias para reducir la basura en el comedor escolar.</p><a href="#" class="btn-descargar">📥 Descargar PDF</a></div>
        </div>
    </div>
</div>
</body>
</html>