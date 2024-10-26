<?php
header("Content-Type: application/json");

include_once './controllers/UsersController.php';

$controller = new UsersController();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $controller->getUsers();
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        if ($data) {
            $controller->createUser($data);
        } else {
            echo json_encode(["message" => "Invalid input"], JSON_PRETTY_PRINT);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        if ($data && isset($data['NOMORDOKKEGIATAN'])) {
            $controller->updateUser($data);
        } else {
            echo json_encode(["message" => "Invalid input or missing ID"], JSON_PRETTY_PRINT);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['id'])) {
            $controller->deleteUser($data['NOMORDOKKEGIATAN']);
        } else {
            echo json_encode(["message" => "Missing required field: id"], JSON_PRETTY_PRINT);
        }
        break;

    default:
        echo json_encode(["message" => "Invalid request method"], JSON_PRETTY_PRINT);
        break;
}
?>
