<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

function verErrores(){
    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(E_ALL);
}

//Esta autenticado?
function isAuth() : void{
    session_start();
    if(!isset($_SESSION['login'])){
        header('Location: /');
    }
}

//Es Admin?
function isAdmin():void{
    if(!isset($_SESSION['admin'])){
        header('Location: /');
    }
}


function esUltimo(string $actual,string $proximo):bool{
    if($actual !== $proximo){ //Si el valor actual es diferente el proximo significa que es el ultimo
        return true;
    }
    return false;
}