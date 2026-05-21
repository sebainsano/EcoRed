<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Éxito - EcoManagua</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            padding: 2rem;
        }
        .card {
            background: white;
            padding: 2.5rem;
            border-radius: 32px;
            text-align: center;
            max-width: 500px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .icono { font-size: 4rem; margin-bottom: 1rem; }
        .codigo {
            background: #f5f5f5;
            padding: 1rem;
            border-radius: 16px;
            font-family: monospace;
            font-size: 1.2rem;
            margin: 1rem 0;
        }
        .btn {
            display: inline-block;
            background: #ff9800;
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: 50px;
            text-decoration: none;
            margin: 0.5rem;
        }
        .btn-verde { background: #2e7d32; }
    </style>
</head>
<body>
<div class="card">
    <div class="icono">✅</div>
    <h2>¡<?php echo ucfirst($_GET['tipo'] ?? 'reporte'); ?> enviado con éxito!</h2>
    <p>Gracias por contribuir al cuidado del <strong>Distrito V de Managua</strong>.</p>
    <div class="codigo">📋 Código: <?php echo htmlspecialchars($_GET['codigo'] ?? '---'); ?></div>
    <a href="inicio_informativo.php?tipo=<?php echo $_SESSION['tipo_usuario'] ?? 'comunidad'; ?>" class="btn">← Volver al inicio</a>
    <a href="mis_reportes.php" class="btn btn-verde">📋 Ver mis reportes</a>
</div>
</body>
</html>