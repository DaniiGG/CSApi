<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/styles.css">
</head>
<body>
    <header>
<h1><a href="<?=BASE_URL?>">CSAPI</a></h1>

<ul>
<?php if (!isset($_SESSION['identity'])): ?>
    <li><a href="<?=BASE_URL?>">Inicio</a></li>
    <li><a href="<?=BASE_URL?>usuario/login">Log In</a></li>
    <li><a href="<?=BASE_URL?>usuario/registro">Registrarse</a></li>
    <?php elseif ($_SESSION['identity']): ?>
            <h3> Bienvenido <?=$_SESSION['identity']->nombre?> 
            <?=$_SESSION['identity']->apellidos?></h3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <li><a href="<?=BASE_URL?>usuario/logout">Cerrar sesión</a></li>
    <?php endif; ?>
</ul>
    </header>

</body>
</html>