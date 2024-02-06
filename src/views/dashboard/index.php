<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/dashboard.css">
    <title>CS:GO API Landing Page</title>
</head>
<body>


    <header>
        <p>¡Obtén datos en tiempo real sobre CS:GO!</p>
    </header>
    <?php use Utils\Utils; ?>
 
    <?php if(isset($_SESSION['register']) && $_SESSION['register'] == 'logueado'): ?> 
        <h6><strong class="alert_green">Se ha logueado correctamente</strong></h6> 
    <?php endif; ?>
     
    <?php Utils::deleteSession('register'); ?>
    <section>
        <h2>Características de la API</h2>
        <p>Con nuestra API de CS:GO, puedes acceder a información valiosa sobre todas las skins del juego, incluyendo precio de estas, detalles de desgaste, imágenes de las mismas y más.</p>
        <p>Integra fácilmente nuestros servicios en tu aplicación o sitio web y mantén a tus usuarios actualizados con los últimos datos de CS:GO.</p>
        <p><b>Importante:</b> generar un token con una hora de tiempo para hacer consultas</p>
        <?php if (!isset($_SESSION['identity'])): ?>
        <a href="<?=BASE_URL?>usuario/login" class="api-link">Explora la documentación</a>
        <?php $_SESSION['register'] = 'login_required'?>
        <?php elseif (isset($_SESSION['identity'])): ?>
            <a href="<?=BASE_URL?>api/api" class="api-link">Explora la documentación</a>
        <?php endif ?>
    </section>

   

</body>
</html>