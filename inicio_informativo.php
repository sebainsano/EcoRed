<?php
session_start();
require_once 'conexion.php';

$tipo_usuario = $_GET['tipo'] ?? $_SESSION['tipo_usuario'] ?? null;
if($tipo_usuario) { $_SESSION['tipo_usuario'] = $tipo_usuario; }

// Estadísticas reales desde BD
$stats = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) as total, COUNT(CASE WHEN tipo='reporte' THEN 1 END) as reportes, COUNT(CASE WHEN tipo='denuncia' THEN 1 END) as denuncias, COUNT(CASE WHEN estado='resuelto' THEN 1 END) as resueltos FROM reportes_denuncias"));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>EcoManagua - Distrito V</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Segoe UI',sans-serif; background:#f0f7f0; overflow-x:hidden; }
        .top-bar { background:#1b5e20; color:white; padding:0.8rem 2rem; display:flex; justify-content:space-between; position:fixed; top:0; width:100%; z-index:1000; }
        .hero { background:linear-gradient(135deg,rgba(27,94,32,0.92),rgba(27,94,32,0.85)), url('https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?w=1200'); background-attachment:fixed; color:white; text-align:center; padding:8rem 2rem 5rem; margin-top:60px; }
        .glitch { font-size:3rem; font-weight:bold; animation:glitch-skew 3s infinite; display:inline-block; }
        @keyframes glitch-skew { 0%,100%{transform:skew(0deg)} 20%{transform:skew(2deg)} 40%{transform:skew(-2deg)} }
        .distrito-tag { background:#ff9800; display:inline-block; padding:0.5rem 1.5rem; border-radius:50px; margin-top:1rem; animation:pulse 2s infinite; }
        @keyframes pulse { 0%,100%{transform:scale(1)} 50%{transform:scale(1.05)} }
        .main-content { max-width:1200px; margin:0 auto; padding:3rem 2rem; }
        .section { background:white; border-radius:24px; padding:2rem; margin-bottom:2rem; box-shadow:0 10px 30px rgba(0,0,0,0.08); transition:all 0.3s; }
        .section h2 { color:#1b5e20; margin-bottom:1.5rem; border-left:5px solid #ff9800; padding-left:1rem; }
        .grid-2 { display:grid; grid-template-columns:repeat(auto-fit,minmax(300px,1fr)); gap:2rem; }
        .problema-card { background:#fff5eb; border-radius:20px; padding:1.5rem; border-left:5px solid #e65100; transition:0.3s; }
        .problema-card:hover { transform:translateX(8px); }
        .solucion-card { background:#e8f5e9; border-radius:20px; padding:1.5rem; border-left:5px solid #2e7d32; transition:0.3s; }
        .stats-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:1.5rem; text-align:center; }
        .stat-card { background:linear-gradient(135deg,#1b5e20,#2e7d32); color:white; padding:2rem; border-radius:20px; transition:0.3s; }
        .stat-number { font-size:2.5rem; font-weight:bold; }
        .mapa-preview { height:350px; border-radius:20px; overflow:hidden; margin-top:1rem; }
        .acciones-container { display:flex; gap:1.5rem; justify-content:center; margin:2rem 0; flex-wrap:wrap; }
        .btn-reportar, .btn-denunciar { padding:1rem 2rem; border-radius:60px; font-weight:bold; text-decoration:none; transition:0.3s; }
        .btn-reportar { background:#ff9800; color:white; box-shadow:0 4px 15px rgba(255,152,0,0.4); }
        .btn-denunciar { background:#c62828; color:white; box-shadow:0 4px 15px rgba(198,40,40,0.4); }
        .btn-reportar:hover, .btn-denunciar:hover { transform:translateY(-3px) scale(1.02); }
        .como-ayudar-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(250px,1fr)); gap:1.5rem; }
        .ayuda-item { text-align:center; padding:1.5rem; background:#f9f9f9; border-radius:20px; transition:0.3s; }
        .ayuda-item:hover { background:#e8f5e9; transform:translateY(-5px); }
        .ayuda-icon { font-size:3rem; margin-bottom:1rem; }
        .footer { background:#1b5e20; color:white; text-align:center; padding:2rem; margin-top:2rem; }
        .fade-on-scroll { opacity:0; transform:translateY(30px); transition:opacity 0.8s, transform 0.8s; }
        .fade-on-scroll.visible { opacity:1; transform:translateY(0); }
        @media (max-width:768px){ .hero h1{font-size:1.8rem} .glitch{font-size:2rem} }
    </style>
</head>
<body>
<div class="top-bar"><div><h2>🌱 EcoManagua - Distrito V</h2></div><div><span class="user-badge"><?php echo $tipo_usuario=='comunidad'?'👥 Comunidad':'🏫 Colegio'; ?></span><a href="index.php" style="background:#ff9800;color:white;padding:0.4rem 1rem;border-radius:50px;text-decoration:none;margin-left:1rem;">🔄 Cambiar</a></div></div>
<div class="hero"><h1 class="glitch" data-text="CUIDEMOS EL DISTRITO V">🌿 CUIDEMOS EL DISTRITO V</h1><p>Santa Lucía, El Dorado, Villa Fontana, San Judas, Altamisa... juntos podemos hacer la diferencia</p><div class="distrito-tag">📍 Distrito V de Managua</div></div>
<div class="main-content">
    <div class="section fade-on-scroll"><h2>⚠️ Problemáticas Ambientales</h2><div class="grid-2"><div class="problema-card"><h3>🗑️ Acumulación de Basura</h3><p>Más de 15 puntos críticos en Santa Lucía y Villa Fontana.</p></div><div class="problema-card"><h3>🔥 Quema de Desechos</h3><p>Práctica común en Altamisa y San Judas, afecta salud respiratoria.</p></div><div class="problema-card"><h3>💧 Contaminación de Cauces</h3><p>Cauce del Reparto San Juan recibe aguas negras y basura.</p></div><div class="problema-card"><h3>🏭 Ruido y Polvo</h3><p>Afecta calidad de vida en zonas cercanas a vías principales.</p></div></div></div>
    <div class="section fade-on-scroll"><h2>💡 Soluciones Propuestas</h2><div class="grid-2"><div class="solucion-card"><h3>♻️ Programa "Barrio Limpio"</h3><p>Jornadas de limpieza comunitaria cada sábado.</p></div><div class="solucion-card"><h3>🗑️ Puntos de Reciclaje</h3><p>Contenedores diferenciados en puntos estratégicos.</p></div><div class="solucion-card"><h3>📚 Educación Ambiental</h3><p>Talleres en colegios y comunidades.</p></div><div class="solucion-card"><h3>📢 Reporta y Denuncia</h3><p>Usa nuestros canales para generar cambio.</p></div></div></div>
    <div class="section fade-on-scroll"><h2>📊 Datos del Distrito V</h2><div class="stats-grid"><div class="stat-card"><div class="stat-number"><?php echo $stats['reportes']; ?></div><div>Reportes recibidos</div></div><div class="stat-card"><div class="stat-number"><?php echo $stats['denuncias']; ?></div><div>Denuncias recibidas</div></div><div class="stat-card"><div class="stat-number"><?php echo $stats['resueltos']; ?></div><div>Caso resueltos</div></div><div class="stat-card"><div class="stat-number">8</div><div>Colegios participando</div></div></div></div>
    <div class="section fade-on-scroll"><h2>🗺️ Mapa de Puntos Críticos</h2><div id="mapaCritico" class="mapa-preview"></div><p style="text-align:center;margin-top:1rem;"><a href="mapa.php" style="color:#ff9800;">Ver mapa completo →</a></p></div>
    <div class="section fade-on-scroll"><h2>🤝 Tú puedes hacer el cambio</h2><div class="como-ayudar-grid"><div class="ayuda-item"><div class="ayuda-icon">🗑️</div><h3>Separa tu basura</h3><p>Orgánico, plástico, vidrio y papel.</p></div><div class="ayuda-item"><div class="ayuda-icon">🚶</div><h3>Participa en jornadas</h3><p>Únete a las limpiezas comunitarias.</p></div><div class="ayuda-item"><div class="ayuda-icon">📢</div><h3>Reporta problemas</h3><p>Basura acumulada, cauces sucios.</p></div><div class="ayuda-item"><div class="ayuda-icon">⚠️</div><h3>Denuncia malas prácticas</h3><p>Tirar basura prohibida, quema, tala.</p></div></div></div>
    <div class="acciones-container fade-on-scroll"><a href="crear_reporte.php?tipo=<?php echo $tipo_usuario; ?>" class="btn-reportar">📍 REPORTAR problema ambiental existente</a><a href="crear_denuncia.php?tipo=<?php echo $tipo_usuario; ?>" class="btn-denunciar">⚠️ DENUNCIAR acción de persona</a></div>
    <div class="fade-on-scroll" style="background:#e8f5e9;border-radius:20px;padding:2rem;text-align:center;"><p style="font-size:1.2rem;font-style:italic;color:#1b5e20;">"El Distrito V no es solo donde vivimos, es el hogar que compartimos. Cuidarlo está en nuestras manos."</p></div>
</div>
<div class="footer"><p>🌱 EcoManagua - Distrito V de Managua | Todos podemos ser agentes de cambio</p></div>
<script>
const observer = new IntersectionObserver((entries)=>{entries.forEach(e=>{if(e.isIntersecting)e.target.classList.add('visible');})},{threshold:0.1});
document.querySelectorAll('.fade-on-scroll').forEach(el=>observer.observe(el));
const map = L.map('mapaCritico').setView([12.1364,-86.2512],13);
L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png',{attribution:'&copy; OSM',subdomains:'abcd'}).addTo(map);
const puntos = [[12.1345,-86.2480,"Santa Lucía"],[12.1420,-86.2605,"El Dorado"],[12.1380,-86.2550,"Reparto San Juan"],[12.1295,-86.2420,"San Judas"],[12.1500,-86.2450,"Altamisa"]];
puntos.forEach(p=>{L.marker([p[0],p[1]]).bindPopup(`<strong>Punto crítico</strong><br>📍 ${p[2]}<br><a href="crear_reporte.php">Reportar</a>`).addTo(map);});
</script>
</body>
</html>