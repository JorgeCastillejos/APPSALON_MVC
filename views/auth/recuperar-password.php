<h1 class="nombre-pagina">Recupera tu Password</h1>
<p class="descripcion-pagina">Coloca tu nuevo password a continuacion</p>

<?php include_once __DIR__ . '/../templates/alertas.php'; ?>

<?php if($error) return; ?> <!--El return para la ejecucion del codigo que este abajo de el-->

<form method="POST" class="formulario">
    <div class="campo">
        <label for="password">Password</label>
        <input type="password" id="password" placeholder="Tu nuevo password" name="password">
    </div>
    <input type="submit" value="Guardar nuevo password" class="boton">
</form>




<div class="acciones">
    <p>¿Ya tienes cuenta? Inicia Sesion</p>
    <p>¿Aun no tienes cuenta? Obtener una</p>
</div>