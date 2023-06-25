<?php
namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController{
    public static function login(Router $router){
        $alertas = [];
        
        if($_SERVER["REQUEST_METHOD"] === 'POST'){
            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin();

            if(empty($alertas)){
                //Validar que exista el Email
                $usuario = Usuario::where('email',$auth->email);
                if($usuario){
                    //Valida Contraseña y que este confirmado
                    
                    if($usuario->comprobarPasswordAndConfirmado($auth->password)){
                        
                        //Autenticar al usuario
                        session_start();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;


                        if($usuario->admin  === '1'){
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('Location: /admin');
                        }else{
                            header('Location: /cita');
                        }

                    }

                }else{
                    $alertas = Usuario::setAlerta('error','Usuario no registrado');
                }
            }

            $alertas = Usuario::getAlertas();
        }
        
        $router->render('auth/login',[
            'alertas' => $alertas
        ]);
    }

    public static function logout(){
        session_start();
        $_SESSION = [];
        header('Location: /');
    }

    public static function olvide(Router $router){
        $alertas = [];

        if($_SERVER["REQUEST_METHOD"] === 'POST'){
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

            if(empty($alertas)){
                //Valida que exista el correo
                $usuario = Usuario::where('email',$auth->email);
                if($usuario && $usuario->confirmado === '1'){
                    
                    
                    //Genera un Token
                    $usuario->crearToken();
                    $usuario->guardar();

                    //Enviar Email
                    $email = new Email($usuario->nombre,$usuario->email,$usuario->token);
                    $email->enviarInstrucciones();
                    
                    Usuario::setAlerta('exito','Revisa tu email');
                }else{
                    Usuario::setAlerta('error','El usuario no existe o no esta confirmado');
                }
            }

            $alertas = Usuario::getAlertas();
    
        }


        $router->render('auth/olvide-password',[
            'alertas' => $alertas
        ]);
    }

    public static function recuperar(Router $router){
        $alertas = [];
        $error = false; //Solo es para ocultar con un return el formulario

       //Buscar si el token existe en la bd
       $token = s($_GET['token']);
       $usuario = Usuario::where('token',$token);


       if(empty($usuario)){
        Usuario::setAlerta('error','Token No valido');
        $error = true;
       }

       if($_SERVER["REQUEST_METHOD"] === 'POST'){
            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();

            if(empty($alertas)){
                
                //Del objeto actual $usuario reescribe el password
                //Primero como null para que se elimine el password anterior y luego asigna 
                //El nuevo password que leiste del post

                $usuario->password = null;
                $usuario->password = $password->password;
                $usuario->hashearPassword();
                $usuario->token = null;
                $resultado = $usuario->guardar();
                
                if($resultado){
                    header('Location: /');
                }
            }
       }

       $alertas = Usuario::getAlertas();

        $router->render('auth/recuperar-password',[
            'alertas' => $alertas,
            'error' => $error
        ]);
    }

    public static function crear(Router $router){
        $alertas = [];
        $usuario = new Usuario();
        

        if($_SERVER["REQUEST_METHOD"] === 'POST'){
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarCuentaNueva();

            if(empty($alertas)){
                //Valida que el usuario Exista
                $resultado = $usuario->existeUsuario();

                if($resultado->num_rows){
                    $alertas = Usuario::getAlertas();
                }

                //Hashear Password
                $usuario->hashearPassword();
                
                //Generar Token
                $usuario->crearToken();
                
                //Enviar el Email
                $email = new Email($usuario->nombre,$usuario->email,$usuario->token);
                $resultado = $email->enviarConfirmacion();
                $resultado = $usuario->guardar();
                if($resultado){
                    header('Location: /mensaje');
                }

            }
        }

        $router->render('auth/crear-cuenta',[
            'alertas' => $alertas,
            'usuario' => $usuario
        ]);
    }


    public static function confirmarCuenta(Router $router){
        $alertas = [];
        $token = s($_GET['token']);
        $usuario = Usuario::where('token',$token);
        if(empty($usuario)){
            Usuario::setAlerta('error','Token no valido');
        }else{
            //Token Valido confirmar cuenta
            //debuguear($usuario)  cambiamos el confirmado de 0 a 1 y eliminamos el token
            $usuario->confirmado = "1";
            $usuario->token = null;
            $usuario->guardar();
            Usuario::setAlerta('exito','Cuenta confirmada Correctamente');      
        }

       $alertas = Usuario::getAlertas();
       $router->render('auth/confirmar-cuenta',[
        'alertas' => $alertas
       ]);
    }


    public static function mensaje(Router $router){
        $router->render('auth/mensaje');
    }
}