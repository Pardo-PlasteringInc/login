<?php
    function registro() {
        require_once('recursos/conecion.php');
        $errores = duplicacion($con);

    if(!empty($errores )){
        return $errores;
    }


        $nombre = limpiar($_POST['nombre']);
        $apellido = limpiar($_POST['apellido']);
        $usuario = limpiar($_POST['usuario']);
        $email = limpiar($_POST['email']);
        $clave = limpiar($_POST['clave']);

        $dec = $con -> prepare("INSERT INTO perfiles (nombre, apellido, usuario, email, clave) VALUES (?,?,?,?,?)");
        $dec -> bind_param("sssss", $nombre, $apellido, $usuario, $email, password_hash($clave, PASSWORD_DEFAULT));
        $dec -> execute();
        $resultado = $dec -> affected_rows;
        $dec -> free_result();
        $dec -> close();
        $con -> close();

        if($resultado == 1){
            $_SESSION['usuario'] = $usuario;
            header('Location: index.php');

        }
        else{
            $errores[] = 'Oops, no se creo tu perfil, por favor intentalo mas tarde';
        }

        return $errores;

    }



    function duplicacion($con){
        $errores = [];

        $usuario = limpiar($_POST['usuario']);
        $email = limpiar($_POST['email']);
    
        $dec = $con -> prepare("SELECT usuario, email FROM perfiles WHERE usuario = ? OR email = ?");
        $dec -> bind_param("ss", $usuario, $email);
        $dec -> execute();
        $resultado = $dec -> get_result();
        $cantidad = mysqli_num_rows($resultado);
        $linea = $resultado -> fetch_assoc();
        $dec -> free_result();
        $dec -> close();

        if($cantidad > 0){
            if($_POST['usuario'] == $linea['usuario']){
                $errores[] = 'El NOMBRE DE USUARIO no esta disponible.';
            }
            if($_POST['email'] == $linea['email']){
                $errores[] = 'El CORREO ELECTRONICO ya esta siendo usado por alguien mas.';
            }
        }
        
        return $errores;
    }

    function login(){
        require_once('recursos/conecion.php');
        $errores = [];

        $usuario = limpiar($_POST['usuarioOEmail']);
        $clave = limpiar($_POST['clave']);

        $dec = $con -> prepare("SELECT usuario, clave, intento, id, tiempo FROM perfiles WHERE usuario = ? OR email = ?");
        $dec -> bind_param("ss", $usuario, $usuario);
        $dec -> execute();
        $resultado = $dec -> get_result();
        $cantidad = mysqli_num_rows($resultado);
        $linea = $resultado -> fetch_assoc();
        $dec -> free_result();
        $dec -> close();
        // $con -> close();

        if($cantidad == 1){

            $errores = fuerzaBruta($con, $linea['intento'], $linea['id'], $linea['tiempo']);
            if(!empty($errores)){return $errores;}

            if(password_verify($clave, $linea['clave'])){
                $_SESSION['usuario'] = $linea['usuario'];
                header('Location: index.php');
            }
            else{
                $errores[] = 'The combination of (USER NAME or EMAIL) and PASSWORD are not valid';
                }
        
        }
        else{
        $errores[] = 'The combination of (USER NAME or EMAIL) and PASSWORD are not valid';
        }
    
    return $errores;
}

    function fuerzaBruta($con, $intento, $id, $tiempo){
        $errores = [];
        $intento = $intento + 1;
        
        $dec = $con -> prepare("UPDATE perfiles SET intento = ?  WHERE id = ?");
        $dec -> bind_param("ii", $intento, $id);
        $dec -> execute();
        $dec -> close();

        if($intento == 5){
            $ahora = date('Y-m-d H:i:s');
            $dec = $con -> prepare("UPDATE perfiles SET tiempo = ?  WHERE id = ?");
            $dec -> bind_param("si", $ahora, $id);
            $dec -> execute();
            $dec -> close();
            $errores[] = 'This account has been blocked for the next 15 minutes.';
        }
        elseif($intento > 5){
            $espera = strtotime(date('Y-m-d H:i:s')) - strtotime($tiempo);
            $minutos = ceil((900-$espera)/60);
            if($espera < 900){
                $errores[] = 'This account has been blocked by the next '.$minutos.' minutes.';
            }
            else{
                $intento = 1;
                $tiempo = NULL;
                $dec = $con -> prepare("UPDATE perfiles SET intento = ?, tiempo = ?  WHERE id = ?");
                $dec -> bind_param("isi", $intento, $tiempo, $id);
                $dec -> execute();
                $dec -> close();
            }
        }

        return $errores;
    }

    function limpiar($datos){
        $datos = trim($datos);
        $datos = stripslashes($datos);
        $datos = htmlspecialchars($datos);
        return $datos;
    }

    function mostrarErrores($errores){
        $resultado = '<div class="alert alert-dandger errores"><ul>';
        foreach($errores as $error){
            $resultado .= '<li>' . htmlspecialchars($error) . '</li>';
        }
        $resultado .= '</ul></div>';
        return $resultado;
    }

    function ficha_csrf(){
        $ficha = bin2hex(random_bytes(32));
        return $_SESSION['ficha'] =  $ficha;
    }

    function validar_ficha($ficha){
        if(isset($_SESSION['ficha']) && hash_equals($_SESSION['ficha'], $ficha)){
            unset($_SESSION['ficha']);
            return true;
        }
        return false;
    }

    function validar($campos){
        $errores =[];
        foreach($campos as $nombre => $mostrar){
            if(!isset($_POST[$nombre]) || $_POST[$nombre] == NULL){
                $errores[] = $mostrar . ' es un campo requerido...';
            }
            else{
                $valides = campos();
                foreach($valides as $campo => $opcion){
                    if($nombre == $campo){
                        if(!preg_match($opcion['patron'], $_POST[$nombre])){
                            $errores[] = $opcion['error'];
                        }
                    }
                }
            }
        }
    
    return $errores;
    } 
    function campo($nombre){
        echo $_POST[$nombre] ?? '';
    }
    
    function campos(){
        $validacion = [
            'nombre' => [
                'patron' => "/^[a-z\s]{2,50}$/i",
                'error' => 'NOMBRES solo puede usar letras y espacios. Ademas debe tener de 2 a 50 caracteres'
            ],
            'apellido' => [
                'patron' => "/^[a-z\s]{2,50}$/i",
                'error' => 'APELLIDO solo puede usar letras y espacios. Ademas debe tener de 2 a 50 caracteres'
            ],
            'usuario' => [
                'patron' => "/^[a-z\][\w]{2,30}$/i",
                'error' => 'NOMBRE DE USUARIO debe tener por lo menos 3 caracteres. Debe de monenzar con una letra y solo puede usar letras, mumeros y guion bajo'
            ],
            'email' => [
                'patron' => '/^[A-z0-9\\._-]+@[A-z0-9][A-z0-9-]*(\\.[A-z0-9_-]+)*\\.([A-z]{2,6})$/i',
                'error' => 'EL CORREO ELECTRONICO debe de ser en un formato valido'
            ],
            'clave' => [
                'patron' => "/(?=^[\w\!@#\$%\^&\*\?]{8,30}$)(?=(.*\d){2,})(?=(.*[a-z]){2,})(?=(.*[\!@#\$%\^&\*\?_]){2,})^.*/",
                'error' => 'porfavor entre un password valido. El password debe tener por lo menos 2 letras mayusculas, 2 letras minusculas, 2 numeros y 2 simbulos'
            ],
            'usuarioOEmail' => [
                'patron' => "/(?=^[a-z]+[\w@\.]{2,50}$)/i",
                'error' => 'porfavor use un NOMBRE DE USUARIO o CORREO ELECTRONICO valido. '
            ]
        ];
    return $validacion;   
    }

    function comparadorDeClaves($clave, $reclave){
        $errores = [];
        if($clave !== $reclave){
            $errores[] = 'Los passwords proveidos no son iguales! ';
        }
        return $errores;
    }
?>