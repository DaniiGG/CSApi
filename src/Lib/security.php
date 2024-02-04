<?php
namespace Lib;
use Controllers\UsuarioController;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Models\Usuario;
use PDO;
use PDOException;
use Lib\ResponseHttp;

class Security{



    final public static function clavesecreta():string{
        return $_ENV["SECRET_KEY"];
    }


    /**
     * Método estático para encriptar una contraseña.
     *
     * @param string $password Contraseña a encriptar.
     * @return string Contraseña encriptada.
     */
    final public static function encriptarContrasena($password)
    {
        // Encriptar la contraseña
        $contrasenaEncriptada = password_hash($password, PASSWORD_BCRYPT);

        return $contrasenaEncriptada;
    }


    /**
     * Verificar si una contraseña coincide con su hash.
     *
     * @param string $password Contraseña a verificar.
     * @param string $hash Hash de la contraseña almacenada.
     * @return bool Retorna true si la contraseña es válida, false de lo contrario.
     */
    public static function verificarContrasena($password, $hash)
    {
        // Verificar si la contraseña coincide con su hash
        return password_verify($password, $hash);
    }


     /**
     * Generar un token aleatorio.
     *
     * @param string $key longitud del token.
     * @return string Token generado.
     */
    final public static function generarToken( string $key, array $data):string
    {
        // Generar bytes aleatorios

        $time=strtotime("now");
        $token =array(
            "iat"=>$time,
            "exp"=>$time +3600,
            "data"=>$data
        );

        return JWT::encode($token, $key, 'HS256');
    }



    final public static function getToken(){
    
        $headers = apache_request_headers(); // recoger las cabeceras en el servidor Apache
        if(!isset($headers['Authorization'])) { // comprobamos que existe la cabecera authoritation
            return $response['message'] = json_decode( ResponseHttp::statusMessage( 403, 'Acceso denegado', "No hay token"));
        }
        try{
        
        $authorizationArr = explode(' ', $headers['Authorization']);
        $token= $authorizationArr[1];
        return ["data"=>JWT::decode($token, new Key (Security::clavesecreta(), 'HS256')),"token"=>$token];
        }catch (\Exception $exception){
        return $response['message']= json_decode(ResponseHttp::statusMessage (401,  'Token expirado o invalido', $token));
        
        }
        
    }


    final public static function validateToken(){
      
        $data=self::getToken();
        $info=$data;
        if(isset($info->status) && $info->status=="Unauthorized"){
            $usuario=new Usuario();
           $usuarioPorToken= $usuario->buscarUsuarioPorToken($info->token);

           if($usuarioPorToken){
            $nuevoToken=Security::generarToken(Security::clavesecreta(), ["email"=>$usuarioPorToken[0]["email"]]);

            $usuarioPorToken[0]["token"]=$nuevoToken;
            $usuario=Usuario::fromArray($usuarioPorToken[0]);
            $update=$usuario->update();

            if($update){
                UsuarioController::sendConfirmationEmail($usuarioPorToken[0]["email"], $nuevoToken);
                
            }
            echo"Su token de autenticación ha expirado, se le ha enviado uno nuevo a su correo";

           }else{
            echo"Su token de autenticación es invalido";
           }

            return false;

      
      } else if(isset($info->status) && $info->status=="Forbidden"){

        echo "Error de autorización";
        return false;
      }else{
        return $data;
        

      }
        /**En $info->data tenemos el id del usuario.
        * Podemos acceder a la base de datos y comprobar que existe un usuario con ese identificador en nuestra
        * También podemos verificar si coinciden las fechas de expiración de token.
        * return verdadero o falso
        */

    }
}

