<?php
session_start();
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: inicio_informativo.php");
    exit;
}

$tipo = $_POST['tipo_accion'] ?? 'reporte';
$tipo_problema = $_POST['tipo_problema'] ?? '';
$ubicacion = $_POST['ubicacion'] ?? '';
$barrio = $_POST['barrio'] ?? '';
$latitud = !empty($_POST['latitud']) ? $_POST['latitud'] : null;
$longitud = !empty($_POST['longitud']) ? $_POST['longitud'] : null;
$descripcion = $_POST['descripcion'] ?? '';
$referencia = $_POST['referencia'] ?? '';
$nombre_reportante = !empty($_POST['nombre']) ? $_POST['nombre'] : 'Anónimo';
$telefono = $_POST['telefono'] ?? '';
$email = $_POST['email'] ?? '';
$gravedad = $_POST['gravedad'] ?? null;
$tiempo_existiendo = $_POST['tiempo_existiendo'] ?? null;
$usuario_tipo = $_POST['usuario_tipo'] ?? 'comunidad';

// Validar obligatorios
if (empty($tipo_problema) || empty($ubicacion) || empty($barrio) || empty($descripcion)) {
    die("❌ Error: Faltan campos obligatorios. <a href='javascript:history.back()'>Volver</a>");
}

// Foto
$foto_path = null;
if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $uploads = 'uploads/';
    if (!file_exists($uploads)) mkdir($uploads, 0777, true);
    $nombre = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $_FILES['foto']['name']);
    if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploads . $nombre)) {
        $foto_path = $uploads . $nombre;
    }
}

// Escapar
$tipo = mysqli_real_escape_string($conexion, $tipo);
$tipo_problema = mysqli_real_escape_string($conexion, $tipo_problema);
$ubicacion = mysqli_real_escape_string($conexion, $ubicacion);
$barrio = mysqli_real_escape_string($conexion, $barrio);
$descripcion = mysqli_real_escape_string($conexion, $descripcion);
$referencia = mysqli_real_escape_string($conexion, $referencia);
$nombre_reportante = mysqli_real_escape_string($conexion, $nombre_reportante);
$telefono = mysqli_real_escape_string($conexion, $telefono);
$email = mysqli_real_escape_string($conexion, $email);
$usuario_tipo = mysqli_real_escape_string($conexion, $usuario_tipo);

// Insertar
$sql = "INSERT INTO reportes_denuncias 
        (tipo, tipo_problema, ubicacion, barrio, latitud, longitud, descripcion, 
         referencia, nombre_reportante, telefono, email, foto_path, gravedad, 
         tiempo_existiendo, estado, usuario_tipo, fecha_creacion) 
        VALUES 
        ('$tipo', '$tipo_problema', '$ubicacion', '$barrio', " . 
        ($latitud ? "'$latitud'" : "NULL") . ", " . 
        ($longitud ? "'$longitud'" : "NULL") . ", 
        '$descripcion', '$referencia', '$nombre_reportante', '$telefono', '$email', " . 
        ($foto_path ? "'$foto_path'" : "NULL") . ", " . 
        ($gravedad ? "'$gravedad'" : "NULL") . ", " . 
        ($tiempo_existiendo ? "'$tiempo_existiendo'" : "NULL") . ", 
        'pendiente', '$usuario_tipo', NOW())";

if (mysqli_query($conexion, $sql)) {
    $id = mysqli_insert_id($conexion);
    $codigo = strtoupper(substr($tipo, 0, 3)) . '-' . str_pad($id, 5, '0', STR_PAD_LEFT);
    header("Location: exito.php?tipo=$tipo&codigo=$codigo");
    exit;
} else {
    echo "<h2>❌ Error al guardar</h2>";
    echo "<p>" . mysqli_error($conexion) . "</p>";
    echo "<a href='javascript:history.back()'>Volver</a>";
}
?>