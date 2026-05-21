<?php
session_start();
$tipo_usuario = $_GET['tipo'] ?? $_SESSION['tipo_usuario'] ?? 'comunidad';
$_SESSION['tipo_usuario'] = $tipo_usuario;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoManagua - Nueva Denuncia | Distrito V</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        .form-denuncia { max-width: 100%; margin: 0 auto; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px; }
        .form-section { background: white; border-radius: 20px; padding: 24px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 24px; }
        .form-section h3 { margin-bottom: 20px; padding-bottom: 12px; border-bottom: 2px solid #ecf0f1; display: flex; align-items: center; gap: 10px; color: #2c3e50; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50; font-size: 0.9rem; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 12px 15px; border: 2px solid #e0e4e8; border-radius: 12px; font-size: 0.95rem; transition: all 0.3s; font-family: inherit; }
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus { outline: none; border-color: #c62828; box-shadow: 0 0 0 3px rgba(198,40,40,0.1); }
        .map-container { height: 280px; border-radius: 12px; overflow: hidden; margin-bottom: 12px; border: 2px solid #e0e4e8; }
        .btn-ubicacion { background: #ecf0f1; border: none; padding: 10px 16px; border-radius: 10px; cursor: pointer; font-weight: 500; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s; }
        .btn-ubicacion:hover { background: #c62828; color: white; }
        .tipos-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 12px; }
        .tipo-option { display: flex; align-items: center; gap: 10px; padding: 12px; border: 2px solid #e0e4e8; border-radius: 12px; cursor: pointer; transition: all 0.3s; }
        .tipo-option:hover { border-color: #c62828; background: #ffebee; }
        .tipo-option input { width: 18px; height: 18px; cursor: pointer; }
        .tipo-option label { cursor: pointer; margin: 0; font-weight: normal; }
        .tipo-option.selected { border-color: #c62828; background: #ffebee; }
        .foto-area { border: 2px dashed #e0e4e8; border-radius: 12px; padding: 30px; text-align: center; cursor: pointer; transition: all 0.3s; }
        .foto-area:hover { border-color: #c62828; background: #ffebee; }
        .foto-area input { display: none; }
        .foto-preview { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 15px; }
        .foto-preview img { width: 80px; height: 80px; object-fit: cover; border-radius: 8px; border: 2px solid #e0e4e8; }
        .checkbox-group { display: flex; align-items: center; gap: 10px; margin-top: 10px; }
        .checkbox-group input { width: 20px; height: 20px; }
        .btn-enviar { width: 100%; padding: 16px; background: linear-gradient(135deg, #c62828, #8e0000); color: white; border: none; border-radius: 50px; font-size: 1.1rem; font-weight: 600; cursor: pointer; transition: all 0.3s; margin-top: 10px; }
        .btn-enviar:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(198,40,40,0.4); }
        .full-width { grid-column: span 2; }
        @media (max-width: 900px) { .form-grid { grid-template-columns: 1fr; } .full-width { grid-column: span 1; } }
        .badge-denuncia { background: #c62828; color: white; padding: 4px 12px; border-radius: 50px; font-size: 0.8rem; display: inline-block; margin-bottom: 15px; }
    </style>
</head>
<body>

<div class="container">

    <!-- SIDEBAR -->
    <!-- SIDEBAR PARA COLEGIOS (EDUCATIVO) -->
<div class="sidebar">
    <h2>🌱 EcoManagua</h2>
    <h2 style="font-size: 0.9rem; margin-top: -10px; margin-bottom: 20px; color: #ff9800;">🏫 Modo Colegio</h2>
    
    <!-- Menú principal -->
    <a href="inicio_informativo.php?tipo=colegio">
        <span>🏠</span> Inicio
    </a>
    <a href="mapa.php">
        <span>🗺️</span> Mapa Ambiental
    </a>
    <a href="mis_reportes.php">
        <span>📋</span> Mis Reportes
    </a>
    <a href="crear_reporte.php?tipo=colegio" class="active">
        <span>📍</span> Nuevo Reporte
    </a>
    <a href="crear_denuncia.php?tipo=colegio">
        <span>⚠️</span> Nueva Denuncia
    </a>
    
    <div style="height: 1px; background: #2e7d32; margin: 15px 0;"></div>
    
    <!-- ========== SECCIÓN EDUCATIVA ========== -->
    <div style="margin-bottom: 15px;">
        <div style="background: #ff9800; padding: 8px 12px; border-radius: 12px; margin-bottom: 10px;">
            <span>📚 CENTRO EDUCATIVO</span>
        </div>
        
        <a href="educacion_colegios.php">
            <span>📖</span> Biblioteca Ambiental
        </a>
        <a href="guias_descargables.php">
            <span>📥</span> Guías Descargables
        </a>
        <a href="videos_educativos.php">
            <span>🎥</span> Videos Educativos
        </a>
    </div>
    
    <!-- Eco-Clubes -->
    <div style="margin-bottom: 15px;">
        <div style="background: #4caf50; padding: 8px 12px; border-radius: 12px; margin-bottom: 10px;">
            <span>🏅 ECO-CLUBES ESCOLARES</span>
        </div>
        <a href="eco_clubes.php">
            <span>🌟</span> Registrar Eco-Club
        </a>
        <a href="mis_eco_clubes.php">
            <span>📊</span> Mi Eco-Club
        </a>
        <a href="ranking_ecologico.php">
            <span>🏆</span> Ranking Ecológico
        </a>
    </div>
    
    <!-- Material Didáctico -->
    <div style="margin-bottom: 15px;">
        <div style="background: #2196f3; padding: 8px 12px; border-radius: 12px; margin-bottom: 10px;">
            <span>🎓 MATERIAL DIDÁCTICO</span>
        </div>
        <a href="planificaciones.php">
            <span>📅</span> Planificaciones
        </a>
        <a href="actividades_aula.php">
            <span>✏️</span> Actividades de Aula
        </a>
        <a href="proyectos_ambientales.php">
            <span>🌎</span> Proyectos Ambientales
        </a>
        <a href="evaluaciones.php">
            <span>📝</span> Evaluaciones
        </a>
    </div>
    
    <!-- Capacitaciones -->
    <div style="margin-bottom: 15px;">
        <div style="background: #9c27b0; padding: 8px 12px; border-radius: 12px; margin-bottom: 10px;">
            <span>🎓 CAPACITACIONES</span>
        </div>
        <a href="solicitar_capacitacion.php">
            <span>📢</span> Solicitar Charla
        </a>
        <a href="cursos_docentes.php">
            <span>👨‍🏫</span> Cursos para Docentes
        </a>
        <a href="webinars.php">
            <span>💻</span> Webinars Ambientales
        </a>
    </div>
    
    <!-- Concursos y Eventos -->
    <div style="margin-bottom: 15px;">
        <div style="background: #e91e63; padding: 8px 12px; border-radius: 12px; margin-bottom: 10px;">
            <span>🏆 CONCURSOS Y EVENTOS</span>
        </div>
        <a href="concursos.php">
            <span>🥇</span> Concursos Escolares
        </a>
        <a href="ferias_ambientales.php">
            <span>🎪</span> Ferias Ambientales
        </a>
        <a href="olimpiadas_ecologicas.php">
            <span>🌿</span> Olimpiadas Ecológicas
        </a>
    </div>
    
    <!-- Recursos Rápidos (Tips) -->
    <div style="margin-top: 20px; background: #1b5e20; border-radius: 16px; padding: 12px;">
        <div style="text-align: center; margin-bottom: 10px;">💡 TIP DEL DÍA</div>
        <div style="font-size: 0.8rem; line-height: 1.4; text-align: center;">
            "Un colegio que recicla puede reducir hasta 2 toneladas de CO2 al año. ¡Empieza hoy!"
        </div>
    </div>
    
    <div style="margin-top: 15px;">
        <a href="index.php" style="background: #ff9800; text-align: center; border-radius: 50px;">
            <span>🔄</span> Cambiar perfil
        </a>
    </div>
</div>
    <!-- CONTENIDO PRINCIPAL -->
    <div class="main">

        <div class="header">
            <h1>⚠️ Nueva Denuncia</h1>
            <div class="user-icons">
                <span>👤 <?php echo $tipo_usuario == 'comunidad' ? 'Comunidad' : 'Colegio'; ?></span>
                <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Avatar">
                <span>🔔</span>
            </div>
        </div>

        <div class="badge-denuncia">⚠️ DENUNCIA - Acciones de personas que dañan el ambiente</div>
        <p style="color: #7f8c8d; margin-bottom: 25px; font-size: 0.95rem;">
            Denuncia acciones como: tirar basura en lugares prohibidos, quema de desechos, tala ilegal, vertido de aguas negras.
        </p>

        <div class="form-denuncia">
            <form action="guardar_reporte.php" method="POST" enctype="multipart/form-data" id="formDenuncia">
                <input type="hidden" name="tipo_accion" value="denuncia">
                <input type="hidden" name="usuario_tipo" value="<?php echo $tipo_usuario; ?>">
                
                <!-- UBICACIÓN - Mapa -->
                <div class="form-section">
                    <h3>📍 Ubicación</h3>
                    <div id="map" class="map-container"></div>
                    <button type="button" class="btn-ubicacion" onclick="getUserLocation()">
                        📍 Obtener mi ubicación actual
                    </button>
                    <div class="form-group" style="margin-top: 15px;">
                        <label>Dirección / Referencia *</label>
                        <input type="text" id="direccion" name="ubicacion" required placeholder="Ej: De la farmacia 2 cuadras al sur">
                    </div>
                    <div class="form-group">
                        <label>Barrio *</label>
                        <select id="barrio" name="barrio" required>
                            <option value="">Seleccione un barrio del Distrito V</option>
                            <option>Santa Lucía</option>
                            <option>El Dorado</option>
                            <option>Villa Fontana</option>
                            <option>San Judas</option>
                            <option>Altamisa</option>
                            <option>Reparto San Juan</option>
                            <option>Las Colinas</option>
                            <option>Otro</option>
                        </select>
                    </div>
                    <input type="hidden" id="latitud" name="latitud" value="">
                    <input type="hidden" id="longitud" name="longitud" value="">
                </div>

                <div class="form-grid">
                    <!-- COLUMNA IZQUIERDA -->
                    <div>
                        <!-- INFORMACIÓN DEL DENUNCIANTE -->
                        <div class="form-section">
                            <h3>👤 Información del Denunciante</h3>
                            <div class="form-group">
                                <label>Nombre completo</label>
                                <input type="text" id="nombre" name="nombre" placeholder="Ej: María González Pérez (opcional)">
                            </div>
                            <div class="form-group">
                                <label>Cédula de Identidad</label>
                                <input type="text" id="cedula" placeholder="Ej: 001-123456-7890A (opcional)">
                            </div>
                            <div class="form-group">
                                <label>Teléfono / Celular</label>
                                <input type="tel" id="telefono" name="telefono" placeholder="Ej: 8888-1234">
                            </div>
                            <div class="form-group">
                                <label>Correo electrónico</label>
                                <input type="email" id="email" name="email" placeholder="ejemplo@correo.com">
                            </div>
                        </div>

                        <!-- TIPO DE DENUNCIA -->
                        <div class="form-section">
                            <h3>🏷️ Tipo de Denuncia</h3>
                            <div class="tipos-grid" id="tiposContainer">
                                <div class="tipo-option" data-tipo="Tirar basura prohibida">
                                    <input type="radio" name="tipo_problema" value="Tirar basura prohibida" id="tipoBasura">
                                    <label for="tipoBasura">🗑️ Tirar basura prohibida</label>
                                </div>
                                <div class="tipo-option" data-tipo="Quema de desechos">
                                    <input type="radio" name="tipo_problema" value="Quema de desechos" id="tipoQuema">
                                    <label for="tipoQuema">🔥 Quema de desechos</label>
                                </div>
                                <div class="tipo-option" data-tipo="Tala ilegal">
                                    <input type="radio" name="tipo_problema" value="Tala ilegal" id="tipoTala">
                                    <label for="tipoTala">🌳 Tala ilegal</label>
                                </div>
                                <div class="tipo-option" data-tipo="Vertido de aguas negras">
                                    <input type="radio" name="tipo_problema" value="Vertido de aguas negras" id="tipoAgua">
                                    <label for="tipoAgua">💧 Vertido de aguas negras</label>
                                </div>
                                <div class="tipo-option" data-tipo="Ruido excesivo">
                                    <input type="radio" name="tipo_problema" value="Ruido excesivo" id="tipoRuido">
                                    <label for="tipoRuido">🔊 Ruido excesivo</label>
                                </div>
                                <div class="tipo-option" data-tipo="Otro">
                                    <input type="radio" name="tipo_problema" value="Otro" id="tipoOtro">
                                    <label for="tipoOtro">📌 Otro</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- COLUMNA DERECHA -->
                    <div>
                        <!-- DESCRIPCIÓN -->
                        <div class="form-section">
                            <h3>📝 Descripción</h3>
                            <div class="form-group">
                                <label>Describe la acción que viste *</label>
                                <textarea id="descripcion" name="descripcion" rows="5" required placeholder="Ej: Vi a una persona tirando basura en la esquina, era un hombre con camisa roja, fue ayer a las 3pm..."></textarea>
                            </div>
                            <div class="form-group">
                                <label>¿Lograste identificar a la persona?</label>
                                <input type="text" id="referencia" name="referencia" placeholder="Descripción física, vehículo, etc.">
                            </div>
                        </div>

                        <!-- FOTOS / EVIDENCIA -->
                        <div class="form-section">
                            <h3>📸 Fotos / Evidencia</h3>
                            <div class="foto-area" onclick="document.getElementById('fotos').click()">
                                📷 Haz clic o arrastra tus fotos aquí
                                <input type="file" id="fotos" name="foto" accept="image/*" onchange="previewFotos(this)">
                            </div>
                            <div id="fotoPreview" class="foto-preview"></div>
                        </div>

                        <!-- INFORMACIÓN ADICIONAL -->
                        <div class="form-section">
                            <h3>➕ Información adicional</h3>
                            <div class="checkbox-group">
                                <input type="checkbox" id="frenteMercado" name="frente_mercado" value="1">
                                <label for="frenteMercado">📍 Frente al Mercado Mayoreo</label>
                            </div>
                            <div class="checkbox-group">
                                <input type="checkbox" id="cercaEscuela" name="cerca_escuela" value="1">
                                <label for="cercaEscuela">🏫 Cerca de un colegio</label>
                            </div>
                            <div class="checkbox-group">
                                <input type="checkbox" id="zonaResidencial" name="zona_residencial" value="1">
                                <label for="zonaResidencial">🏘️ Zona residencial</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BOTÓN ENVIAR -->
                <button type="submit" class="btn-enviar">
                    ⚠️ Enviar Denuncia
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // Inicializar mapa
    var map = L.map('map').setView([12.1364, -86.2512], 14);
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a>',
        subdomains: 'abcd'
    }).addTo(map);
    
    var marker;
    
    map.on('click', function(e) {
        if (marker) map.removeLayer(marker);
        marker = L.marker(e.latlng).addTo(map);
        document.getElementById('latitud').value = e.latlng.lat;
        document.getElementById('longitud').value = e.latlng.lng;
        
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${e.latlng.lat}&lon=${e.latlng.lng}&zoom=18&addressdetails=1`)
            .then(response => response.json())
            .then(data => {
                if (data.display_name) {
                    document.getElementById('direccion').value = data.display_name.substring(0, 100);
                }
            });
    });
    
    function getUserLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;
                map.setView([lat, lng], 16);
                if (marker) map.removeLayer(marker);
                marker = L.marker([lat, lng]).addTo(map);
                document.getElementById('latitud').value = lat;
                document.getElementById('longitud').value = lng;
                
                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.display_name) {
                            document.getElementById('direccion').value = data.display_name.substring(0, 100);
                        }
                    });
            }, function(error) {
                alert("No se pudo obtener tu ubicación. Verifica los permisos.");
            });
        } else {
            alert("Tu navegador no soporta geolocalización.");
        }
    }
    
    // Selección de tipo de denuncia
    document.querySelectorAll('.tipo-option').forEach(option => {
        option.addEventListener('click', function() {
            const radio = this.querySelector('input');
            radio.checked = true;
            document.querySelectorAll('.tipo-option').forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');
        });
    });
    
    // Previsualización de fotos
    function previewFotos(input) {
        const preview = document.getElementById('fotoPreview');
        preview.innerHTML = '';
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                preview.appendChild(img);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

</body>
</html>