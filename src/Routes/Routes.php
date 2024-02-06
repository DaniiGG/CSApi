<?php

namespace Routes;

use Controllers\AuthController;
use Controllers\DashBoardController;
use Controllers\SkinsController;
use Controllers\UsuarioController;
use Controllers\errorController;
use Lib\Router;

class Routes
{
    public static function index(){
       

        Router::add('GET','/read',function (){
            return (new SkinsController())->read();
        });
        Router::add('GET','/read/tipo/:id',function ($id){
            return (new SkinsController())->findByTipo($id);
        });

        Router::add('GET','/read/precio/:id',function ($id){
            return (new SkinsController())->findByPrecio($id);
        });

        Router::add('POST','/create',function (){
            return (new SkinsController())->create();
        });

        Router::add('PUT','/update',function (){
            return (new SkinsController())->update();
        });

        Router::add('DELETE','/delete/:id',function ($id){
            return (new SkinsController())->delete($id);
        });
        


        Router::add('GET','/pruebas',function (){
            return (new AuthController())->pruebas();
        });

        Router::add('GET','usuario/login/',function (){
            return (new UsuarioController())->identifica();
            
        });

        Router::add('POST','usuario/login',function (){
            return (new UsuarioController())->login();
        });


        Router::add('GET','usuario/logout/',function (){
            return (new UsuarioController())->logout();
            
        });

        Router::add('GET','usuario/registro/',function (){
            return (new UsuarioController())->registro();
        });

        Router::add('POST','usuario/registro/',function (){
            return (new UsuarioController())->registro();
        });




        Router::add('GET', 'usuario/confirmacion/:token', function ($token) {
            return (new UsuarioController())->confirmarRegistro2($token);
        });

        Router::add('GET', 'usuario/nuevoToken', function () {
            return (new UsuarioController())->nuevoToken();
        });

        Router::add('GET', 'api/api', function () {
            return (new UsuarioController())->vistaApi();
        });


        Router::add('GET','/',function (){
            return (new DashBoardController())->index();
        });


        Router::add('GET','/Error/error/', function (){
            return (new errorController())->error404();
        });

        Router::dispatch();
    }
}