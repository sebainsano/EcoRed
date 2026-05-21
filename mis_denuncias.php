<?php
// ============================================
// SIN AUTENTICACIÓN - SOLO DISEÑO
// ============================================

// Datos de prueba estáticos para mostrar el diseño
$usuario_nombre = "Visitante";
$stats = [
    'total' => 24,
    'pendientes' => 6,
    'en_proceso' => 8,
    'resueltas' => 10
];

$denuncias_prueba = [
    ['id' => 1, 'tipo' => 'Acumulación de basura', 'ubicacion' => 'Barrio Riguero', 'fecha' => '2024-05-15', 'estado' => 'pendiente'],
    ['id' => 2, 'tipo' => 'Quema de desechos', 'ubicacion' => 'Col. Centroamérica', 'fecha' => '2024-05-31', 'estado' => 'en_proceso'],
    ['id' => 3, 'tipo' => 'Contaminación de agua', 'ubicacion' => 'Laguna de Tiscapa', 'fecha' => '2024-05-28', 'estado' => 'en_proceso'],
    ['id' => 4, 'tipo' => 'Animal muerto en vía pública', 'ubicacion' => 'Villa Libertad', 'fecha' => '2024-04-28', 'estado' => 'resuelta'],
    ['id' => 5, 'tipo' => 'Contaminación de aire', 'ubicacion' => 'Las Brisas', 'fecha' => '2024-05-20', 'estado' => 'pendiente'],
];

$perfil_prueba = [
    'email' => 'visitante@ecomanagua.com',
    'telefono' => 'No especificado',
    'direccion' => 'Distrito 1, Managua',
    'fecha_registro' => '2024-05-01'
];

function getIconoPorTipo($tipo) {
    $tipo_lower = strtolower($tipo);
    if (strpos($tipo_lower, 'basura') !== false) return '🗑️';
    if (strpos($tipo_lower, 'quema') !== false) return '🔥';
    if (strpos($tipo_lower, 'animal') !== false) return '🐾';
    if (strpos($tipo_lower, 'agua') !== false) return '💧';
    if (strpos($tipo_lower, 'aire') !== false) return '🌫️';
    return '📍';
}

function getClaseBadge($estado) {
    $estado_lower = strtolower($estado);
    if ($estado_lower == 'pendiente') return 'yellow';
    if ($estado_lower == 'en_proceso') return 'blue';
    if ($estado_lower == 'resuelta') return 'green';
    return 'yellow';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoManagua - Mis Denuncias | Distrito 1</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .stats-mini {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        .stat-mini-card {
            background: white;
            padding: 15px 25px;
            border-radius: 16px;
            text-align: center;
            flex: 1;
            min-width: 100px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            cursor: pointer;
            border-left: 4px solid;
        }
        .stat-mini-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.1);
        }
        .stat-mini-card .numero {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2c3e50;
        }
        .stat-mini-card .etiqueta {
            font-size: 0.8rem;
            color: #7f8c8d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .stat-mini-card.todas { border-left-color: #3498db; }
        .stat-mini-card.pendientes { border-left-color: #f39c12; }
        .stat-mini-card.proceso { border-left-color: #e74c3c; }
        .stat-mini-card.resueltas { border-left-color: #27ae60; }

        .two-columns {
            display: flex;
            gap: 24px;
            margin-top: 20px;
        }
        .denuncias-list-large {
            flex: 2;
            background: white;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .right-sidebar {
            flex: 1.2;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }
        .info-card {
            background: white;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .info-card h3 {
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #ecf0f1;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .denuncia-item-mis {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #f0f2f5;
        }
        .denuncia-item-mis:last-child {
            border-bottom: none;
        }
        .denuncia-info-mis h4 {
            color: #2c3e50;
            margin-bottom: 5px;
        }
        .denuncia-info-mis p {
            font-size: 0.8rem;
            color: #95a5a6;
        }
        .btn-detalle {
            background: #ecf0f1;
            padding: 6px 14px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 0.75rem;
            font-weight: 600;
            color: #2c3e50;
            transition: all 0.2s;
        }
        .btn-detalle:hover {
            background: #1b5e4a;
            color: white;
        }
        .profile-info p {
            margin: 12px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .logout-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            padding: 12px;
            background: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .logout-link:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }
        .badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            display: inline-block;
        }
        .badge.yellow { background: #fff3cd; color: #856404; }
        .badge.blue { background: #cce5ff; color: #004085; }
        .badge.green { background: #d4edda; color: #155724; }
        @media (max-width: 900px) {
            .two-columns {
                flex-direction: column;
            }
            .stats-mini {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

<div class="container">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h2>🌱 EcoManagua</h2>
        <a href="index.php">
            <span>🏠</span> Inicio
        </a>
        <a href="mapa.php">
            <span>🗺️</span> Mapa
        </a>
        <a href="mis_denuncias.php" class="active">
            <span>📋</span> Mis Denuncias
        </a>
        <a href="crear_denuncia.php">
            <span>➕</span> Nueva Denuncia
        </a>
        <a href="noticias.php">
            <span>📰</span> Noticias
        </a>
        <a href="instituciones.php">
            <span>🏛️</span> Instituciones
        </a>
    </div>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="main">

        <div class="header">
            <h1>📋 Mis Denuncias</h1>
            <div class="user-icons">
                <span>👤 <?php echo $usuario_nombre; ?></span>
                <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Avatar">
                <span>🔔</span>
            </div>
        </div>

        <p style="color: #7f8c8d; margin-bottom: 25px; font-size: 0.95rem;">
            Historial y estado de tus reportes ambientales en el Distrito 1
        </p>

        <!-- Estadísticas -->
        <div class="stats-mini">
            <div class="stat-mini-card todas">
                <div class="numero"><?php echo $stats['total']; ?></div>
                <div class="etiqueta">Todas</div>
            </div>
            <div class="stat-mini-card pendientes">
                <div class="numero"><?php echo $stats['pendientes']; ?></div>
                <div class="etiqueta">Pendientes</div>
            </div>
            <div class="stat-mini-card proceso">
                <div class="numero"><?php echo $stats['en_proceso']; ?></div>
                <div class="etiqueta">En Proceso</div>
            </div>
            <div class="stat-mini-card resueltas">
                <div class="numero"><?php echo $stats['resueltas']; ?></div>
                <div class="etiqueta">Resueltas</div>
            </div>
        </div>

        <div class="two-columns">
            
            <!-- LISTA DE DENUNCIAS -->
            <div class="denuncias-list-large">
                <h3 style="margin-bottom: 15px;">📝 Mis Denuncias</h3>
                
                <?php foreach($denuncias_prueba as $denuncia): ?>
                    <div class="denuncia-item-mis">
                        <div class="denuncia-info-mis">
                            <h4>
                                <?php echo getIconoPorTipo($denuncia['tipo']) . ' ' . htmlspecialchars($denuncia['tipo']); ?>
                            </h4>
                            <p>📍 <?php echo htmlspecialchars($denuncia['ubicacion']); ?> · <?php echo date('d M Y', strtotime($denuncia['fecha'])); ?></p>
                            <span class="badge <?php echo getClaseBadge($denuncia['estado']); ?>">
                                <?php echo ucfirst(htmlspecialchars($denuncia['estado'])); ?>
                            </span>
                        </div>
                        <a href="ver_denuncia.php?id=<?php echo $denuncia['id']; ?>" class="btn-detalle">Ver Detalle →</a>
                    </div>
                <?php endforeach; ?>

                <button class="btn" style="margin-top: 20px; background: #ecf0f1; color: #2c3e50;" onclick="alert('Cargar más denuncias próximamente')">
                    📄 Cargar más denuncias
                </button>
            </div>

            <!-- COLUMNA DERECHA -->
            <div class="right-sidebar">
                
                <div class="info-card">
                    <h3>📰 Noticias Ambientales</h3>
                    <div class="denuncia-item-mis" style="padding: 12px 0;">
                        <div class="denuncia-info-mis">
                            <h4>🌿 Jornada de limpieza</h4>
                            <p>Este sábado en el Barrio Riguero</p>
                            <small>Hace 2 días</small>
                        </div>
                        <a href="#" class="btn-detalle">Leer</a>
                    </div>
                    <div class="denuncia-item-mis" style="padding: 12px 0;">
                        <div class="denuncia-info-mis">
                            <h4>🚯 Nueva ordenanza ambiental</h4>
                            <p>Multas por quema de basura</p>
                            <small>Hace 5 días</small>
                        </div>
                        <a href="#" class="btn-detalle">Leer</a>
                    </div>
                    <div class="denuncia-item-mis" style="padding: 12px 0;">
                        <div class="denuncia-info-mis">
                            <h4>💧 Recuperación Laguna Tiscapa</h4>
                            <p>Plan de limpieza en curso</p>
                            <small>Hace 1 semana</small>
                        </div>
                        <a href="#" class="btn-detalle">Leer</a>
                    </div>
                </div>

                <div class="info-card">
                    <h3>👤 Mi Perfil</h3>
                    <div class="profile-info">
                        <p><span>📧</span> <?php echo $perfil_prueba['email']; ?></p>
                        <p><span>📱</span> <?php echo $perfil_prueba['telefono']; ?></p>
                        <p><span>📍</span> <?php echo $perfil_prueba['direccion']; ?></p>
                        <p><span>📅</span> Miembro desde <?php echo date('M Y', strtotime($perfil_prueba['fecha_registro'])); ?></p>
                        <p><span>✅</span> <?php echo $stats['total']; ?> denuncias realizadas</p>
                    </div>
                    <a href="editar_perfil.php" class="btn-detalle" style="display: inline-block; margin-top: 10px;">✏️ Editar perfil</a>
                </div>

                <a href="index.php" class="logout-link">
                    🚪 Cerrar Sesión
                </a>
            </div>
        </div>
    </div>
</div>

</body>
</html>