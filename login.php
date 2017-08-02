<?php

header('Content-type: application/x-www-form-urlencoded');

$json = array(
    'success' => false,
    'result'  => 0
);

if(isset($_POST['correo'], $_POST['password'])){

    $correo = $_POST['correo'];
    $password = $_POST['password'];

    $json['success'] = true;
    $json['result'] = 1;

    validacion($correo, $password, $json);

} else {

    echo json_encode($json);

}


function validacion($correo, $password, $json){

    // Conectamos
    $pdo = new PDO(
        'mysql:host=127.0.0.1;dbname=paracrecer',
        'root',
        '',
        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
    );

    // Hacemos el query
    $query = $pdo->prepare("SELECT `CORREO`, `PASSWORD` FROM `asesor` WHERE CORREO=:correo");

    // Llenamos campos y ejecutamos
    $query->execute([
        'correo' => $correo,
    ]);

    // Si trae un resultado
    if ($query->rowCount() > 0){

        // Sólo hay un usuario con un correo
        if($query->rowCount() == 1){

            $check = $query->fetch(PDO::FETCH_ASSOC);

            $row_correo = $check['CORREO'];
            $row_pass = $check['PASSWORD'];

            // Comprobamos el correo iguales
            if($correo == $row_correo){

                if (password_verify($password, $row_pass)) {

                    //echo "¡ÉXITO!";
                    echo json_encode($json);

                } else {

                    echo "1Su correo o contraseña no coinciden";
                }

            } else {

                echo "2Su correo o contraseña no coinciden";

            }

        } else {

            echo "Existen más de 2 coincidencias contacte a su administrador";
        }

    } else {

        echo "No se encontraron coincidencias";
    }
}
