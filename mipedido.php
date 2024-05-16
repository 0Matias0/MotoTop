<?php include('header.php') ?>

<main>
    <h2>Pedidos:</h2>
    <br />
    <?php
    $MensajeEstado = "";

    if (!$_POST) //------------------------------------------FORMULARIO-----------------------------------------
    {
        echo '
                <form action="mipedido.php" method="post" class="formPedido">
                    <h2 class="formTitle">Ingrese sus datos:</h2>
                    <div class="mb-3">
                        <label for="DNI" class="form-label">DNI: </label>
                        <input type="number" class="form-control" name="inDNI" id="DNI">
                    </div>
                    </br>
                    <div class="mb-3">
                        <label for="pass" class="form-label">Contraseña: </label>
                        <input type="password" class="form-control" name="inPass" id="pass">
                    </div>
                    <div class="formButtons">
                        <button type="reset" class="btn btn-danger">Borrar</button>
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </div>
                </form>';
    } else //------------------------------------------MOSTRADOR PEDIDOS-----------------------------------------
    {
        $Servidor = "sql10.freesqldatabase.com";
        $Usuario = "sql10655035";
        $Password = "nVRsxZy5Qs";

        $ValidAccount = false;
        $ErrorReintente = 'Error en los datos, intente de nuevo...</br>
                                  <a href="mipedido.php"><button> > Volver < </button></a>';
        $sqlReturned = "";
        $ClienteDNI = "";
        $ClienteNombre = "";
        $pedidoEstado = "";
        $tabla = "";

        try //----------------------------------------------- CONSULTAR DNI Y PASS SI COINCIDEN ----------------
        {
            $conexion = new PDO("mysql:host=$Servidor;dbname=sql10655035;charset=UTF8", $Usuario, $Password);
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = 'SELECT pass, CONCAT(nombre," ",apellido) AS nombre FROM CLIENTE WHERE DNI=:dni;';

            $sentencia = $conexion->prepare($sql);
            $sentencia->bindParam(':dni', $_POST['inDNI'], PDO::PARAM_INT);
            $sentencia->execute();

            $resultado = $sentencia->fetchAll();

            if ($sentencia->rowCount() != 0) {
                $sqlReturned = $resultado[0];

                if (password_verify($_POST["inPass"], $sqlReturned["pass"])) {
                    $ValidAccount = true;
                    $ClienteDNI = $_POST['inDNI'];
                    $ClienteNombre = $sqlReturned["nombre"];
                } else {
                    $ValidAccount = false;
                }
            } else {
                $ValidAccount = false;
            }
        } catch (PDOException $error) {
        }

        if ($ValidAccount) {
            try //----------------------------------------------- CONSULTAR PEDIDOS ------------------------------
            {
                $conexion = new PDO("mysql:host=$Servidor;dbname=sql10655035;charset=UTF8", $Usuario, $Password);
                $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $sql = 'SELECT CONCAT(v.nombre," ",v.apellido) AS vendedor, ID, precio_total, fecha_generado, fecha_envio, fecha_entrega, estado, c.direccion_entrega
                        FROM PEDIDO 
                        INNER JOIN CLIENTE AS c ON (cliente_DNI=DNI)
                        INNER JOIN VENDEDOR AS v ON (vendedor_DNI=v.DNI) 
                        WHERE c.DNI=:dni;';

                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':dni', $_POST['inDNI'], PDO::PARAM_INT);
                $sentencia->execute();

                $resultado = $sentencia->fetchAll();

                echo '<h2>CLIENTE: ' . $ClienteNombre . '</h2>';
                echo '<h2>N° DNI: ' . $ClienteDNI . '</h2>';

                foreach ($resultado as $pedido) {
                    if ($pedido["estado"] == 0) {
                        $pedidoEstado = "En preparacion...";
                    } else
                            if ($pedido["estado"] == 1) {
                        $pedidoEstado = "En camino.";
                    } else {
                        $pedidoEstado = "Recibido.";
                    }

                    //Encabezado
                    $tabla = $tabla .
                        '<div class="pedido_div">
                                        <div class="head_pedidido_div">
                                            <p><u>Pedido N° ' . $pedido["ID"] . '</u></p>
                                            <p><u>Vendedor:</u> ' . $pedido["vendedor"] . '.</p>
                                            <p><u>Hecho el:</u> ' . $pedido["fecha_generado"] . '.</p>
                                            <p><u>Precio total:</u> $' . $pedido["precio_total"] . '</p>
                                            <p><u>Se entrega en:</u> ' . $pedido["direccion_entrega"] . '.</p>
                                            <p><u>Enviado el:</u> ' . $pedido["fecha_envio"] . '.</p>
                                            <p><u>Llega el:</u> ' . $pedido["fecha_entrega"] . '.</p>
                                            <p><u>Estado:</u> ' . $pedidoEstado . '</p>
                                        </div>
                                        <div>
                                            <details class="detail_lista">
                                            <summary> -Lista del pedido </summary>
                                                <table class="tabla_lista">
                                                <thead>
                                                    <tr class="tabla_lista_head">
                                                        <th>Codigo</th>
                                                        <th>Cantidad</th>
                                                        <th>Producto</th>
                                                        <th>Precio c/u</th>
                                                        <th>Descuento</th>
                                                        <th>Oferta X cantidad</th>
                                                        <th>PRECIO TOTAL</th>
                                                    </tr>
                                                </thead>
                                                    <tbody class="tabla_lista_body">';

                    $sql = 'SELECT barcode,cantidad,nombre,precio,oferta_descuento,oferta_paga,oferta_lleva,total
                                                                FROM LISTA_PEDIDO WHERE pedido_ID=' . $pedido["ID"] . ';';
                    $sentencia = $conexion->prepare($sql);
                    $sentencia->execute();

                    $resultado2 = $sentencia->fetchAll();

                    foreach ($resultado2 as $lista) //Contenido de la tabla
                    {
                        $tabla = $tabla . '<tr>'; //inicio fila
                        $tabla = $tabla . '<td>' . $lista["barcode"] . '</td>';
                        $tabla = $tabla . '<td>' . $lista["cantidad"] . '</td>';
                        $tabla = $tabla . '<td>' . $lista["nombre"] . '</td>';
                        $tabla = $tabla . '<td>' . $lista["precio"] . '</td>';
                        $tabla = $tabla . '<td>-%' . $lista["oferta_descuento"] . '</td>';
                        $tabla = $tabla . '<td>' . $lista["oferta_paga"] . 'X' . $lista["oferta_lleva"] . '</td>';
                        $tabla = $tabla . '<td>' . $lista["total"] . '</td>';
                        $tabla = $tabla . '</tr>'; //fin fila
                    }

                    $tabla = $tabla . '       </tbody>
                                                    </table>
                                            </details>
                                        </div>
                                    </div>';
                }

                echo $tabla . '<br>';
                $ErrorReintente = '';
            } catch (PDOException $error) {
            }
        }

        echo $ErrorReintente;
    }

    ?>
</main>

<?php include('footer.php') ?>