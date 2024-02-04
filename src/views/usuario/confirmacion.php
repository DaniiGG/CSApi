<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Registro</title>
</head>
<body>
    <h1>Confirmación de Registro</h1>

    <?php

    if (isset($_SESSION['register'])) {
        $mensaje = '';
        switch ($_SESSION['register']) {
            case 'failed':
                $mensaje = 'Tiempo para confirmación expirado. Se ha enviado un email de confirmación.';
                break;
            case 'token_invalid':
                $mensaje = 'El token de confirmación es inválido.';
                break;
            default:
                $mensaje = '';
        }

        echo '<p>' . $mensaje . '</p>';
        
        
        unset($_SESSION['register']);
    }
    ?>


</body>
</html>