<main>
<h1>Crear una cuenta</h1> 
<?php use Utils\Utils; ?>
<?php if(isset($_SESSION['register']) && $_SESSION['register'] == 'complete'): ?> 
    <h6><strong class="alert_green">Registro completado correctamente</strong> </h6>
    <?php elseif(isset($_SESSION['register']) && $_SESSION['register'] == 'failed'): ?> 
        <h6><strong class="alert_red">Registro fallido, introduzca bien los datos</strong> </h6>
        <?php elseif(isset($_SESSION['register']) && $_SESSION['register'] == 'failed_email_exists'): ?> 
        <h6><strong class="alert_red">Registro fallido, Ese email ya existe, utilice otro</strong> </h6>
        <?php endif; ?>
        
<?php Utils::deleteSession('register'); ?>
<form action="<?=BASE_URL?>usuario/registro/" method="POST"> 

<label for="nombre">Nombre</label>
<input type="text" name="data[nombre]" required/>

<label for="apellidos">Apellidos</label>
<input type="text" name="data[apellidos]" required/>

<label for="email">Email</label>
<input type="email" name="data[email]" required/>

<label for="password">Contraseña</label>
<input type="password" name="data[password]" required/>

<input type="submit" value="Registrarse" />
        </form>
</main>
