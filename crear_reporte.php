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
    <title>EcoManagua - Nuevo Reporte | Distrito V</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        .form-reporte { max-width: 100%; margin: 0 auto; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px; }
        .form-section { background: white; border-radius: 20px; padding: 24px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 24px; }
        .form-section h3 { margin-bottom: 20px; padding-bottom: 12px; border-bottom: 2px solid #ecf0f1; display: flex; align-items: center; gap: 10px; color: #2c3e50; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50; font-size: 0.9rem; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 12px 15px; border: 2px solid #e0e4e8; border-radius: 12px; font-size: 0.95rem; transition: all 0.3s; font-family: inherit; }
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus { outline: none; border-color: #ff9800; box-shadow: 0 0 0 3px rgba(255,152,0,0.1); }
        .map-container { height: 280px; border-radius: 12px; overflow: hidden; margin-bottom: 12px; border: 2px solid #e0e4e8; }
        .btn-ubicacion { background: #ecf0f1; border: none; padding: 10px 16px; border-radius: 10px; cursor: pointer; font-weight: 500; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s; }
        .btn-ubicacion:hover { background: #ff9800; color: white; }
        .tipos-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 12px; }
        .tipo-option { display: flex; align-items: center; gap: 10px; padding: 12px; border: 2px solid #e0e4e8; border-radius: 12px; cursor: pointer; transition: all 0.3s; }
        .tipo-option:hover { border-color: #ff9800; background: #fff8e1; }
        .tipo-option input { width: 18px; height: 18px; cursor: pointer; }
        .tipo-option label { cursor: pointer; margin: 0; font-weight: normal; }
        .tipo-option.selected { border-color: #ff9800; background: #fff8e1; }
        .gravedad-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-top: 10px; }
        .gravedad-option { text-align: center; padding: 12px; border: 2px solid #e0e4e8; border-radius: 12px; cursor: pointer; transition: all 0.3s; }
        .gravedad-option:hover { border-color: #ff9800; background: #fff8e1; }
        .gravedad-option.selected { border-color: #ff9800; background: #fff8e1; }
        .gravedad-option input { display: none; }
        .foto-area { border: 2px dashed #e0e4e8; border-radius: 12px; padding: 30px; text-align: center; cursor: pointer; transition: all 0.3s; }
        .foto-area:hover { border-color: #ff9800; background: #fff8e1; }
        .foto-area input { display: none; }
        .foto-preview { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 15px; }
        .foto-preview img { width: 80px; height: 80px; object-fit: cover; border-radius: 8px; border: 2px solid #e0e4e8; }
        .checkbox-group { display: flex; align-items: center; gap: 10px; margin-top: 10px; }
        .checkbox-group input { width: 20px; height: 20px; }
        .btn-enviar { width: 100%; padding: 16px; background: linear-gradient(135deg, #ff9800, #e65100); color: white; border: none; border-radius: 50px; font-size: 1.1rem; font-weight: 600; cursor: pointer; transition: all 0.3s; margin-top: 10px; }
        .btn-enviar:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(255,152,0,0.4); }
        .full-width { grid-column: span 2; }
        @media (max-width: 900px) { .form-grid { grid-template-columns: 1fr; } .full-width { grid-column: span 1; } }
        .badge-reporte { background: #ff9800; color: white; padding: 4px 12px; border-radius: 50px; font-size: 0.8rem; display: inline-block; margin-bottom: 15px; }
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
<!-- SIDEBAR PARA COMUNIDAD (PARTICIPACIÓN CIUDADANA) -->
<div class="sidebar">
    <h2>🌱 EcoManagua</h2>
    <h2 style="font-size: 0.9rem; margin-top: -10px; margin-bottom: 20px; color: #4caf50;">👥 Modo Comunidad</h2>
    
    <!-- Menú principal -->
    <a href="inicio_informativo.php?tipo=comunidad">
        <span>🏠</span> Inicio
    </a>
    <a href="mapa.php">
        <span>🗺️</span> Mapa Ambiental
    </a>
    <a href="mis_reportes.php">
        <span>📋</span> Mis Reportes
    </a>
    <a href="crear_reporte.php?tipo=comunidad" class="active">
        <span>📍</span> Nuevo Reporte
    </a>
    <a href="crear_denuncia.php?tipo=comunidad">
        <span>⚠️</span> Nueva Denuncia
    </a>
    
    <div style="height: 1px; background: #2e7d32; margin: 15px 0;"></div>
    
    <!-- ========== SECCIÓN PARTICIPACIÓN CIUDADANA ========== -->
    <div style="margin-bottom: 15px;">
        <div style="background: #ff9800; padding: 8px 12px; border-radius: 12px; margin-bottom: 10px;">
            <span>🤝 PARTICIPACIÓN CIUDADANA</span>
        </div>
        <a href="jornadas_limpieza.php">
            <span>🧹</span> Jornadas de Limpieza
        </a>
        <a href="voluntariado.php">
            <span>🙋</span> Voluntariado Ambiental
        </a>
        <a href="comites_vecinales.php">
            <span>🏘️</span> Comités Vecinales
        </a>
        <a href="cabildos_abiertos.php">
            <span>🗣️</span> Cabildos Abiertos
        </a>
    </div>
    
    <!-- Servicios Ambientales -->
    <div style="margin-bottom: 15px;">
        <div style="background: #4caf50; padding: 8px 12px; border-radius: 12px; margin-bottom: 10px;">
            <span>♻️ SERVICIOS AMBIENTALES</span>
        </div>
        <a href="puntos_reciclaje.php">
            <span>🗑️</span> Puntos de Reciclaje
        </a>
        <a href="calendario_recoleccion.php">
            <span>📅</span> Calendario de Recolección
        </a>
        <a href="ecoparques.php">
            <span>🌳</span> Ecoparques del Distrito V
        </a>
        <a href="centros_acopio.php">
            <span>🏪</span> Centros de Acopio
        </a>
    </div>
    
    <!-- Alertas y Noticias -->
    <div style="margin-bottom: 15px;">
        <div style="background: #2196f3; padding: 8px 12px; border-radius: 12px; margin-bottom: 10px;">
            <span>⚠️ ALERTAS Y NOTICIAS</span>
        </div>
        <a href="alertas_ambientales.php">
            <span>🔔</span> Alertas Ambientales
        </a>
        <a href="noticias.php">
            <span>📰</span> Noticias del Distrito V
        </a>
        <a href="comunicados.php">
            <span>📢</span> Comunicados Oficiales
        </a>
        <a href="estado_denuncias.php">
            <span>📊</span> Estado de Denuncias
        </a>
    </div>
    
    <!-- Educación Ambiental para Comunidad -->
    <div style="margin-bottom: 15px;">
        <div style="background: #9c27b0; padding: 8px 12px; border-radius: 12px; margin-bottom: 10px;">
            <span>🌿 EDUCACIÓN AMBIENTAL</span>
        </div>
        <a href="consejos_ecologicos.php">
            <span>💚</span> Consejos Ecológicos
        </a>
        <a href="manual_buenas_practicas.php">
            <span>📖</span> Manual de Buenas Prácticas
        </a>
        <a href="videos_comunitarios.php">
            <span>🎬</span> Videos Educativos
        </a>
        <a href="infografias.php">
            <span>📊</span> Infografías
        </a>
    </div>
    
    <!-- Contacto y Denuncias -->
    <div style="margin-bottom: 15px;">
        <div style="background: #e91e63; padding: 8px 12px; border-radius: 12px; margin-bottom: 10px;">
            <span>📞 CONTACTO Y ASISTENCIA</span>
        </div>
        <a href="contacto_emergencia.php">
            <span>🚨</span> Emergencias Ambientales
        </a>
        <a href="lineas_ayuda.php">
            <span>📱</span> Líneas de Ayuda
        </a>
        <a href="whatsapp_comunitario.php">
            <span>💬</span> WhatsApp Comunitario
        </a>
        <a href="oficinas_municipales.php">
            <span>🏛️</span> Oficinas Municipales
        </a>
    </div>
    
    <!-- Beneficios y Reconocimientos -->
    <div style="margin-bottom: 15px;">
        <div style="background: #795548; padding: 8px 12px; border-radius: 12px; margin-bottom: 10px;">
            <span>🏆 BENEFICIOS Y RECONOCIMIENTOS</span>
        </div>
        <a href="vecino_destacado.php">
            <span>⭐</span> Vecino Ambiental Destacado
        </a>
        <a href="incentivos_verdes.php">
            <span>🎁</span> Incentivos Verdes
        </a>
        <a href="certificaciones.php">
            <span>📜</span> Certificaciones Comunitarias
        </a>
    </div>
    
    <!-- Recursos Rápidos (Tips) -->
    <div style="margin-top: 20px; background: #1b5e20; border-radius: 16px; padding: 12px;">
        <div style="text-align: center; margin-bottom: 10px;">💡 TIP DEL DÍA</div>
        <div style="font-size: 0.8rem; line-height: 1.4; text-align: center;">
            "Separa tus residuos: Orgánico, Plástico, Vidrio y Papel. ¡Pequeñas acciones generan grandes cambios!"
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
            <h1>📍 Nuevo Reporte Ambiental</h1>
            <div class="user-icons">
                <span>👤 <?php echo $tipo_usuario == 'comunidad' ? 'Comunidad' : 'Colegio'; ?></span>
                <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Avatar">
                <span>🔔</span>
            </div>
        </div>

        <div class="badge-reporte">📍 REPORTE - Problemas ambientales existentes (basura acumulada, cauces contaminados, etc.)</div>
        <p style="color: #7f8c8d; margin-bottom: 25px; font-size: 0.95rem;">
            Reporta basura acumulada, cauces contaminados, botes desbordados u otras situaciones ambientales que necesitan atención.
        </p>

        <div class="form-reporte">
            <form action="guardar_reporte.php" method="POST" enctype="multipart/form-data" id="formReporte">
                <input type="hidden" name="tipo_accion" value="reporte">
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
                        <!-- INFORMACIÓN DEL REPORTANTE -->
                        <div class="form-section">
                            <h3>👤 Información del Reportante</h3>
                            <div class="form-group">
                                <label>Nombre completo</label>
                                <input type="text" id="nombre" name="nombre" placeholder="Ej: María González Pérez (opcional)">
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

                        <!-- TIPO DE PROBLEMA -->
                        <div class="form-section">
                            <h3>🗑️ Tipo de problema</h3>
                            <div class="tipos-grid" id="tiposContainer">
                                <div class="tipo-option" data-tipo="Basura acumulada">
                                    <input type="radio" name="tipo_problema" value="Basura acumulada" id="tipoBasura">
                                    <label for="tipoBasura">🗑️ Basura acumulada</label>
                                </div>
                                <div class="tipo-option" data-tipo="Cauce contaminado">
                                    <input type="radio" name="tipo_problema" value="Cauce contaminado" id="tipoCauce">
                                    <label for="tipoCauce">💧 Cauce contaminado</label>
                                </div>
                                <div class="tipo-option" data-tipo="Bote desbordado">
                                    <input type="radio" name="tipo_problema" value="Bote desbordado" id="tipoBote">
                                    <label for="tipoBote">🗑️ Bote desbordado</label>
                                </div>
                                <div class="tipo-option" data-tipo="Mal olor">
                                    <input type="radio" name="tipo_problema" value="Mal olor" id="tipoOlor">
                                    <label for="tipoOlor">👃 Mal olor persistente</label>
                                </div>
                                <div class="tipo-option" data-tipo="Punto crítico">
                                    <input type="radio" name="tipo_problema" value="Punto crítico" id="tipoCritico">
                                    <label for="tipoCritico">⚠️ Punto crítico</label>
                                </div>
                                <div class="tipo-option" data-tipo="Vectores">
                                    <input type="radio" name="tipo_problema" value="Vectores" id="tipoVectores">
                                    <label for="tipoVectores">🐀 Presencia de vectores</label>
                                </div>
                                <div class="tipo-option" data-tipo="Otro">
                                    <input type="radio" name="tipo_problema" value="Otro" id="tipoOtro">
                                    <label for="tipoOtro">📌 Otro</label>
                                </div>
                            </div>
                        </div>

                        <!-- GRAVEDAD -->
                        <div class="form-section">
                            <h3>⚠️ Nivel de gravedad</h3>
                            <div class="gravedad-grid" id="gravedadContainer">
                                <div class="gravedad-option" data-gravedad="Leve">
                                    <input type="radio" name="gravedad" value="Leve" id="graveLeve">
                                    <label for="graveLeve">🟢 Leve</label>
                                </div>
                                <div class="gravedad-option" data-gravedad="Moderado">
                                    <input type="radio" name="gravedad" value="Moderado" id="graveModerado">
                                    <label for="graveModerado">🟡 Moderado</label>
                                </div>
                                <div class="gravedad-option" data-gravedad="Grave">
                                    <input type="radio" name="gravedad" value="Grave" id="graveGrave">
                                    <label for="graveGrave">🔴 Grave</label>
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
                                <label>Describe el problema que estás reportando *</label>
                                <textarea id="descripcion" name="descripcion" rows="4" required placeholder="Ej: Hay acumulación de basura en la esquina desde hace 3 días, genera mal olor y atrae moscas..."></textarea>
                            </div>
                            <div class="form-group">
                                <label>¿Desde cuándo existe este problema?</label>
                                <select id="tiempo" name="tiempo_existiendo">
                                    <option value="">Selecciona...</option>
                                    <option>Menos de 1 semana</option>
                                    <option>1-2 semanas</option>
                                    <option>2 semanas - 1 mes</option>
                                    <option>Más de 1 mes</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Referencia del lugar</label>
                                <input type="text" id="referencia" name="referencia" placeholder="Ej: Frente al mercado, cerca del semáforo">
                            </div>
                        </div>

                        <!-- FOTOS / EVIDENCIA -->
                        <div class="form-section">
                            <h3>📸 Fotos del problema</h3>
                            <div class="foto-area" onclick="document.getElementById('fotos').click()">
                                📷 Haz clic para subir fotos del lugar
                                <input type="file" id="fotos" name="foto" accept="image/*" onchange="previewFotos(this)">
                            </div>
                            <div id="fotoPreview" class="foto-preview"></div>
                        </div>

                        <!-- INFORMACIÓN ADICIONAL -->
                        <div class="form-section">
                            <h3>➕ Información adicional</h3>
                            <div class="checkbox-group">
                                <input type="checkbox" id="afectaSalud" name="afecta_salud" value="1">
                                <label for="afectaSalud">⚠️ Afecta la salud pública</label>
                            </div>
                            <div class="checkbox-group">
                                <input type="checkbox" id="cercaEscuela" name="cerca_escuela" value="1">
                                <label for="cercaEscuela">🏫 Está cerca de una escuela o colegio</label>
                            </div>
                            <div class="checkbox-group">
                                <input type="checkbox" id="cercaCauce" name="cerca_cauce" value="1">
                                <label for="cercaCauce">💧 Está cerca de un cauce o quebrada</label>
                            </div>
                            <div class="checkbox-group">
                                <input type="checkbox" id="yaReportado" name="ya_reportado" value="1">
                                <label for="yaReportado">📢 Ya ha sido reportado antes sin respuesta</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BOTÓN ENVIAR -->
                <button type="submit" class="btn-enviar">
                    📍 Enviar Reporte
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
    
    // Selección de tipo de problema
    document.querySelectorAll('.tipo-option').forEach(option => {
        option.addEventListener('click', function() {
            const radio = this.querySelector('input');
            radio.checked = true;
            document.querySelectorAll('.tipo-option').forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');
        });
    });
    
    // Selección de gravedad
    document.querySelectorAll('.gravedad-option').forEach(option => {
        option.addEventListener('click', function() {
            const radio = this.querySelector('input');
            radio.checked = true;
            document.querySelectorAll('.gravedad-option').forEach(opt => opt.classList.remove('selected'));
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