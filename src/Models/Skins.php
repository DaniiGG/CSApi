<?php
namespace Models;
use Lib\BaseDatos;
use PDO;
use PDOException;

class Skins extends BaseDatos{

    private string $id;
    private string $nombre;
    private string $tipo;
    private string $imagen;
    private float $desgaste;
    private string $precio;
    
    public function __construct(
        
        ){
          parent::__construct();
        }

    public function getId(): int{
        return $this->id;
    }

    public function setId(int $id){
        $this->id = $id;
    }

    public function getNombre(): string{
        return $this->nombre;
    }

    public function setNombre(string $nombre){
        $this->nombre = $nombre;
    }

    public function getTipo() : string {
		return $this->tipo;
	}

	public function setTipo(string $value) {
		$this->tipo = $value;
	}

	public function getImagen() : string {
		return $this->imagen;
	}

	public function setImagen(string $value) {
		$this->imagen = $value;
	}

	public function getDesgaste() : float {
		return $this->desgaste;
	}

	public function setDesgaste(float $value) {
		$this->desgaste = $value;
	}

	public function getPrecio() : string {
		return $this->precio;
	}

	public function setPrecio(string $value) {
		$this->precio = $value;
	}
   
    /*Obtiene todos los registros de la tabla 'skins'.
    Realiza una consulta SQL para seleccionar todos los datos de la tabla 'skins'.
    Retorna un array con todos los resultados obtenidos.*/
    public function getAll(): ?array{
        $this->consulta("SELECT * FROM skins");
        return $this->extraer_todos();
    }

    /*Crea un nuevo registro de skin en la base de datos.
    Utiliza una consulta preparada para insertar un nuevo registro con los datos proporcionados.
    Retorna true si la operación de inserción es exitosa; de lo contrario, retorna false.*/
    public function creaSkin(): bool {
        $id = NULL;  
        $nombre = $this->getNombre();
        $tipo = $this->getTipo();
        $imagen = $this->getImagen();
        $desgaste = $this->getDesgaste();
        $precio = $this->getPrecio();
        try {
            $ins = $this->prepara("INSERT INTO skins (id, nombre, tipo, imagen, desgaste, precio)
                                            VALUES (:id, :nombre, :tipo, :imagen, :desgaste, :precio)");
            
            // Vincula los parámetros
            $ins->bindValue(':id', $id);
            $ins->bindValue(':nombre', $nombre, PDO::PARAM_STR);
            $ins->bindValue(':tipo', $tipo, PDO::PARAM_STR);
            $ins->bindValue(':imagen', $imagen, PDO::PARAM_STR);
            $ins->bindValue(':desgaste', $desgaste, PDO::PARAM_STR);
            $ins->bindValue(':precio', $precio, PDO::PARAM_STR);
    
            // Ejecuta la consulta
            $ins->execute();
    
            // Si se ejecuta correctamente, establece el resultado como verdadero
            $result = true;
        } catch (PDOException $e) {
            $result = false; // En caso de error, se establece el resultado como falso
        }
        $this->desconecta();
        return $result; // Retorna el resultado de la operación de inserción (true/false)
    }

    /*Busca un registro de skin por su ID en la base de datos.
    Utiliza una consulta preparada para seleccionar el registro con el ID proporcionado.
    Retorna un array de objetos que representan los resultados de la consulta.*/
    public function find($id){
        $query = "SELECT * FROM skins WHERE id = :id";



        try {
            $ins = $this->prepara($query);
            
            // Vincula los parámetros
            $ins->bindValue(':id', $id);
           
    
            // Ejecuta la consulta
            $ins->execute();
            $pruebas = $ins->fetchAll(PDO::FETCH_OBJ);
            $ins->closeCursor();
            $this->desconecta();
            // Si se ejecuta correctamente, establece el resultado como verdadero
            return $pruebas;
        } catch (PDOException $err) {
			return [];
		} 
    }

    /*Actualiza un registro de skin en la base de datos con los datos proporcionados.
    Utiliza una consulta preparada para actualizar los datos del registro con el ID proporcionado.
    Retorna true si la operación de actualización es exitosa; de lo contrario, retorna false. */
    public function update(array $data) {
        try {
            $id = $data["id"];
            $nombre = $data["nombre"];
            $tipo = $data["tipo"];
            $imagen = $data["imagen"];
            $desgaste = $data["desgaste"];
            $precio = $data["precio"];
    
            $query = "UPDATE skins SET nombre = :nombre, tipo = :tipo, imagen = :imagen, desgaste = :desgaste, precio = :precio WHERE id = :id";
            $statement = $this->prepara($query);
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $statement->bindParam(':tipo', $tipo, PDO::PARAM_STR);
            $statement->bindParam(':imagen', $imagen, PDO::PARAM_STR);
            $statement->bindParam(':desgaste', $desgaste, PDO::PARAM_STR);
            $statement->bindParam(':precio', $precio, PDO::PARAM_STR);
            $result = $statement->execute();
            $statement->closeCursor();
            $this->desconecta();
            return $result;
        } catch (PDOException $err) {
            return false;
        }
    }
    
    /*Busca registros de skins por su tipo en la base de datos.
    Utiliza una consulta preparada para seleccionar registros con el tipo proporcionado.
    Retorna un array de objetos que representan los resultados de la consulta. */
    public function findTipo($tipo){
        $query = "SELECT * FROM skins WHERE tipo = :tipo";



        try {
            $ins = $this->prepara($query);
            
            // Vincula los parámetros
            $ins->bindValue(':tipo', $tipo);
            // Ejecuta la consulta
            $ins->execute();
            $pruebas = $ins->fetchAll(PDO::FETCH_OBJ);
            $ins->closeCursor();
            // Si se ejecuta correctamente, establece el resultado como verdadero
            $this->desconecta();
            return $pruebas;
        } catch (PDOException $err) {
			return [];
		} 
    }

    /*Busca registros de skins por precio (parcial o completo) en la base de datos.
    Utiliza una consulta preparada para seleccionar registros con el precio que contiene el valor proporcionado.
    Retorna un array de objetos que representan los resultados de la consulta. */
    public function findPrecio($precio){
        $query = "SELECT * FROM skins WHERE precio LIKE :precio";



        try {
            $ins = $this->prepara($query);
            
            // Vincula los parámetros
            $ins->bindValue(':precio', '%' . $precio . '%');
            // Ejecuta la consulta
            $ins->execute();
            $pruebas = $ins->fetchAll(PDO::FETCH_OBJ);
            $ins->closeCursor();
            // Si se ejecuta correctamente, establece el resultado como verdadero
            $this->desconecta();
            return $pruebas;
        } catch (PDOException $err) {
			return [];
		} 
    }


    /*Elimina un registro de skin de la base de datos por su ID.
    Utiliza la función find para obtener información del registro antes de eliminarlo.
    Retorna true si la operación de eliminación es exitosa y el registro existe; de lo contrario, retorna false.*/
    public function delete($id) {
        try {
            // Utiliza el método find para obtener la información del registro antes de eliminarlo
            $registro = $this->find($id);
    
            // Verifica si se encontró el registro antes de continuar con la eliminación
            if (!empty($registro)) {
                $query = "DELETE FROM skins WHERE id = :id";
                $statement = $this->prepara($query);
                $statement->bindParam(':id', $id, PDO::PARAM_INT);
    
                $result = $statement->execute();
                $statement->closeCursor();
                $this->desconecta();
                return $result;
            } else {
                // Si no se encuentra el registro, devuelve falso
                return false;
            }
        } catch (PDOException $err) {
            return false;
        }
    }

    /*Cierra la conexión a la base de datos.
    Utiliza el método cierraConexion (que probablemente está definido en algún lugar no proporcionado) para cerrar la conexión a la base de datos. */
    public function desconecta():void
    {
        $this->cierraConexion();
    }


}