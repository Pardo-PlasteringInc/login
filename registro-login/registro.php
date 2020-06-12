<?php
session_start();
require_once('funciones/funciones.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ficha'])) {

    if (!empty($_POST['miel'])) {
        return header('Location: index.php');
    }

    $campos = [
        'nombre' => 'Nombre',
        'apellido' => 'Apellido',
        'usuario' => 'Nombre de Usuario',
        'email' => 'Correo Electronico',
        'clave' => 'Password',
        'reclave' => 'Re-Password',
        'terminos' => 'Terminos y Condiciones'
    ];

    $errores = validar($campos);
    $errores = array_merge($errores, comparadorDeClaves($_POST['clave'], $_POST['reclave']));

    if (empty($errores)) {
        $errores = registro();
    }
}

$titulo = "Mi Projecto | Registro";
require_once('parciales/arriva.php');
require_once('parciales/nav.php');

?>


<!-- Contenedor principal (cuerpo de la pagina) -->

<div class="container" id="pagina-registro">
    <div class="row">
        <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
            <h1 class="titulo-de-pagina"> Pagina de Registro</h1>

            <?php if (!empty($errores)) {
                echo mostrarErrores($errores);
            }  ?>
            <!-- Formulario de registro -->
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="formulario-registro">
                <input type="hidden" name="ficha" value="<?php echo ficha_csrf(); ?>">
                <input type="hidden" name="miel" value="">
                <h2>Registrate <small>para unirte</small></h2>
                <hr>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <input type="text" class="form-control input-lg" name="nombre"  value="<?php campo('nombre') ?>" placeholder="Nombre" tabindex="1" autofocus>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <input type="text" class="form-control input-lg" name="apellido"   value="<?php campo('apellido') ?>" placeholder="Apellido" tabindex="2">

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <input type="text" class="form-control input-lg" name="usuario"  value="<?php campo('usuario') ?>" placeholder="Nombre de usuario" tabindex="3" autofocus>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <input type="text" class="form-control input-lg" name="email"  value="<?php campo('email') ?>" placeholder="Email" tabindex="4">

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <input type="password" class="form-control input-lg" name="clave"  placeholder="Password" tabindex="5" autofocus>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <input type="password" class="form-control input-lg" name="reclave"   placeholder="Re-Password" tabindex="6">

                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-sm-3">
                            <label class="btn btn-primary btn-lg btn-block">
                                <input type="checkbox" name="terminos" tabindex="7" <?php if (isset($_POST['terminos'])) { echo "checked = 'checked'";} ?>>
                                Acepto
                            </label>
                        </div>
                        <div class="col-sm-9">
                            By registering I am accepting the agreed terms and conditions of this page including the use of Cookies.
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-success btn-lg btn-block" name="registroBtn" tabindex="8">Registrar</button>
                        </div>
                        <div class="col-sm-6">
                            <a href="index.php" class="btn btn-danger btn-lg btn-block" tabindex="9">Cancelar</a>
                        </div>
                    </div>


                </div>

            </form><!-- /Formulario de registro -->
            <hr>




        </div>
    </div>
</div>

<?php
require_once('parciales/abajo.php');
require_once('parciales/footer.php');
?>