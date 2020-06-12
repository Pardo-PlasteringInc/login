<?php
   session_start();
   $titulo = "Mi Projecto | Principio";
require_once('parciales/arriva.php');
require_once('parciales/nav.php');
?>


    <!-- Contenedor principal (cuerpo de la pagina) -->
    <?php
           if(isset($_SESSION['usuario'])){
              echo ' 
                 <p>Binenvenido '.$_SESSION['usuario'].'</p>            
           ';
          }else{
              echo '
              <p>Por favor registrate o login</p>            
              ';
          }
        ?>
        <br>
        <br>
        <br>
        <br>
    <div class="container" id="pagina-principal">
    <h1 class="titulo">
                <span class="texto-purple-dark">Pardo</span>
                <span class="texto-naranja">Plastering</span>
                <span class="texto-naranja">Inc.</span>
            </h1>
    </div><!-- /Contenedor principal (cuerpo de la pagina) -->
<?php
require_once('parciales/abajo.php');
require_once('parciales/footer.php');
?>

