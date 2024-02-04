<?php
namespace Controllers;
Use Lib\Pages;
use Models\Usuario;
use Lib\Security;
Use Utils\Utils;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class UsuarioController{
    private Pages $pages;

    function __construct(){

        $this->pages= new Pages();
        

    }

    /*
    Gestiona el proceso de registro de usuarios.
    Verifica si el método de solicitud HTTP es POST y si se proporcionan los datos necesarios en el POST (nombre, apellidos, correo electrónico y contraseña).
    Encripta la contraseña, genera un token de sesión único utilizando el correo electrónico, crea un objeto Usuario, lo guarda en la base de datos y envía un correo de confirmación.
    Se establecen variables de sesión para indicar el éxito o fracaso del registro.
    Finalmente, renderiza la página de registro.
    */ 
    
    public function registro(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Falta sanear y validar
            if (!empty($_POST['data']['nombre']) && !empty($_POST['data']['apellidos']) && !empty($_POST['data']['email']) && !empty($_POST['data']['password'])) {
                // Todos los campos están presentes
    
                $registrado = $_POST['data'];
    
                // Encriptar contraseña
                $registrado['password'] = password_hash($registrado['password'], PASSWORD_BCRYPT, ['cost' => 4]);
                
                // Generar un token de sesión único
                $tokenSesion = Security::generarToken(Security::clavesecreta(), ["email"=>$_POST['data']['email']]);
                $registrado['token'] = $tokenSesion;
                
                
                
                $usuario = Usuario::fromArray($registrado);
    
                $save = $usuario->save();
    
                if ($save) {
                    // Enviar correo de confirmación
                    $this->sendConfirmationEmail($registrado['email'], $tokenSesion);
    
                    $_SESSION['register'] = "complete";
                } else {
                    $_SESSION['register'] = "failed";
                }
            } else {
                // Faltan campos, registro fallido
                $_SESSION['register'] = "failed";
            }
        }
    
        $this->pages->render('usuario/registro');
    }


    /*
    Maneja el proceso de inicio de sesión de usuarios.
    Verifica si el método de solicitud HTTP es POST y si se proporcionan los datos necesarios.
    Intenta autenticar al usuario buscando su correo electrónico en la base de datos.
    Si la autenticación es exitosa y el usuario está confirmado, establece variables de sesión para la identidad del usuario y lo ingresa.
    Si el usuario no está confirmado, genera un nuevo token de confirmación, actualiza el registro del usuario, envía un correo de confirmación e indica al usuario que confirme su registro.
    Si la autenticación falla, renderiza la página de inicio de sesión.
    */ 
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['data'])) {
                $auth = $_POST['data']; // SANEAR Y VALIDAR
    
                // Buscar el usuario en la base de datos por su correo electrónico
                $usuario = Usuario::fromArray($auth);

                $identity = $usuario->login();
    
                // Verificar si la autenticación fue exitosa y el usuario está confirmado
                if ($identity && is_object($identity)) {
                    if ($usuario->getConfirmado()) {
                        $_SESSION['register'] = 'logueado';
                        $_SESSION['identity'] = $identity;
    
                        header("Location:" . BASE_URL . "/");
                        exit;
                    } else {
                        $tokenSesion = Security::generarToken(Security::clavesecreta(), ["email"=>$_POST['data']['email']]);

                        $usuarioEmail=$usuario->buscaMail($_POST['data']['email']);

                        $usuarioEmail->token=$tokenSesion;

                        $usuario=Usuario::fromArray(["id"=>$usuarioEmail->id, "nombre"=>$usuarioEmail->nombre, "apellidos"=>$usuarioEmail->apellidos, "email"=>$usuarioEmail->email, "password"=>$usuarioEmail->password, "rol"=>$usuarioEmail->rol, "confirmado"=>$usuarioEmail->confirmado, "token"=>$usuarioEmail->token, "token_exp"=>date("Y-m-d H:i:s", time()+3600)]);

                        $usuario->update();

                        $this->sendConfirmationEmail($auth['email'], $tokenSesion);

                        $_SESSION['register'] = 'send_confirmation';
                        $this->pages->render('usuario/login');
                        
                    }
                } else {
                    // La autenticación falló
                    $_SESSION['register'] = 'failed';
                    $this->pages->render('usuario/login');
                }
            }
        } else {
            $this->pages->render('usuario/login');
        }
    }


   

    /*
    Envía un correo electrónico de confirmación a la dirección especificada con un token de confirmación.
    Utiliza la biblioteca PHPMailer para configurar y enviar el correo electrónico.
    El correo electrónico incluye un enlace de confirmación con el token generado.
    */ 
    public static function sendConfirmationEmail($email, $confirmationToken): void {
        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'pruebasphpdanii@gmail.com';
            $mail->Password = 'fiqe mevv lxjo yhqn';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Configuración del correo
            $mail->setFrom('pruebasphpdanii@gmail.com', 'Api');
            $mail->addAddress($email);
            $mail->Subject = 'Confirmación de Registro';


            $mail->isHTML(TRUE);
            $mail->CharSet='UTF-8';
            
            // Cuerpo del correo
            $confirmationLink = "http://localhost/ApiRestful/";
            $contenido = "<html>";
            $contenido .= "<p>Hola, $email</p>";
            $contenido .= "<p>Este es el token de confirmación: $confirmationToken</p>";
            $contenido .= "<p>Haz clic en el siguiente enlace para confirmar tu registro: <a href=$confirmationLink>Volver a la api</a></p>";
            $contenido .= "</html>";
            $mail->Body = $contenido;

            // Enviar el correo
            $mail->send();
        } catch (Exception $e) {
            // Manejar errores si es necesario
            echo "Error al enviar el correo: {$mail->ErrorInfo}";
        }
    }



    /**Maneja la confirmación de registro de usuarios mediante un token.
    Valida el token y extrae los datos.
    Verifica si el usuario con el correo electrónico proporcionado existe y si el token coincide.
    Si se cumplen las condiciones, actualiza el registro del usuario para confirmar el registro.
    Muestra un mensaje de éxito si la confirmación del registro es exitosa; de lo contrario, indica que el correo electrónico no existe.
     */
    public function confirmarRegistro2(): void {
        try {
            
        $todo= Security::validateToken();
        
            if($todo){
                $data=$todo["data"];
            $usuario=new Usuario();
                
            $usuarioEmail=$usuario->buscaMail($data->data->email);
            
            if($usuarioEmail && ($usuarioEmail->token==$todo["token"])){
    
                
                $usuario=Usuario::fromArray(["id"=>$usuarioEmail->id, "nombre"=>$usuarioEmail->nombre, "apellidos"=>$usuarioEmail->apellidos, "email"=>$usuarioEmail->email, "password"=>$usuarioEmail->password, "rol"=>$usuarioEmail->rol, "confirmado"=>true, "token"=>"", "token_exp"=>date("Y-m-d H:i:s", time()-1)]);
    
                
                $usuario->update();
    
                echo "Su registro ha sido confirmado con exito";
    
            }else{
            echo"No existe ese correo";
            }

        }
        
        

        } catch (Exception $e) {
            echo "Error al confirmar el registro: " . $e->getMessage();
            
        }

        //$this->pages->render('usuario/confirmacion');
    }


    /**Genera un nuevo token de sesión para el usuario conectado y actualiza el registro del usuario con el nuevo token.
    Renderiza la página de la API con el nuevo token si la actualización es exitosa.
     * 
     */

    public function nuevoToken(){

        if(isset($_SESSION['identity'])){

            $email=$_SESSION['identity']->email;

            $tokenSesion = Security::generarToken(Security::clavesecreta(), ["email" =>$email]);
            

            $usuario=Usuario::fromArray(["id"=>$_SESSION['identity']->id, "nombre"=>$_SESSION['identity']->nombre, "apellidos"=>$_SESSION['identity']->apellidos, "email"=>$_SESSION['identity']->email, "password"=>$_SESSION['identity']->password, "rol"=>$_SESSION['identity']->rol, "confirmado"=>true, "token"=>$tokenSesion, "token_exp"=>date("Y-m-d H:i:s", time()+3600)]);
    
            $actualizado=$usuario->update();
            if($actualizado){
                $this->pages->render('api/api', ["token"=>$tokenSesion]);
            }

        }

    }

    /*Renderiza la página de la API.*/
    public function vistaApi(){
        $this->pages->render('api/api');
    }

    /*Renderiza la página de inicio de sesión.*/
    public function identifica(){
        $this->pages->render('usuario/login');
    }

    /*Cierra la sesión del usuario, eliminando la variable de sesión 'identity'.
    Redirecciona a la página principal (BASE_URL).*/
    public function logout(){

        Utils::deleteSession('identity');
    header("Location:".BASE_URL);

    }



}
        



    