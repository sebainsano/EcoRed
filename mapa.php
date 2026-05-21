<?php
session_start();
require_once 'conexion.php';

$tipo_usuario = $_SESSION['tipo_usuario'] ?? 'comunidad';

// Obtener filtros
$filtro_tipo = $_GET['tipo'] ?? 'todos';
$filtro_estado = $_GET['estado'] ?? 'todos';

// Construir consulta SQL
$sql = "SELECT * FROM reportes_denuncias 
        WHERE latitud IS NOT NULL 
        AND longitud IS NOT NULL 
        AND latitud != 0 
        AND longitud != 0";

if ($filtro_tipo != 'todos') {
    $sql .= " AND tipo = '" . mysqli_real_escape_string($conexion, $filtro_tipo) . "'";
}
if ($filtro_estado != 'todos') {
    $sql .= " AND estado = '" . mysqli_real_escape_string($conexion, $filtro_estado) . "'";
}

$sql .= " ORDER BY fecha_creacion DESC";
$resultado = mysqli_query($conexion, $sql);
$reportes = [];
while ($row = mysqli_fetch_assoc($resultado)) {
    $reportes[] = $row;
}

// Obtener estadísticas
$stats_sql = "SELECT 
    COUNT(*) as total,
    COUNT(CASE WHEN tipo = 'reporte' THEN 1 END) as reportes,
    COUNT(CASE WHEN tipo = 'denuncia' THEN 1 END) as denuncias,
    COUNT(CASE WHEN estado = 'pendiente' THEN 1 END) as pendientes,
    COUNT(CASE WHEN estado = 'en_proceso' THEN 1 END) as en_proceso,
    COUNT(CASE WHEN estado = 'resuelto' THEN 1 END) as resueltos
FROM reportes_denuncias 
WHERE latitud IS NOT NULL AND longitud IS NOT NULL";
$stats_result = mysqli_query($conexion, $stats_sql);
$stats = mysqli_fetch_assoc($stats_result);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mapa de Reportes y Denuncias - EcoManagua Distrito V</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            height: 100%;
            overflow: hidden;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f7f0;
        }
        
        /* Contenedor principal con flex */
        .map-container-wrapper {
            display: flex;
            width: 100%;
            height: 100vh;
            overflow: hidden;
        }
        
        /* Sidebar - ancho fijo y scroll independiente */
        .sidebar {
            width: 340px;
            min-width: 340px;
            background: white;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow-y: auto;
            z-index: 10;
            position: relative;
        }
        
        /* Para pantallas pequeñas, sidebar más angosto */
        @media (max-width: 768px) {
            .sidebar {
                width: 280px;
                min-width: 280px;
            }
        }
        
        /* Para pantallas muy pequeñas, sidebar oculto con botón */
        @media (max-width: 600px) {
            .sidebar {
                position: absolute;
                left: -280px;
                transition: left 0.3s ease;
                z-index: 100;
                height: 100vh;
                box-shadow: 2px 0 15px rgba(0,0,0,0.2);
            }
            .sidebar.open {
                left: 0;
            }
            .btn-menu-movil {
                display: block;
                position: absolute;
                top: 10px;
                left: 10px;
                z-index: 101;
                background: #1b5e20;
                color: white;
                border: none;
                padding: 10px 15px;
                border-radius: 8px;
                cursor: pointer;
                font-size: 1.2rem;
            }
        }
        
        .btn-menu-movil {
            display: none;
        }
        
        .sidebar-header {
            background: #1b5e20;
            color: white;
            padding: 1.5rem;
            text-align: center;
            position: sticky;
            top: 0;
            z-index: 5;
        }
        
        .sidebar-header h2 {
            margin-bottom: 0.5rem;
            font-size: 1.3rem;
        }
        
        .sidebar-header p {
            font-size: 0.85rem;
            opacity: 0.9;
        }
        
        /* Estadísticas */
        .stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            padding: 1rem;
            background: #f8f9fa;
            border-bottom: 1px solid #e0e4e8;
        }
        
        .stat-item {
            text-align: center;
            padding: 10px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .stat-number {
            font-size: 1.5rem;
            font-weight: bold;
        }
        
        .stat-reporte { color: #ff9800; }
        .stat-denuncia { color: #c62828; }
        .stat-pendiente { color: #ff9800; }
        .stat-proceso { color: #2196f3; }
        .stat-resuelto { color: #4caf50; }
        
        /* Filtros */
        .filtros {
            padding: 1rem;
            border-bottom: 1px solid #e0e4e8;
            background: white;
        }
        
        .filtros h3 {
            margin-bottom: 1rem;
            color: #2c3e50;
            font-size: 1rem;
        }
        
        .filtro-group {
            margin-bottom: 1rem;
        }
        
        .filtro-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            font-size: 0.85rem;
            color: #555;
        }
        
        .filtro-group select {
            width: 100%;
            padding: 8px 12px;
            border: 2px solid #e0e4e8;
            border-radius: 10px;
            font-size: 0.9rem;
            background: white;
            cursor: pointer;
        }
        
        .btn-filtrar {
            width: 100%;
            padding: 10px;
            background: #ff9800;
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 0.5rem;
        }
        
        .btn-filtrar:hover {
            background: #e65100;
            transform: scale(1.02);
        }
        
        /* Lista de reportes */
        .reportes-lista {
            flex: 1;
            overflow-y: auto;
            padding: 1rem;
            background: #fff;
        }
        
        .reportes-lista h3 {
            margin-bottom: 1rem;
            font-size: 0.9rem;
            color: #555;
            position: sticky;
            top: 0;
            background: white;
            padding: 5px 0;
        }
        
        .reporte-item {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 12px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.3s;
            border-left: 4px solid #ccc;
        }
        
        .reporte-item:hover {
            background: #e8f5e9;
            transform: translateX(5px);
        }
        
        .reporte-item.reporte { border-left-color: #ff9800; }
        .reporte-item.denuncia { border-left-color: #c62828; }
        
        .reporte-tipo {
            font-weight: bold;
            margin-bottom: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .reporte-tipo span {
            font-size: 0.7rem;
            padding: 2px 8px;
            border-radius: 20px;
            color: white;
        }
        
        .pendiente { background: #ff9800; }
        .en_proceso { background: #2196f3; }
        .resuelto { background: #4caf50; }
        
        .reporte-ubicacion {
            font-size: 0.8rem;
            color: #666;
            margin-top: 5px;
            word-break: break-word;
        }
        
        .reporte-fecha {
            font-size: 0.7rem;
            color: #999;
            margin-top: 5px;
        }
        
        /* Mapa */
        .map-container {
            flex: 1;
            position: relative;
            height: 100vh;
        }
        
        #map {
            height: 100%;
            width: 100%;
        }
        
        /* Leyenda */
        .legend {
            position: absolute;
            bottom: 20px;
            right: 20px;
            background: white;
            padding: 12px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            z-index: 1000;
            font-size: 0.8rem;
        }
        
        .legend h4 {
            margin-bottom: 8px;
            font-size: 0.85rem;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 5px;
        }
        
        .legend-color {
            width: 20px;
            height: 20px;
            border-radius: 50%;
        }
        
        .legend-color.reporte { background: #ff9800; }
        .legend-color.denuncia { background: #c62828; }
        .legend-color.pendiente { background: #ff9800; border: 2px solid #333; }
        .legend-color.proceso { background: #2196f3; }
        .legend-color.resuelto { background: #4caf50; }
        
        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 2000;
            justify-content: center;
            align-items: center;
        }
        
        .modal-content {
            background: white;
            border-radius: 24px;
            padding: 2rem;
            max-width: 500px;
            max-height: 80vh;
            overflow-y: auto;
            position: relative;
            margin: 20px;
        }
        
        .modal-close {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 1.5rem;
            cursor: pointer;
            color: #999;
        }
        
        .modal-close:hover { color: #333; }
        
        .modal h3 { color: #1b5e20; margin-bottom: 1rem; }
        .modal hr { margin: 1rem 0; border-color: #eee; }
        
        .volver-btn {
            display: inline-block;
            margin-top: 1rem;
            padding: 8px 16px;
            background: #ff9800;
            color: white;
            text-decoration: none;
            border-radius: 20px;
            font-size: 0.8rem;
            text-align: center;
        }
        
        .volver-btn:hover {
            background: #e65100;
        }
        
        /* Scrollbar personalizada */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }
        
        .sidebar::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</head>
<body>

<button class="btn-menu-movil" onclick="toggleSidebar()">☰ Menú</button>

<div class="map-container-wrapper">
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h2>🗺️ EcoManagua - Distrito V</h2>
            <p>Reportes y Denuncias Ambientales</p>
            <a href="inicio_informativo.php?tipo=<?php echo $tipo_usuario; ?>" class="volver-btn">← Volver al inicio</a>
        </div>
        
        <!-- Estadísticas -->
        <div class="stats">
            <div class="stat-item">
                <div class="stat-number stat-reporte"><?php echo $stats['reportes']; ?></div>
                <div>📍 Reportes</div>
            </div>
            <div class="stat-item">
                <div class="stat-number stat-denuncia"><?php echo $stats['denuncias']; ?></div>
                <div>⚠️ Denuncias</div>
            </div>
            <div class="stat-item">
                <div class="stat-number stat-pendiente"><?php echo $stats['pendientes']; ?></div>
                <div>⏳ Pendientes</div>
            </div>
            <div class="stat-item">
                <div class="stat-number stat-resuelto"><?php echo $stats['resueltos']; ?></div>
                <div>✅ Resueltos</div>
            </div>
        </div>
        
        <!-- Filtros -->
        <div class="filtros">
            <h3>🔍 Filtrar por:</h3>
            <form method="GET" id="filtroForm">
                <div class="filtro-group">
                    <label>Tipo:</label>
                    <select name="tipo" id="filtroTipo" onchange="this.form.submit()">
                        <option value="todos" <?php echo $filtro_tipo == 'todos' ? 'selected' : ''; ?>>Todos</option>
                        <option value="reporte" <?php echo $filtro_tipo == 'reporte' ? 'selected' : ''; ?>>📍 Reportes</option>
                        <option value="denuncia" <?php echo $filtro_tipo == 'denuncia' ? 'selected' : ''; ?>>⚠️ Denuncias</option>
                    </select>
                </div>
                <div class="filtro-group">
                    <label>Estado:</label>
                    <select name="estado" id="filtroEstado" onchange="this.form.submit()">
                        <option value="todos" <?php echo $filtro_estado == 'todos' ? 'selected' : ''; ?>>Todos</option>
                        <option value="pendiente" <?php echo $filtro_estado == 'pendiente' ? 'selected' : ''; ?>>⏳ Pendiente</option>
                        <option value="en_proceso" <?php echo $filtro_estado == 'en_proceso' ? 'selected' : ''; ?>>🔄 En Proceso</option>
                        <option value="resuelto" <?php echo $filtro_estado == 'resuelto' ? 'selected' : ''; ?>>✅ Resuelto</option>
                    </select>
                </div>
                <noscript>
                    <button type="submit" class="btn-filtrar">Aplicar Filtros</button>
                </noscript>
            </form>
        </div>
        
        <!-- Lista de reportes -->
        <div class="reportes-lista">
            <h3>📋 Lista de casos (<?php echo count($reportes); ?>)</h3>
            <?php if (count($reportes) == 0): ?>
                <div style="text-align: center; padding: 2rem; color: #999;">
                    No hay reportes o denuncias con ubicación.
                </div>
            <?php endif; ?>
            <?php foreach ($reportes as $r): ?>
                <div class="reporte-item <?php echo $r['tipo']; ?>" data-id="<?php echo $r['id']; ?>" data-lat="<?php echo $r['latitud']; ?>" data-lng="<?php echo $r['longitud']; ?>">
                    <div class="reporte-tipo">
                        <?php echo $r['tipo'] == 'reporte' ? '📍' : '⚠️'; ?> <?php echo htmlspecialchars(substr($r['tipo_problema'], 0, 30)); ?>
                        <span class="<?php echo $r['estado']; ?>"><?php echo ucfirst(str_replace('_', ' ', $r['estado'])); ?></span>
                    </div>
                    <div class="reporte-ubicacion">📍 <?php echo htmlspecialchars(substr($r['ubicacion'], 0, 50)); ?>...</div>
                    <div class="reporte-fecha">📅 <?php echo date('d/m/Y', strtotime($r['fecha_creacion'])); ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Mapa -->
    <div class="map-container">
        <div id="map"></div>
        
        <!-- Leyenda -->
        <div class="legend">
            <h4>Leyenda</h4>
            <div class="legend-item"><div class="legend-color reporte"></div><span>Reporte</span></div>
            <div class="legend-item"><div class="legend-color denuncia"></div><span>Denuncia</span></div>
            <div class="legend-item"><div class="legend-color pendiente"></div><span>Pendiente</span></div>
            <div class="legend-item"><div class="legend-color proceso"></div><span>En Proceso</span></div>
            <div class="legend-item"><div class="legend-color resuelto"></div><span>Resuelto</span></div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="modal" class="modal">
    <div class="modal-content">
        <span class="modal-close" onclick="cerrarModal()">&times;</span>
        <div id="modalContenido"></div>
    </div>
</div>

<script>
    // Función para móviles
    function toggleSidebar() {
        var sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('open');
    }
    
    // Cerrar sidebar al hacer clic fuera en móvil
    document.addEventListener('click', function(event) {
        var sidebar = document.getElementById('sidebar');
        var btnMenu = document.querySelector('.btn-menu-movil');
        if (window.innerWidth <= 600) {
            if (!sidebar.contains(event.target) && !btnMenu.contains(event.target)) {
                sidebar.classList.remove('open');
            }
        }
    });
    
    // Inicializar mapa
    var map = L.map('map').setView([12.1364, -86.2512], 13);
    
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a>',
        subdomains: 'abcd',
        maxZoom: 19
    }).addTo(map);
    
    // Función para obtener color según tipo y estado
    function getMarkerColor(tipo, estado) {
        if (tipo === 'reporte') {
            if (estado === 'resuelto') return '#4caf50';
            if (estado === 'en_proceso') return '#2196f3';
            return '#ff9800';
        } else {
            if (estado === 'resuelto') return '#4caf50';
            if (estado === 'en_proceso') return '#2196f3';
            return '#c62828';
        }
    }
    
    // Icono personalizado
    function createCustomIcon(tipo, estado) {
        var color = getMarkerColor(tipo, estado);
        return L.divIcon({
            html: `<div style="
                background-color: ${color};
                width: 30px;
                height: 30px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 2px 6px rgba(0,0,0,0.3);
                border: 2px solid white;
                font-size: 14px;
                font-weight: bold;
            ">${tipo === 'reporte' ? '📍' : '⚠️'}</div>`,
            iconSize: [30, 30],
            popupAnchor: [0, -15]
        });
    }
    
    // Datos de reportes desde PHP
    var reportes = <?php echo json_encode($reportes); ?>;
    var markers = {};
    var markerLayerGroup = L.layerGroup().addTo(map);
    
    // Agregar marcadores
    reportes.forEach(function(reporte) {
        if (reporte.latitud && reporte.longitud && reporte.latitud != 0) {
            var lat = parseFloat(reporte.latitud);
            var lng = parseFloat(reporte.longitud);
            var icon = createCustomIcon(reporte.tipo, reporte.estado);
            var marker = L.marker([lat, lng], { icon: icon })
                .bindPopup(`
                    <div style="min-width: 220px;">
                        <strong style="color:${getMarkerColor(reporte.tipo, reporte.estado)}">${reporte.tipo === 'reporte' ? '📍 REPORTE' : '⚠️ DENUNCIA'}</strong><br>
                        <strong>${reporte.tipo_problema}</strong><br>
                        📍 ${reporte.ubicacion.substring(0, 80)}<br>
                        🏘️ ${reporte.barrio}<br>
                        📅 ${new Date(reporte.fecha_creacion).toLocaleDateString()}<br>
                        <span style="background: ${getMarkerColor(reporte.tipo, reporte.estado)}; color:white; padding:2px 8px; border-radius:20px; font-size:11px;">${reporte.estado}</span>
                        <br><br>
                        <a href="#" onclick="verDetalle(${reporte.id}); return false;" style="color:#ff9800; text-decoration:none;">🔍 Ver detalles completos →</a>
                    </div>
                `);
            marker.addTo(markerLayerGroup);
            markers[reporte.id] = marker;
        }
    });
    
    // Ver detalle en modal
    function verDetalle(id) {
        var reporte = reportes.find(r => r.id == id);
        if (!reporte) return;
        
        var estadoTexto = '';
        switch(reporte.estado) {
            case 'pendiente': estadoTexto = '⏳ Pendiente'; break;
            case 'en_proceso': estadoTexto = '🔄 En Proceso'; break;
            case 'resuelto': estadoTexto = '✅ Resuelto'; break;
            default: estadoTexto = reporte.estado;
        }
        
        var modalContenido = document.getElementById('modalContenido');
        modalContenido.innerHTML = `
            <h3>📋 ${reporte.tipo === 'reporte' ? 'Reporte' : 'Denuncia'} #${reporte.id}</h3>
            <hr>
            <p><strong>📌 Tipo:</strong> ${reporte.tipo_problema}</p>
            <p><strong>📍 Ubicación:</strong> ${reporte.ubicacion}</p>
            <p><strong>🏘️ Barrio:</strong> ${reporte.barrio}</p>
            <p><strong>📝 Descripción:</strong><br>${reporte.descripcion.replace(/\n/g, '<br>')}</p>
            <p><strong>👤 Reportado por:</strong> ${reporte.nombre_reportante || 'Anónimo'}</p>
            <p><strong>📅 Fecha:</strong> ${new Date(reporte.fecha_creacion).toLocaleString()}</p>
            <p><strong>⚡ Estado:</strong> <span style="background:${getMarkerColor(reporte.tipo, reporte.estado)}; color:white; padding:4px 12px; border-radius:20px; font-size:0.8rem;">${estadoTexto}</span></p>
            ${reporte.foto_path ? `<p><strong>📸 Evidencia:</strong><br><img src="${reporte.foto_path}" style="max-width:100%; border-radius:12px; margin-top:8px;"></p>` : ''}
            <hr>
            <p style="font-size:0.75rem; color:#888;">Código: ${reporte.tipo.substring(0,3).toUpperCase()}-${String(reporte.id).padStart(5,'0')}</p>
        `;
        
        document.getElementById('modal').style.display = 'flex';
        
        // Centrar mapa en el marcador
        if (reporte.latitud && reporte.longitud) {
            map.setView([parseFloat(reporte.latitud), parseFloat(reporte.longitud)], 16);
            if (markers[reporte.id]) {
                markers[reporte.id].openPopup();
            }
        }
    }
    
    function cerrarModal() {
        document.getElementById('modal').style.display = 'none';
    }
    
    window.onclick = function(event) {
        var modal = document.getElementById('modal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
    
    // Click en items de la lista
    document.querySelectorAll('.reporte-item').forEach(function(item) {
        item.addEventListener('click', function() {
            var id = this.dataset.id;
            var lat = parseFloat(this.dataset.lat);
            var lng = parseFloat(this.dataset.lng);
            if (lat && lng) {
                map.setView([lat, lng], 16);
                if (markers[id]) {
                    markers[id].openPopup();
                }
            }
            verDetalle(id);
        });
    });
    
    // Ajustar mapa después de cargar
    setTimeout(function() {
        map.invalidateSize();
        if (reportes.length > 0 && reportes[0].latitud) {
            var primerLat = parseFloat(reportes[0].latitud);
            var primerLng = parseFloat(reportes[0].longitud);
            if (!isNaN(primerLat) && !isNaN(primerLng)) {
                map.setView([primerLat, primerLng], 13);
            }
        }
    }, 300);
    
    // Reajustar al cambiar tamaño de ventana
    window.addEventListener('resize', function() {
        map.invalidateSize();
    });
</script>

</body>
</html>