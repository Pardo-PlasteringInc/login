<nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
 
            <!-- logo y boton de expander y colopsar los enlaces -->
            <div class="navbar-header">
               <button type="buton" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#enlaces">
                   <span class="icon-bar"></span>
                   <span class="icon-bar"></span>
                   <span class="icon-bar"></span>
               </button>
               <a href="index.php" class="navbar-brand">Mi Projecto</a>
            </div><!-- /logo y boton de expander y colopsar los enlaces -->

            <!-- Enlaces de navegacion -->
             <div class="collapse navbar-collapse navbar-right" id="enlaces">
                 <ul class="nav navbar-nav">
                     <li><a href="index.php">Principio</a></li>
                     <li><a href="#">Tabla</a></li>
                     <li><a href="contacto.php">Contacto</a></li>
                     <li><a href="http://stucco.tonohost.com/?i=1#nosotros">Galeria</a></li>
                     <?php 
                        if(isset($_SESSION['usuario'])){
                            echo '                     
                               <li><a href="usuario" class="dropdown-toggle" data-toggle="dropdown">
                               '.$_SESSION['usuario'].' <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                   <li><a href="\conection\tabla.php">Mi Tabla</a></li>
                                   <li><a href="#">Mi Cuenta</a></li>
                                   <li><a href="#">Mis Preferencias</a></li>
                                   <li class="divider"></li>
                                   <li><a href="logout.php">Logout</a></li>
                               </ul>
                            </li';
                        }else{
                            echo '
                            <li><a href="login.php">Login</a></li>
                            <li><a href="registro.php">Registro</a></li>';
                        }
                     ?>
                 </ul>
             </div><!-- /Enlaces de navegacion -->


        </div>
    </nav>
