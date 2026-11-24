<?php
// auth.php: se incluye al inicio de cada página protegida
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}