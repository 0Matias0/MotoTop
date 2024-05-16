<?php include ('header.php'); ?>

    <main>
        <section class="hero">
            <div>
                <span class="maquinaEscribir">MOTO - TOP</span>
                <p>Donde la calidad y la velocidad se encuentran.</p>
                <a href="./acercade.php"><button>Conocenos</button></a>
            </div>
            <div>
                <img src="./assets/img/hero.svg" alt="">
            </div>
        </section>
    
        <section id="promociones">
            <h2 class="title-section">Productos en OFERTA</h1>
                <article class="card-section justify-content-center">
                    <p>PROMOCIONES</p>
                    <p></p><br/>
                    <p>Cuando eliges nuestros repuestos de motos, estás eligiendo confiabilidad y experiencia. Respaldamos cada compra con nuestro compromiso de satisfacción del cliente.</p>

                    <?php
                        /* PRODUCTOS EN PROMO - PHP */
                        $Servidor = "sql10.freesqldatabase.com";
                        $Usuario = "sql10655035";
                        $Password = "nVRsxZy5Qs";

                        try
                        {
                            $conexion = new PDO("mysql:host=$Servidor;dbname=sql10655035;charset=UTF8",$Usuario,$Password);
                            $conexion -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

                            $sql = "SELECT p.ID,p.nombre,img_data,p.precio,p.oferta_descuento,p.oferta_paga,p.oferta_lleva,p.oferta_inicio,p.oferta_fin,r.nombre AS prov_nombre
                            FROM PRODUCTO p INNER JOIN PROVEEDOR r ON (p.proveedor_ID=r.id) WHERE (oferta_descuento IS NOT NULL OR oferta_lleva IS NOT NULL);";

                            $sentencia = $conexion -> prepare($sql);
                            $sentencia -> execute();

                            $resultado = $sentencia -> fetchAll();

                            foreach($resultado as $product)
                            {

                                $OGprecio = "<br/>";
                                if ( $product['oferta_descuento'] != NULL )
                                {
                                    $OGprecio = '<p class="card-text card_OGprice">Precio original: $<s>'.$product['precio'].'</s></p>';
                                    $img_promo = '<img src="./assets/img/fire_circle.gif" class="fire_circle" alt=""><div class="circle_text">'.$product['oferta_descuento'].'% OFF!</div>';
                                }
                                else
                                {
                                    $OGprecio = '<p class="card-text card_OGprice">¡Pagas '.$product['oferta_paga'].' y llevas '.$product['oferta_lleva'].'!</p>';
                                    $img_promo = '<img src="./assets/img/fire_rectangle.gif" class="fire_rectan" alt="">
                                                  <div class="rectan_text">'.$product['oferta_paga'].'X'.$product['oferta_lleva'].'</div>';
                                }

                                echo '
                                <div class="card" id="productos">
                                    '.$img_promo.'
                                    <img src="data:image/png;base64,'.base64_encode($product['img_data']).'" class="card-img-top" alt="'.$product['nombre'].'">
                                    <div class="card-body">
                                        <div class="card_producto">                                            
                                            <div>
                                                <p class="card-title" card-text card_description">'.$product['nombre'].'</p>
                                                <p class="card-marca">'.$product['prov_nombre'].'</p>
                                            </div>
                                            <i class="fa-solid fa-heart"></i>
                                        </div>

                                        <div class="card_footer">
                                            <div>
                                                <p>Hasta:</p>
                                                <p>'.$product['oferta_fin'].'</p>
                                            </div>
                                            <p>'.$OGprecio.'</p>                                                                               
                                            <div class="card_buy">                                                
                                                <p class="card-precio">$'.$product['precio']-(($product['precio']/100)*$product['oferta_descuento']).'</p>
                                                <a href="#" class="btn card_button"><i class="fa-solid fa-cart-shopping"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>';
                            }
                        }
                        catch(PDOException $error)
                        {
                            echo "La Conexion no pudo ser| error :".$error;
                        }
                    ?>

                </article>
        </section>
    
        <section class="galeria-section">
            <h2 class="title-section">Galería</h2>
            <div class="galeria">
                <div id="carouselExampleDark" class="carousel carousel-dark slide">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="1" aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="2" aria-label="Slide 3"></button>
                    </div>
                    <div class="carousel-inner">
                        <div class="carousel-item active" data-bs-interval="10000">
                            <img src="./assets/img/personal.png" class="" alt="...">
                        </div>
                        <div class="carousel-item" data-bs-interval="2000">
                            <img src="./assets/img/personal.png" class="d-block w-100" alt="...">
                        </div>
                        <div class="carousel-item">
                            <img src="./assets/img/personal.png" class="d-block w-100" alt="...">
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </section>
    
        <section class="newsletter">
            <p>Suscribete a nuestro newsletter</p>
            <div>
                <input type="mail" placeholder="Ingrese su email">
                <button type="submit" class="newsletter_button">Suscribirse</button>
            </div>
        </section>
    </main>

<?php include ('footer.php') ?>
