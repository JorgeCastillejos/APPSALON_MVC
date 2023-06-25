<?php
namespace Controllers;

use Model\AdminCita;
use MVC\Router;

class AdminController{

    public static function index(Router $router){

        session_start();

        //Validamos que sea Administrador
        isAdmin();
    
        $fecha = $_GET['fecha'] ?? date('Y-m-d'); //  strtotime('- 1 day')
        $fechas = explode('-',$fecha);
        if(!checkdate($fechas[1],$fechas[2],$fechas[0])){
            header('Location: /404');
        }
        

        //Aqui haremos la consulta
        $consulta = "SELECT citas.id, citas.hora, CONCAT (usuarios.nombre, ' ', usuarios.apellido) as cliente,"; 
        $consulta .= " usuarios.email, usuarios.telefono, servicios.nombre as servicio, servicios.precio FROM citas LEFT OUTER JOIN usuarios ON";
        $consulta .= " citas.usuarioId=usuarios.id LEFT OUTER JOIN citasservicios ON citasservicios.citaId = citas.id LEFT OUTER JOIN servicios ON servicios.id = citasservicios.servicioId";
        $consulta .= " WHERE fecha = '${fecha}'";
        
        $citas = AdminCita::SQL($consulta);
        

        $router->render('admin/index',[
            'nombre' => $_SESSION['nombre'],
            'citas' => $citas,
            'fecha' => $fecha
        ]);
    }

    
    
}