<?php
// Página inicial de selección de tipo de usuario
// No requiere login, solo elección
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoManagua - Distrito V</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            max-width: 1200px;
            width: 90%;
            margin: 2rem auto;
            text-align: center;
        }
        h1 {
            color: #2e7d32;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        .subtitle {
            color: #1b5e20;
            font-size: 1.2rem;
            margin-bottom: 3rem;
        }
        .cards {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            justify-content: center;
        }
        .card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            width: 300px;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            text-decoration: none;
            color: #333;
            display: block;
        }
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 30px rgba(0,0,0,0.15);
        }
        .card-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        .card h2 {
            color: #2e7d32;
            margin: 1rem 0;
        }
        .card p {
            color: #555;
            line-height: 1.5;
        }
        .comunidad {
            border-top: 8px solid #43a047;
        }
        .colegio {
            border-top: 8px solid #1565c0;
        }
        .footer {
            margin-top: 3rem;
            color: #2e7d32;
            font-size: 0.9rem;
        }
        @media (max-width: 700px) {
            .cards { flex-direction: column; align-items: center; }
            .card { width: 80%; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🌿 EcoManagua - Distrito V</h1>
        <div class="subtitle">Cuida tu ambiente, transforma tu comunidad educativa</div>
        
        <div class="cards">
            <a href="index.php?tipo=comunidad" class="card comunidad">
                <div class="card-icon">🌳🧑‍🤝‍🧑</div>
                <h2>Soy de la Comunidad</h2>
                <p>Accede a información ambiental, puntos de reciclaje, calendario de recolección, alertas y reportes ciudadanos del Distrito V.</p>
                <p><strong>Ver opciones para vecinos</strong> →</p>
            </a>
            
            <a href="index.php?tipo=colegio" class="card colegio">
                <div class="card-icon">🏫📢</div>
                <h2>Soy un Colegio</h2>
                <p>Reporta anomalías ambientales, descarga guías educativas, registra tu eco-club y gestiona denuncias desde tu centro escolar.</p>
                <p><strong>Acceder herramientas educativas</strong> →</p>
            </a>
        </div>
        
        <div class="footer">
            <p>📍 Distrito V de Managua | Promoviendo la gestión ambiental participativa</p>
        </div>
    </div>
</body>
</html>