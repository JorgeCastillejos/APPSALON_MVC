<h1 class="nombre-pagina">Panel de Administración</h1>

<?php include_once __DIR__ . '/../templates/barra.php'; ?>

<h2>Buscar Citas</h2>
<div class="busqueda">
    <form class="formulario">
        <div class="campo">
            <label for="fecha">Fecha</label>
            <input type="date" id="fecha" name="fecha" value="<?php echo $fecha ?>">
        </div>
    </form>
</div>

<!--Estamos pasando un arreglo, utilizamos la funcion count -->
<?php if(count($citas) === 0): ?>
    <h3>No hay Citas Disponibles en esta Fecha</h3>
    <?php endif; ?>

<div class="citas-admin">
    <ul class="citas">
        <?php 
        $idCita = 0;
        foreach($citas as $key => $cita): 
        
        ?>
            <?php if($idCita !== $cita->id): 
                $total = 0;
                ?>
            <li>
                <p><span>Id:</span> <?php echo $cita->id; ?></p>
                <p><span>Hora:</span> <?php echo $cita->hora; ?></p>
                <p><span>Cliente:</span> <?php echo $cita->cliente; ?></p>
                <p><span>Email:</span> <?php echo $cita->email; ?></p>
                <p><span>Teléfono:</span> <?php echo $cita->telefono; ?></p>
            </li>
                <h3>Servicios</h3>
            <?php 
                $idCita = $cita->id;
            endif; ?>
            <p><?php echo $cita->servicio . " " . $cita->precio ?></p>

            <!--Calcular el total-->
            <?php 
                $actual = $cita->id;
                $proximo = $citas[$key + 1]->id ?? 0;
                $total += $cita->precio;
                if(esUltimo($actual,$proximo)){ ?>
                    <p class="total"> <span>Total:</span> $<?php echo $total; ?></p>

                    <form action="/api/eliminar" method="POST">
                        <input type="hidden" name="id" value="<?php echo $cita->id ?>">
                        <input type="submit" class="boton-eliminar" value="Eliminar">
                    </form>

                <?php }
            ?>    
        <?php endforeach; ?>

    </ul>
</div>

<?php $script = "<script src='build/js/buscador.js'></script>"; ?>