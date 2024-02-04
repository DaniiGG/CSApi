<?php

namespace Controllers;

use Lib\Pages;
use Models\Security;

    
    

class AuthController{

    private Pages $pages;
    function __construct(){

        $this->pages = new Pages();
    }

    public function pruebas(){

    }
}