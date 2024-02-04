<?php
namespace Models;
use Lib\BaseDatos;
use PDOException;
use PDO;


class Usuario{
    private string|null $id;
    private string $nombre;
    private string $apellidos;
    private string $email; 
    private string $password;
    private string $rol;
    private bool $confirmado;
    private string $token;
    private string $token_exp;
    private BaseDatos $db;
    // Errores
    // protected static $errores
    public function  __construct(string $id="", string $nombre="", string $apellidos="", string $email="", string $password="", string $rol="",bool $confirmado=false,  string $token="", string $token_exp="" )
    {
    $this->db = new BaseDatos();
    $this->id = $id;
    $this->nombre = $nombre ;
    $this->apellidos = $apellidos;
    $this->email = $email;
    $this->password = $password;
    $this->rol = $rol;
    $this->confirmado = $confirmado;
    $this->token = $token;
    $this->token_exp = $token_exp;
    }

    public function getId(): string|null {
        return $this->id;
    }

    public function getNombre(): string {
        return $this->nombre;
    }

    public function getApellidos(): string {
        return $this->apellidos;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function getRol(): string {
        return $this->rol;
    }

    // Setters
    public function setId(string|null $id): void {
        $this->id = $id;
    }

    public function setNombre(string $nombre): void {
        $this->nombre = $nombre;
    }

    public function setApellidos(string $apellidos): void {
        $this->apellidos = $apellidos;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function setPassword(string $password): void {
        $this->password = $password;
    }

    public function setRol(string $rol): void {
        $this->rol = $rol;
    }


    public function getConfirmado() : bool {
		return $this->confirmado;
	}

	public function setConfirmado(bool $confirmado) {
		$this->confirmado = $confirmado;
	}

	public function getToken() : string {
		return $this->token;
	}

	public function setToken(string $token) {
		$this->token = $token;
	}


	public function getToken_exp() : string {
		return $this->token_exp;
	}

	public function setToken_exp(string $token_exp) {
		$this->token_exp = $token_exp;
	}

    public static function fromArray(array $data): Usuario
    {
    return new Usuario(
    $data['id'] ?? '',
    $data['nombre'] ?? '',
    $data['apellidos'] ?? '',
    $data['email'] ?? '',
    $data['password'] ?? '',
    $data['rol'] ?? '',
    $data['confirmado'] ?? false,
    $data['token'] ?? '',
    $data['token_exp'] ?? 0,

    );
    }

public function desconecta() : void{
    $this->db==null;
}

    
public function save() { 
    //if(isset($contacto['Contacto']['id']
    if($this->getId()){
    return $this->update();
    } else {
    return $this->create();
    }
    }

    
    public function create(): bool {
        $id = NULL;
        $nombre = $this->getNombre();
        $apellidos = $this->getApellidos();
        $email = $this->getEmail();
        $password = $this->getPassword();
        $rol = 'user';
        $confirmado = $this->getConfirmado();
        $token = $this->getToken();
        $token_exp = $this->getToken_exp();
        $result = false;
        
    
        try {
            $ins = $this->db->prepare("INSERT INTO usuarios (id, nombre, apellidos, email, password, rol, confirmado, token, token_exp) VALUES (:id, :nombre, :apellidos, :email, :password, :rol, :confirmado, :token, :token_exp)");
            $ins->bindValue(':id', $id);
            $ins->bindValue(':nombre', $nombre, PDO::PARAM_STR);
            $ins->bindValue(':apellidos', $apellidos, PDO::PARAM_STR);
            $ins->bindValue(':email', $email, PDO::PARAM_STR);
            $ins->bindValue(':password', $password, PDO::PARAM_STR);
            $ins->bindValue(':rol', $rol, PDO::PARAM_STR);
            $ins->bindValue(':confirmado', $confirmado, PDO::PARAM_STR);
            $ins->bindValue(':token', $token, PDO::PARAM_STR);
            $ins->bindValue(':token_exp', $token_exp, PDO::PARAM_STR);
            $ins->execute();
            
            $result = true;
        } catch (PDOException $e) {
            $result = false;
        }
    
        $this->desconecta();
        return $result;
    }



     public function buscaMail($email): bool|object {
        $result = false;
    
        // Comprobar si existe el usuario
        $cons = $this->db->prepare("SELECT * FROM usuarios WHERE email = :email");
        $cons->bindValue(':email', $email, PDO::PARAM_STR);
    
        try {
            $cons->execute();
    
            if ($cons && $cons->rowCount() == 1) {
                $result = $cons->fetch(PDO::FETCH_OBJ);
                
            }
        } catch (PDOException $err) {
            // Log or handle the exception
            // For example: error_log("Error in buscaMail(): " . $err->getMessage());
        }
    
        $this->desconecta();
        return $result;
    }

public function login(): bool|object {
    $result = false;
    $email = $this->email;
    $password = $this->password;

    // Buscar el usuario en la base de datos por su correo electrónico
    $usuario = $this->buscaMail($email);

    if ($usuario !== false) {
        // Verificar si el usuario está confirmado
       
            $verify = password_verify($password, $usuario->password);
            
            if ($verify) {
                $this->confirmado = $usuario->confirmado;
                $result = $usuario;
                $this->nombre = $usuario->nombre;
                $this->apellidos = $usuario->apellidos;
                $this->rol = $usuario->rol;
                $this->id = $usuario->id;
            }
        
    }

    $this->desconecta();
    return $result;
}
public function update(): bool {
    $id=$this->getId();
    $nombre = $this->getNombre();
    $apellidos = $this->getApellidos();
    $email = $this->getEmail();
    $password = $this->getPassword();
    $rol = $this->getRol();
    $confirmado = $this->getConfirmado();
    $token = $this->getToken();
    $token_exp = $this->getToken_exp();

    try {
        $upd = $this->db->prepare("UPDATE usuarios SET nombre = :nombre, apellidos = :apellidos, email = :email, password = :password, rol = :rol, confirmado = :confirmado, token = :token, token_exp = :token_exp WHERE id = :id");
        $upd->bindValue(':id', $id, PDO::PARAM_STR);
        $upd->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $upd->bindValue(':apellidos', $apellidos, PDO::PARAM_STR);
        $upd->bindValue(':email', $email, PDO::PARAM_STR);
        $upd->bindValue(':password', $password, PDO::PARAM_STR);
        $upd->bindValue(':rol', $rol, PDO::PARAM_STR);
        $upd->bindValue(':confirmado', $confirmado, PDO::PARAM_STR);
        $upd->bindValue(':token', $token, PDO::PARAM_STR);
        $upd->bindValue(':token_exp', $token_exp, PDO::PARAM_STR);  
       
        $upd->execute();
        return true;
    } catch (PDOException $e) {
        // Manejar la excepción, por ejemplo, puedes agregar un log
        error_log("Error en el método update: " . $e->getMessage());
        return false;
    }
}

	

public function buscarUsuarioPorToken($token) {
    $result = false;
    
    // Comprobar si existe el usuario por el token
    try{
    $cons = $this->db->prepare("SELECT * FROM usuarios WHERE token = :token");
    $cons->bindValue(':token', $token, PDO::PARAM_STR);
    $cons->execute();
    $datos = $cons->fetchAll(PDO::FETCH_ASSOC);
	$cons->closeCursor();
	$cons=null;
	$this->desconecta();
	return $datos;
    }
    catch(PDOException){
        return false;
    }
   
}




public function updateToken(string $token, string $token_exp): bool {
    // Verificar si hay un ID y tokens válidos
    if (!$this->email || !$token || !$token_exp) {
        return false;
    }

    try {
        $upd = $this->db->prepare("UPDATE usuarios SET token = :token, token_exp = :token_exp WHERE email = :email");
        $upd->bindValue(':email', $this->email, PDO::PARAM_STR);
        $upd->bindValue(':token', $token, PDO::PARAM_STR);
        $upd->bindValue(':token_exp', $token_exp, PDO::PARAM_STR);
        $upd->execute();
        
        return true;
    } catch (PDOException $e) {
        // Manejar la excepción, por ejemplo, puedes agregar un log
        error_log("Error en el método updateToken: " . $e->getMessage());
        return false;
    }
}


}