<?php
namespace Controllers;

use Model\AdminCita;
use Model\Cita;
use Model\CitaServicio;
use Model\Servicio;

class APIController{
    
    public static function index(){
        
        $servicios = Servicio::all();
        echo json_encode($servicios);
    }

    public static function guardar(){
        // //Guarda en citas
         $cita = new Cita($_POST);
         $resultado = $cita->guardar();
        
        // //citaID
         $citaId = $resultado['id'];
        
        // //Guardando en citasservicios citaId, servicioId

       $serviciosId = explode(",",$_POST['servicioId']);
       foreach($serviciosId as $servicioId){
        $args = [
            'citaId' => $citaId,
            'servicioId' => $servicioId
        ];

        $citasservicios = new CitaServicio($args);
        $citasservicios->guardar();
       }

        echo json_encode(['resultado' => $resultado]);
    }

    public static function eliminar(){
        if($_SERVER["REQUEST_METHOD"] === 'POST'){
            $id = $_POST['id'];
            $cita = Cita::find($id);
            $cita->eliminar();
            header('Location: '. $_SERVER["HTTP_REFERER"]);
        }
    }

}