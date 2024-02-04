<?php
namespace Controllers;
use Lib\Pages;
use Models\Paciente;

class PacienteController{
   
    private Pages $pages;
    
    function __construct(){

        $this->pages = new Pages();
    }

    public function mostrarTodos(){
        $patient = new Paciente();
        $todos_mis_pacientes = $patient->getAll();
        $patient->desconecta();
        $patient = null;

        $this->pages->render('paciente/mostrar_todos', ['pacientes'=>$todos_mis_pacientes]);

    }
}
