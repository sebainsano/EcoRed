<?php
session_start();
// Si no hay tipo de usuario, redirigir al selector
if(!isset($_SESSION['tipo_usuario'])) {
    header("Location: index.php");
    exit;
}
$tipo_usuario = $_SESSION['tipo_usuario'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Educación Ambiental - EcoManagua | Distrito V</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f7f0;
        }
        
        .container {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar igual que en index */
        .sidebar {
            width: 260px;
            background: #1b5e20;
            color: white;
            padding: 1.5rem;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }
        
        .sidebar h2 {
            margin-bottom: 2rem;
            font-size: 1.5rem;
            text-align: center;
        }
        
        .sidebar a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: white;
            text-decoration: none;
            border-radius: 12px;
            margin-bottom: 8px;
            transition: background 0.3s;
        }
        
        .sidebar a:hover, .sidebar a.active {
            background: #2e7d32;
        }
        
        .main {
            flex: 1;
            margin-left: 260px;
            padding: 2rem;
        }
        
        .header {
            background: white;
            border-radius: 20px;
            padding: 1.5rem 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .header h1 {
            color: #1b5e20;
            margin-bottom: 0.5rem;
        }
        
        /* Grid de contenido educativo */
        .educacion-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .card-educativa {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .card-educativa:hover {
            transform: translateY(-5px);
        }
        
        .card-header {
            background: linear-gradient(135deg, #2e7d32, #1b5e20);
            color: white;
            padding: 1.5rem;
            text-align: center;
        }
        
        .card-header .icon {
            font-size: 3rem;
            margin-bottom: 0.5rem;
        }
        
        .card-header h3 {
            font-size: 1.3rem;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .card-body p {
            color: #555;
            line-height: 1.6;
            margin-bottom: 1rem;
        }
        
        .btn-ver {
            display: inline-block;
            background: #2e7d32;
            color: white;
            padding: 0.6rem 1.2rem;
            border-radius: 30px;
            text-decoration: none;
            margin-top: 0.5rem;
            transition: background 0.3s;
        }
        
        .btn-ver:hover {
            background: #1b5e20;
        }
        
        /* Sección de tips */
        .tips-section {
            background: #e8f5e9;
            border-radius: 20px;
            padding: 2rem;
            margin-top: 2rem;
        }
        
        .tips-section h2 {
            color: #1b5e20;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        
        .tips-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }
        
        .tip {
            background: white;
            padding: 1rem;
            border-radius: 16px;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .tip-emoji {
            font-size: 2rem;
        }
        
        .tip-text {
            flex: 1;
        }
        
        .tip-text strong {
            color: #2e7d32;
        }
        
        .cambiar-tipo {
            text-align: right;
            margin-bottom: 1rem;
        }
        
        .cambiar-tipo a {
            background: #ff9800;
            color: white;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: bold;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }
            .main {
                margin-left: 200px;
                padding: 1rem;
            }
            .educacion-grid {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 600px) {
            .sidebar {
                display: none;
            }
            .main {
                margin-left: 0;
            }
        }
        
        /* Banner del Distrito V */
        .distrito-banner {
            background: #c8e6c9;
            border-radius: 16px;
            padding: 1rem;
            text-align: center;
            margin-bottom: 2rem;
            border-left: 5px solid #2e7d32;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- SIDEBAR igual que en index -->
    <div class="sidebar">
        <h2>🌱 EcoManagua</h2>
        <a href="index.php">
            <span>🏠</span> Inicio
        </a>
        <a href="mapa.php">
            <span>🗺️</span> Mapa
        </a>
        <a href="crear_denuncia.php">
            <span>⚠️</span> Denunciar Anomalía
        </a>
        <a href="noticias.php">
            <span>📰</span> Noticias
        </a>
        <a href="instituciones.php">
            <span>🏛️</span> Instituciones
        </a>
        <a href="educacion_colegios.php" class="active">
            <span>📚</span> Centro Educativo
        </a>
        <a href="eco_clubes.php">
            <span>🏅</span> Eco-Clubes
        </a>
    </div>

    <div class="main">
        <!-- Botón cambiar tipo -->
        <div class="cambiar-tipo">
            <a href="index.php">🔄 Cambiar perfil</a>
        </div>
        
        <!-- Header -->
        <div class="header">
            <h1>📚 Centro de Educación Ambiental</h1>
            <p>Formando líderes ambientales en el Distrito V de Managua</p>
        </div>
        
        <!-- Banner Distrito V -->
        <div class="distrito-banner">
            🌎 <strong>Distrito V</strong> | Santa Lucía, El Dorado, Villa Fontana, San Judas, Altamisa, Reparto San Juan
        </div>
        
        <!-- Grid principal de contenido educativo -->
        <div class="educacion-grid">
            <!-- Tarjeta 1: Importancia ambiental -->
            <div class="card-educativa">
                <div class="card-header">
                    <div class="icon">🌍</div>
                    <h3>¿Por qué cuidar el ambiente?</h3>
                </div>
                <div class="card-body">
                    <p>El Distrito V enfrenta desafíos ambientales como acumulación de basura, quema de desechos y contaminación de agua. ¡Tú puedes ser parte de la solución!</p>
                    <p><strong>Dato clave:</strong> Un colegio que recicla puede reducir hasta 2 toneladas de CO2 al año.</p>
                    <a href="#" class="btn-ver">Leer más →</a>
                </div>
            </div>
            
            <!-- Tarjeta 2: Cómo denunciar anomalías -->
            <div class="card-educativa">
                <div class="card-header">
                    <div class="icon">⚠️</div>
                    <h3>Denuncias Ambientales</h3>
                </div>
                <div class="card-body">
                    <p>Aprende a identificar y reportar anomalías ambientales en tu comunidad escolar: basura ilegal, quema de basura, contaminación de aguas, ruido excesivo.</p>
                    <p><strong>Desde tu colegio</strong> puedes ser el héroe ambiental de tu barrio.</p>
                    <a href="crear_denuncia.php" class="btn-ver">Hacer una denuncia →</a>
                </div>
            </div>
            
            <!-- Tarjeta 3: Guías descargables -->
            <div class="card-educativa">
                <div class="card-header">
                    <div class="icon">📥</div>
                    <h3>Guías para Docentes</h3>
                </div>
                <div class="card-body">
                    <p>Material didáctico gratuito para integrar educación ambiental en tus clases. Incluye actividades prácticas, juegos y proyectos de reciclaje.</p>
                    <ul style="margin-top: 0.5rem; margin-left: 1.5rem; color: #555;">
                        <li>Guía de reciclaje en el aula</li>
                        <li>Manual de huertos escolares</li>
                        <li>Cuaderno de ecoretos</li>
                    </ul>
                    <a href="#" class="btn-ver">Descargar guías →</a>
                </div>
            </div>
        </div>
        
        <!-- Segunda fila de contenido -->
        <div class="educacion-grid">
            <!-- Tarjeta 4: Eco-clubes escolares -->
            <div class="card-educativa">
                <div class="card-header">
                    <div class="icon">🏅</div>
                    <h3>Eco-Clubes Escolares</h3>
                </div>
                <div class="card-body">
                    <p>Forma un grupo de estudiantes comprometidos con el ambiente. Organiza campañas de limpieza, reciclaje y reforestación en el Distrito V.</p>
                    <p><strong>Beneficio:</strong> Reconocimiento municipal y certificación ecológica para tu colegio.</p>
                    <a href="eco_clubes.php" class="btn-ver">Registrar mi club →</a>
                </div>
            </div>
            
            <!-- Tarjeta 5: Video educativo -->
            <div class="card-educativa">
                <div class="card-header">
                    <div class="icon">🎥</div>
                    <h3>Video: "Managua Limpia"</h3>
                </div>
                <div class="card-body">
                    <p>Mira este video educativo sobre cómo los colegios del Distrito V están marcando la diferencia.</p>
                    <div style="background: #e0e0e0; border-radius: 12px; padding: 1rem; text-align: center; margin: 1rem 0;">
                        🎬 [Reproductor de video]
                    </div>
                    <a href="#" class="btn-ver">Ver video completo →</a>
                </div>
            </div>
            
            <!-- Tarjeta 6: Calendario ambiental -->
            <div class="card-educativa">
                <div class="card-header">
                    <div class="icon">📅</div>
                    <h3>Calendario Ambiental</h3>
                </div>
                <div class="card-body">
                    <p>Próximas fechas importantes para tu colegio:</p>
                    <ul style="margin-top: 0.5rem; margin-left: 1.5rem; color: #555;">
                        <li><strong>5 de junio</strong> - Día del Ambiente</li>
                        <li><strong>17 de junio</strong> - Día de Lucha contra la Desertificación</li>
                        <li><strong>Septiembre</strong> - Semana del Reciclaje en Managua</li>
                    </ul>
                    <a href="#" class="btn-ver">Ver todos →</a>
                </div>
            </div>
        </div>
        
        <!-- Sección de tips rápidos -->
        <div class="tips-section">
            <h2>💡 Tips para tu colegio en el Distrito V</h2>
            <div class="tips-grid">
                <div class="tip">
                    <div class="tip-emoji">🗑️</div>
                    <div class="tip-text"><strong>Separa residuos</strong> - Coloca 3 contenedores: orgánico, reciclable y no reciclable.</div>
                </div>
                <div class="tip">
                    <div class="tip-emoji">💡</div>
                    <div class="tip-text"><strong>Ahorra energía</strong> - Apaga luces y equipos cuando no se usen.</div>
                </div>
                <div class="tip">
                    <div class="tip-emoji">🚰</div>
                    <div class="tip-text"><strong>Cuida el agua</strong> - Repara goteras y promueve su uso responsable.</div>
                </div>
                <div class="tip">
                    <div class="tip-emoji">🌱</div>
                    <div class="tip-text"><strong>Huerto escolar</strong> - Cultiva tus propios alimentos y aprende sobre compostaje.</div>
                </div>
            </div>
        </div>
        
        <!-- Frase motivacional -->
        <div style="text-align: center; margin-top: 2rem; padding: 1rem; background: #fff3e0; border-radius: 16px;">
            <p style="font-style: italic; color: #e65100;">"La educación ambiental no es solo aprender sobre el ambiente, es aprender a vivir en armonía con él"</p>
            <p style="margin-top: 0.5rem;">- EcoManagua Distrito V</p>
        </div>
    </div>
</div>

</body>
</html>