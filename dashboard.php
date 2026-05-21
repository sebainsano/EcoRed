<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>EcoManagua - Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h2>🌱 EcoManagua</h2>

        <a href="dashboard.php" class="active">🏠 Inicio</a>
        <a href="mapa.php">🗺️ Mapa</a>
        <a href="mis_denuncias.php">📋 Mis Denuncias</a>
        <a href="crear_denuncia.php">➕ Nueva Denuncia</a>
        <a href="perfil.php">👤 Mi Perfil</a>
    </div>

    <!-- CONTENIDO -->
    <div class="main">

        <!-- HEADER -->
        <div class="header">
            <div>
                <h1>Hola, María 🌿</h1>
                <p>Gracias por ayudar a mantener Managua limpia</p>
            </div>

            <div class="user-icons">
                🔔
                <img src="https://i.pravatar.cc/40">
            </div>
        </div>

        <!-- CARDS -->
        <div class="cards">
            <div class="card">
                <p>Denuncias</p>
                <h2>24</h2>
                <span>Este mes</span>
            </div>

            <div class="card yellow">
                <p>En Proceso</p>
                <h2>8</h2>
                <span>Actualmente</span>
            </div>

            <div class="card green">
                <p>Resueltas</p>
                <h2>16</h2>
                <span>Este mes</span>
            </div>

            <div class="card">
                <p>Impacto</p>
                <h2>1.2k</h2>
                <span>Personas beneficiadas</span>
            </div>
        </div>

        <!-- BUSCADOR -->
        <input type="text" class="search" placeholder="🔍 Buscar lugares, denuncias...">

        <!-- CONTENIDO PRINCIPAL -->
        <div class="content">

            <!-- MAPA -->
            <div class="map">
                <img src="uploads/mapa.png" alt="Mapa">
            </div>

            <!-- LISTA -->
            <div class="list">

                <h3>Denuncias Cercanas</h3>

                <div class="item">
                    🗑️ Acumulación de basura
                    <span class="badge yellow">En proceso</span>
                </div>

                <div class="item">
                    🔥 Quema de desechos
                    <span class="badge red">Pendiente</span>
                </div>

                <div class="item">
                    💧 Contaminación de agua
                    <span class="badge green">Resuelta</span>
                </div>

                <button class="btn">Ver Mapa Completo</button>

            </div>

        </div>

    </div>

</div>

</body>
</html>