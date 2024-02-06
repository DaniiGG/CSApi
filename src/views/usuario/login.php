
<main>
<h1>Login</h1>
<?php use Utils\Utils; ?>
 
    <?php if(isset($_SESSION['register']) && $_SESSION['register'] == 'failed'): ?> 
        <h6><strong class="alert_red">Registro fallido, introduzca bien los datos</strong></h6> 

        <?php elseif(isset($_SESSION['register']) && $_SESSION['register'] == 'expired'): ?> 
        <h6><strong class="alert_red">Confirmación expirada, se le ha mandado un correo con una confirmacion</strong></h6> 

        <?php elseif(isset($_SESSION['register']) && $_SESSION['register'] == 'token_invalid'): ?> 
        <h6><strong class="alert_red">Token invalido</strong></h6> 

        <?php elseif(isset($_SESSION['register']) && $_SESSION['register'] == 'confirmed'): ?> 
        <h6><strong class="alert_green">Registro confirmado</strong></h6> 

        <?php elseif(isset($_SESSION['register']) && $_SESSION['register'] == 'send_confirmation'): ?> 
            <h6><strong class="alert_orange">Se ha enviado un link de confirmacion a su correo</strong> </h6>

            <?php elseif(isset($_SESSION['register']) && $_SESSION['register'] == 'login_required'): ?> 
            <h6><strong class="alert_orange">Para usar la api es necesario iniciar sesión</strong> </h6>
            
        <?php endif; ?>
        
<?php Utils::deleteSession('register'); ?>
<form action="<?=BASE_URL?>usuario/login/" method="post"> 
<label for="email">Email</label>
<input type="email" name="data[email]" id="email" />
<label for="password">Contraseña</label>
<input type="password" name="data[password]" id="password"/> 
<input type="submit" value="Enviar" />
</form>


<h6 class="registrar">¿No tiene cuenta?<a href="<?=BASE_URL?>usuario/registro">Regístrese</a></h6>
</main>
