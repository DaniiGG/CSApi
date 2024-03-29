<?php

namespace Lib;

use PDO;
use PDOException;

class BaseDatos extends PDO {
    private mixed $resultado;
    private $tipo_de_base;
    private string $servidor;
    private string $usuario;
    private string $pass;
    private string $base_datos;

    public function __construct() {
        
        $this->tipo_de_base = 'mysql';
        $this->servidor = $_ENV["DB_HOST"];
        $this->usuario = $_ENV["DB_USER"];
        $this->pass = $_ENV["DB_PASS"];
        $this->base_datos = $_ENV["DB_DATABASE"];
        
    
        try {
            $opciones = array(
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                PDO::MYSQL_ATTR_FOUND_ROWS => true
            );
            parent::__construct("{$this->tipo_de_base}:dbname={$this->base_datos};host={$this->servidor}", $this->usuario, $this->pass, $opciones);
        } catch (PDOException $e) {
            echo "errores" . $e->getMessage();
        }
    }

    public function consulta(string $querySQL): void {
        $this->resultado = $this->query($querySQL); // Utilizar $this directamente como instancia de PDO
    }

    public function extraer_registro(): mixed {
        return ($fila = $this->resultado->fetch(PDO::FETCH_ASSOC)) ? $fila : false;
    }

    public function extraer_todos(): array {
        return $this->resultado->fetchAll(PDO::FETCH_ASSOC);
    }

    public function prepara(string $query): mixed {
        return $this->prepare($query);
}

    public function filasAfectadas(): int {
        return $this->resultado->rowCount();
    }

    public function cierraConexion():void {

        $this->conexion = null;
    }
}