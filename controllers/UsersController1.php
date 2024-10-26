<?php
include_once './models/User.php';

class UsersController {
    private function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        echo json_encode($data);
    }

    public function getUsers() {
        $user = new User();
        $users = $user->fetchAll();
        $this->jsonResponse($users);
    }

    public function createUser($data) {
        $user = new User();
    
        // Inserting data into TABLE KEGIATAN
        if (!empty($data['NO_KEGIATAN']) && !empty($data['TANGGALKEGIATAN']) && !empty($data['NPWP']) && !empty($data['NIB'])) {
            $result = $user->create($data); // Save to KEGIATAN
            $this->jsonResponse($result);
            return;
        }
    
        // Inserting data into TABLE DOKUMENKEGIATAN
        if (!empty($data['NOMORDOKKEGIATAN']) && !empty($data['TANGGALKEGIATAN']) && !empty($data['NAMAENTITAS']) && !empty($data['KDKEGIATAN'])) {
            $result = $user->create($data); // Save to DOKUMENKEGIATAN
            $this->jsonResponse($result);
            return;
        }
    
        // Inserting data into TABLE BARANGTRANSAKSI
        if (!empty($data['IDTRANSAKSI']) && !empty($data['KDKATEGORIBARANG']) && !empty($data['KDBARANG']) && !empty($data['URAIANBARANG']) && !empty($data['JUMLAH']) && !empty($data['KDSATUAN']) && !empty($data['NILAI']) && !empty($data['NOMORDOKKEGIATAN'])) {
            $result = $user->create($data); // Save to BARANGTRANSAKSI
            $this->jsonResponse($result);
            return;
        }
    
        // Inserting data into TABLE DOKUMEN
        if (!empty($data['IDDOKUMEN']) && !empty($data['KODEDOKUMEN']) && !empty($data['NOMORDOKUMEN']) && !empty($data['TANGGALDOKUMEN']) && !empty($data['IDTRANSAKSI'])) {
            $result = $user->create($data); // Save to DOKUMEN
            $this->jsonResponse($result);
            return;
        }
    
        // Default response if no condition matches
        $this->jsonResponse(["message" => "Invalid input"], 400);
    }

    public function updateUser($data) {
        if (!empty($data['NO_KEGIATAN']) && !empty($data['TANGGALKEGIATAN']) && !empty($data['NPWP']) && !empty($data['NIB'])) {
            $user = new User();
            $result = $user->update($data);
            $this->jsonResponse($result);
        } else {
            $this->jsonResponse(["message" => "Invalid input"], 400);
        }
        if (!empty($data['NOMORDOKKEGIATAN']) && !empty($data['TANGGALKEGIATAN']) && !empty($data['NAMAENTITAS']) && !empty($data['KDKEGIATAN'])) {
            $user = new User();
            $result = $user->update($data);
            $this->jsonResponse($result);
        } else {
            $this->jsonResponse(["message" => "Invalid input"], 400);
        }
        if (!empty($data['IDTRANSAKSI']) && !empty($data['KDKATEGORIBARANG']) && !empty($data['KDBARANG']) && !empty($data['URAIANBARANG']) && !empty($data['JUMLAH']) && !empty($data['KDSATUAN']) && !empty($data['NILAI']) && !empty($data['NOMORDOKKEGIATAN'])) {
            $user = new User();
            $result = $user->update($data);
            $this->jsonResponse($result);
        } else {
            $this->jsonResponse(["message" => "Invalid input"], 400);
        }
        if (!empty($data['IDDOKUMEN']) && !empty($data['KODEDOKUMEN']) && !empty($data['NOMORDOKUMEN']) && !empty($data['TANGGALDOKUMEN']) && !empty($data['IDTRANSAKSI'])) {
            $user = new User();
            $result = $user->update($data);
            $this->jsonResponse($result);
        } else {
            $this->jsonResponse(["message" => "Invalid input"], 400);
        }
    }

    public function deleteUser($id) {
        // Make sure ID is provided
        if (!empty($id)) {
            $user = new User();
            $response = $user->delete($id);
            $this->jsonResponse($response);
        } else {
            $this->jsonResponse(["message" => "ID is required for deletion"], 400);
        }
    }
}
?>
