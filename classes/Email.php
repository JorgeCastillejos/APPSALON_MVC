<?php
namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email{

    public $nombre;
    public $email;
    public $token;

    public function __construct($nombre,$email,$token)
    {
        $this->nombre = $nombre;
        $this->email = $email;
        $this->token = $token;
    }

    public function enviarConfirmacion(){
        $mail = new PHPMailer();

        //Configurar SMTP  
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '35f3de8bcb1a53';
        $mail->Password = '339229a16a7481';

        //Estructura del Email
        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('jorge@maya.com','MAYA IMPORTACIONES');
        $mail->Subject = 'CONFIRMA TU CUENTA';

        //Habilitar HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        //Contenido
        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre  . " Has creado tu cuenta en AppSalon, Solo debes confirmarla en el siguiente enlace:</strong></p>";
        
        $contenido .= "<p><a href='http://localhost:3000/confirmar-cuenta?token=". $this->token ."'>Confirma Aqui</a></p>";
        $contenido .= "</html>";   
        $mail->Body = $contenido;
        $mail->send();
    }

    public function enviarInstrucciones(){
       
       $mail = new PHPMailer();

       //Configurar SMTP
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '35f3de8bcb1a53';
        $mail->Password = '339229a16a7481';

        //Estructura del Correo
        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('correo2@correo.com','Appsalon');
        $mail->Subject = 'Reestablece tu password';

        //Habilitar HTML
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        //Contenido
        $contenido = "<html>";
        $contenido .= "<p><strong>Hola ". $this->nombre ." Has solicitado reestablecer tu password, Sigue el siguiente enlace para hacerlo.</strong></p>";
        $contenido .= "<p><a href='http://localhost:3000/recuperar?token=". $this->token  ."'>Reestablece Tu password</a></p>";
        $contenido .= "<p>Si no has solicitado este cambio, Ignora este mensaje.</p>";
        $contenido .= "</html>";
        $mail->Body = $contenido;
        $mail->send();

    }
}