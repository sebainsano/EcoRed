<?php
require_once 'conexion.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $estado = mysqli_real_escape_string($conexion, $_POST['estado']);
    mysqli_query($conexion, "UPDATE reportes_denuncias SET estado='$estado', fecha_actualizacion=NOW() WHERE id=$id");
}
header("Location: admin_dashboard.php");
?>