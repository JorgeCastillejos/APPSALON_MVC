<?php
namespace Controllers;

use Model\Servicio;
use Model\Usuario;
use MVC\Router;

class ServicioController{

    public static function index(Router $router){
        session_start(); //Iniciamos la sesion solo para pasarle el nombre
        isAdmin();

        $servicios = Servicio::all();
        

        $router->render('servicios/index',[
            'nombre' => $_SESSION['nombre'],
            'servicios' => $servicios
        ]);
    }

    public static function crear(Router $router){
        session_start(); //Iniciamos la sesion solo para pasarle el nombre
        isAdmin();
        $servicio = new Servicio();
        $alertas = [];
        

        if($_SERVER["REQUEST_METHOD"] === 'POST'){
            $servicio->sincronizar($_POST);
            $alertas = $servicio->validar();
            if(empty($alertas)){
                $servicio->guardar();
                header('Location: /servicios');
            }
           
            
        }

        $alertas = Servicio::getAlertas();

        $router->render('servicios/crear',[
            'nombre' => $_SESSION['nombre'],
            'alertas' => $alertas,
            'servicio' => $servicio
        ]);
    }

    public static function actualizar(Router $router){
        session_start();//Iniciamos la sesion solo para pasarle el nombre
        isAdmin();
        

        if(!is_numeric($_GET['id'])) return; //Is numeric retorna true o false
        $servicio = Servicio::find($_GET['id']);
        $alertas = [];
        
        if($_SERVER["REQUEST_METHOD"] === 'POST'){
          $servicio->sincronizar($_POST);
          $alertas = $servicio->validar();

          if(empty($alertas)){
            $servicio->guardar();
            header('Location: /servicios');
          }
        }

        $router->render('servicios/actualizar',[
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }

    public static function eliminar(){
        isAdmin();

        if($_SERVER["REQUEST_METHOD"] === 'POST'){
           $id = $_POST['id'];
            $servicio = Servicio::find($id);
            $servicio->eliminar();
            header('Location: /servicios');
        }
    }
}