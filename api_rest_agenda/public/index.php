<?php
header("Content-Type: application/json; charset=UTF-8");

include_once "../config/database.php";
include_once "../models/Contacto.php";

$database = new Database();
$db = $database->getConnection();

$contacto = new Contacto($db);

$request_method = $_SERVER["REQUEST_METHOD"];

switch ($request_method) {
    case 'GET':
        $stmt = $contacto->read();
        $contacts_arr = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $contact_item = array(
                'id' => $id,
                'nombre' => $nombre,
                'telefono' => $telefono,
                'email' => $email,
                'direccion' => $direccion
            );
            array_push($contacts_arr, $contact_item);
        }
        echo json_encode($contacts_arr);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));

        if (
            !empty($data->nombre) &&
            !empty($data->telefono) &&
            !empty($data->email)
        ) {
            $contacto->nombre = $data->nombre;
            $contacto->telefono = $data->telefono;
            $contacto->email = $data->email;
            $contacto->direccion = $data->direccion;

            if ($contacto->create()) {
                echo json_encode(array("message" => "Contacto creado."));
            } else {
                echo json_encode(array("message" => "Error al crear el contacto."));
            }
        } else {
            echo json_encode(array("message" => "Faltan campos"));
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));

        if (
            !empty($data->id) &&
            !empty($data->nombre) &&
            !empty($data->telefono) &&
            !empty($data->email)
        ) {
            $contacto->id = $data->id;
            $contacto->nombre = $data->nombre;
            $contacto->telefono = $data->telefono;
            $contacto->email = $data->email;
            $contacto->direccion = $data->direccion;

            if ($contacto->update()) {
                echo json_encode(array("message" => "Contacto actualizado."));
            } else {
                echo json_encode(array("message" => "Error al actualizar el contacto."));
            }
        } else {
            echo json_encode(array("message" => "Faltan campos"));
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->id)) {
            $contacto->id = $data->id;

            if ($contacto->delete()) {
                echo json_encode(array("message" => "Contacto eliminado."));
            } else {
                echo json_encode(array("message" => "Error al eliminar el contacto."));
            }
        } else {
            echo json_encode(array("message" => "Faltan campos"));
        }
        break;

    default:
        echo json_encode(array("message" => "Método no permitido"));
        break;
}
?>
