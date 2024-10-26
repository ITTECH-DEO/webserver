<?php
include_once './models/User.php';

class UsersController {
    public function getUsers() {
        $user = new User();
        $users = $user->fetchAll();
        echo json_encode($users);
    }

    public function createUser($data) {
        if (!empty($data['ID']) && !empty($data['NAME']) && !empty($data['EMAIL'])) {
            $user = new User();
            $result = $user->create($data);
            echo json_encode($result);
        } else {
            echo json_encode(["message" => "Invalid input"]);
        }
    }

    public function updateUser($data) {
        if (!empty($data['ID']) && !empty($data['NAME']) && !empty($data['EMAIL'])) {
            $user = new User();
            $result = $user->update($data);
            echo json_encode($result);
        } else {
            echo json_encode(["message" => "Invalid input"]);
        }
    }

    public function deleteUser($id) {
    // Pastikan ID tidak kosong
    if (!empty($id)) {
        $user = new User();
        $response = $user->delete($id);
        echo json_encode($response);
    } else {
        echo json_encode(["message" => "ID is required for deletion"]);
    }
}
}
?>
