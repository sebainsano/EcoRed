<?php
session_start();
$tipo_usuario = $_SESSION['tipo_usuario'] ?? 'colegio';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eco-Clubes - EcoManagua</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .container { display: flex; }
        .main { flex: 1; padding: 2rem; }
        form { background: white; padding: 2rem; border-radius: 20px; max-width: 500px; }
        input, select { width: 100%; padding: 10px; margin-bottom: 1rem; border-radius: 10px; border: 1px solid #ddd; }
        button { background: #ff9800; color: white; padding: 12px; border: none; border-radius: 50px; cursor: pointer; width: 100%; }
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
        <h1>🏅 Registrar Eco-Club Escolar</h1>
        <p>Forma parte de la red de colegios ambientalmente responsables del Distrito V</p>
        <form method="POST">
            <input type="text" name="colegio" placeholder="Nombre del colegio" required>
            <input type="text" name="club" placeholder="Nombre del Eco-Club" required>
            <input type="text" name="profesor" placeholder="Profesor responsable" required>
            <input type="email" name="email" placeholder="Correo de contacto" required>
            <input type="number" name="estudiantes" placeholder="Cantidad de estudiantes">
            <button type="submit">📢 Registrar Eco-Club</button>
        </form>
        <?php if($_SERVER['REQUEST_METHOD']=='POST'): ?>
            <div style="background: #e8f5e9; padding: 1rem; border-radius: 12px; margin-top: 1rem; text-align: center;">
                ✅ ¡Eco-Club registrado exitosamente! Pronto recibirás información.
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>