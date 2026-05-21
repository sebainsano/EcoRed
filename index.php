<?php
session_start();
if(isset($_SESSION['tipo_usuario']) && isset($_GET['tipo'])) {
    header("Location: inicio_informativo.php?tipo=" . $_GET['tipo']);
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoManagua - Distrito V</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #0a2f0d 0%, #1b5e20 50%, #0d3b10 100%);
            overflow-x: hidden;
        }
        .particles { position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 0; overflow: hidden; }
        .particle {
            position: absolute;
            background: rgba(255,152,0,0.3);
            border-radius: 50%;
            animation: floatParticle linear infinite;
        }
        @keyframes floatParticle {
            0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10% { opacity: 0.5; }
            90% { opacity: 0.5; }
            100% { transform: translateY(-100vh) rotate(360deg); opacity: 0; }
        }
        .glitch {
            font-size: 3.5rem;
            font-weight: 900;
            position: relative;
            color: white;
            letter-spacing: 5px;
            animation: glitch-skew 4s infinite;
            text-align: center;
        }
        .glitch::before, .glitch::after {
            content: attr(data-text);
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        .glitch::before { color: #ff9800; z-index: -1; animation: glitch-1 2.5s infinite; }
        .glitch::after { color: #4caf50; z-index: -2; animation: glitch-2 3.5s infinite; }
        @keyframes glitch-1 {
            0%,100% { clip-path: inset(0 0 0 0); transform: translate(0); }
            20% { clip-path: inset(20% 0 30% 0); transform: translate(-3px,2px); }
            40% { clip-path: inset(50% 0 20% 0); transform: translate(3px,-2px); }
            60% { clip-path: inset(10% 0 60% 0); transform: translate(-2px,1px); }
            80% { clip-path: inset(80% 0 5% 0); transform: translate(2px,-1px); }
        }
        @keyframes glitch-2 {
            0%,100% { clip-path: inset(0 0 0 0); transform: translate(0); }
            30% { clip-path: inset(30% 0 40% 0); transform: translate(3px,-2px); }
            50% { clip-path: inset(10% 0 70% 0); transform: translate(-3px,2px); }
            70% { clip-path: inset(60% 0 20% 0); transform: translate(2px,-1px); }
        }
        @keyframes glitch-skew { 0%,100% { transform: skew(0deg); } 20% { transform: skew(2deg); } 40% { transform: skew(-2deg); } }
        .selector-container {
            position: relative;
            z-index: 2;
            max-width: 1200px;
            margin: 0 auto;
            padding: 3rem 2rem;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .cards { display: flex; flex-wrap: wrap; gap: 2.5rem; justify-content: center; margin-top: 2rem; }
        .card {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            border-radius: 32px;
            padding: 2.5rem 2rem;
            width: 340px;
            text-decoration: none;
            color: #333;
            transition: all 0.5s cubic-bezier(0.2,0.9,0.4,1.1);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            opacity: 0;
            transform: translateY(50px);
            animation: cardSlide 0.8s ease forwards;
        }
        .card:nth-child(1) { animation-delay: 0.3s; }
        .card:nth-child(2) { animation-delay: 0.6s; }
        @keyframes cardSlide {
            from { opacity: 0; transform: translateY(50px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .card:hover { transform: translateY(-15px) scale(1.02); box-shadow: 0 30px 60px rgba(0,0,0,0.3); }
        .card-icon { font-size: 4.5rem; margin-bottom: 1.2rem; text-align: center; transition: transform 0.3s; }
        .card:hover .card-icon { transform: scale(1.1) rotate(5deg); }
        .card h2 { font-size: 1.8rem; margin-bottom: 1rem; text-align: center; }
        .card p { color: #555; line-height: 1.6; text-align: center; font-size: 0.95rem; }
        .comunidad { border-bottom: 6px solid #43a047; background: linear-gradient(135deg, #fff, #f8fff8); }
        .comunidad h2 { color: #2e7d32; }
        .colegio { border-bottom: 6px solid #1565c0; background: linear-gradient(135deg, #fff, #f0f4ff); }
        .colegio h2 { color: #1565c0; }
        .card-footer { margin-top: 1.5rem; text-align: center; font-size: 0.85rem; font-weight: bold; }
        .comunidad .card-footer { color: #43a047; }
        .colegio .card-footer { color: #1565c0; }
        .distrito-badge {
            display: inline-block;
            background: rgba(255,152,0,0.2);
            backdrop-filter: blur(10px);
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            color: #ff9800;
            font-weight: bold;
            margin-bottom: 2rem;
            border: 1px solid rgba(255,152,0,0.5);
            animation: badgePulse 2s infinite;
        }
        @keyframes badgePulse {
            0%,100% { box-shadow: 0 0 0 0 rgba(255,152,0,0.4); }
            50% { box-shadow: 0 0 0 15px rgba(255,152,0,0); }
        }
        .footer { text-align: center; margin-top: 4rem; padding: 1.5rem; color: rgba(255,255,255,0.7); font-size: 0.85rem; }
        .animated-subtitle { text-align: center; margin: 1rem 0 3rem; }
        .letter-span {
            display: inline-block;
            font-size: 1.2rem;
            font-weight: 500;
            color: rgba(255,255,255,0.9);
            opacity: 0;
            transform: translateY(20px);
            animation: letterAppear 0.4s ease forwards;
        }
        @keyframes letterAppear { to { opacity: 1; transform: translateY(0); } }
        @media (max-width: 800px) { .glitch { font-size: 2rem; } .card { width: 300px; padding: 1.5rem; } }
        @media (max-width: 700px) { .cards { flex-direction: column; align-items: center; } }
    </style>
</head>
<body>
<div class="particles" id="particles"></div>
<div class="selector-container">
    <div style="text-align:center;"><div class="distrito-badge">📍 DISTRITO V DE MANAGUA 📍</div></div>
    <div class="glitch" data-text="ECOMANAGUA">🌿 ECOMANAGUA 🌿</div>
    <div class="animated-subtitle" id="animatedSubtitle"></div>
    <div class="cards">
        <a href="inicio_informativo.php?tipo=comunidad" class="card comunidad">
            <div class="card-icon">🌳🧑‍🤝‍🧑</div>
            <h2>Soy de la Comunidad</h2>
            <p>Accede a información ambiental, puntos de reciclaje, calendario de recolección, alertas y reportes ciudadanos del Distrito V.</p>
            <div class="card-footer">Ver opciones para vecinos →</div>
        </a>
        <a href="inicio_informativo.php?tipo=colegio" class="card colegio">
            <div class="card-icon">🏫📚</div>
            <h2>Soy un Colegio</h2>
            <p>Reporta anomalías ambientales, descarga guías educativas, registra tu eco-club y gestiona denuncias desde tu centro escolar.</p>
            <div class="card-footer">Acceder herramientas educativas →</div>
        </a>
    </div>
    <div class="footer"><p>🌎 "El Distrito V no es solo donde vivimos, es el hogar que compartimos. Cuidarlo está en nuestras manos."</p></div>
</div>
<script>
    function animateSubtitle() {
        const text = "Selecciona tu perfil para acceder a herramientas personalizadas";
        const container = document.getElementById('animatedSubtitle');
        if(!container) return;
        container.innerHTML = '';
        text.split(' ').forEach((word, wi) => {
            const wordSpan = document.createElement('span');
            wordSpan.style.display = 'inline-block';
            wordSpan.style.marginRight = '10px';
            for(let i=0;i<word.length;i++){
                const letter = document.createElement('span');
                letter.className = 'letter-span';
                letter.innerText = word[i];
                letter.style.animationDelay = `${(wi*word.length+i)*0.04}s`;
                wordSpan.appendChild(letter);
            }
            container.appendChild(wordSpan);
        });
    }
    function createParticles(){
        const container = document.getElementById('particles');
        for(let i=0;i<50;i++){
            const p = document.createElement('div');
            p.className = 'particle';
            p.style.width = `${Math.random()*6+2}px`;
            p.style.height = p.style.width;
            p.style.left = `${Math.random()*100}%`;
            p.style.animationDuration = `${Math.random()*8+5}s`;
            p.style.animationDelay = `${Math.random()*10}s`;
            container.appendChild(p);
        }
    }
    animateSubtitle();
    createParticles();
</script>
</body>
</html>