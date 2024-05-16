<?php include ('header.php') ?>

<?php

$MensajeEstado = "";

if ($_POST) {
    $Servidor = "sql10.freesqldatabase.com";
    $Usuario = "sql10655035";
    $Password = "nVRsxZy5Qs";

    $vNombre = $_POST["inNombre"];
    $vApellido = $_POST["inApellido"];
    $vCorreo = $_POST["inCorreo"];
    $vTelefono = $_POST["inTelefono"];
    $vDomicilio = $_POST["inDomicilio"];
    $vMensaje = $_POST["inMensaje"];

    try {
        $conexion = new PDO("mysql:host=$Servidor;dbname=sql10655035;charset=UTF8", $Usuario, $Password);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "insert into `MENSAJE_SOLICITUD` (`nombre`,`apellido`,`correo`,`telefono`,`domicilio`,`mensaje`,`fecha_envio`,`leido`)
                VALUES ('$vNombre', '$vApellido', '$vCorreo', '$vTelefono', '$vDomicilio', '$vMensaje', now(), 0);";

        $conexion->exec($sql);

        $MensajeEstado = '<h2 class="formTitle" id="MessageGood">SU MENSAJE SE HA ENVIADO CON EXITO!</h2>';
    } catch (PDOException $error) {
        $MensajeEstado = '<h2 class="formTitle" id="MessageBad">ERROR AL ENVIAR, VERIFIQUE LOS DATOS...</h2>';
    }
}

?>

    <main>
        <form action="contacto.php" method="post" class="formContacto">
        <h2 class="formTitle">Envíenos un mensaje para contactarnos:</h2>
        <?php echo $MensajeEstado ?>
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" name="inNombre" id="nombre">
            </div>
            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido</label>
                <input type="text" class="form-control" name="inApellido" id="apellido">
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="tel" class="form-control" name="inTelefono" id="telefono">
            </div>
            <div class="mb-3">
                <label for="correo" class="form-label">Email</label>
                <input type="email" class="form-control" name="inCorreo" id="correo">
            </div>
            <div class="mb-3">
                <label for="domicilio" class="form-label">Domicilio</label>
                <input type="text" class="form-control" name="inDomicilio" id="domicilio">
            </div>
            <div class="mb-3">
                <label for="mensaje" class="form-label">Mensaje</label><br>
                <textarea name="inMensaje" id="mensaje" cols="30" rows="5" placeholder="Escriba su mensaje aquí"></textarea>
            </div>
            <div class="formButtons">
                <button type="reset" class="btn btn-danger">Borrar</button>
                <button type="submit" class="btn btn-primary">Enviar</button>
            </div>
        </form>
        <!-- <form action="contacto.php" method="post">
            Inserte nombre:
            <br />
            <input type="text" name="inNombre" id="">
            <br />
            Inserte apellido:
            <br />
            <input type="text" name="inApellido" id="">
            <br />
            Ingrese un numero de telefono:
            <br />
            <input type="text" name="inTelefono" id="">
            <br />
            Ingrese su domicilio:
            <br />
            <input type="text" name="inDomicilio" id="">
            <br />
            Escribanos un mensaje:
            <br />
            <input type="text" name="inMensaje" id="">
            <br />
            <input type="submit" value="ENVIAR">
            <br />
            <br />
        </form> -->
    </main>

    <?php include ('footer.php') ?>