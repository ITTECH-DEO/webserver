<?php
include_once './config/Database.php';

class User {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function fetchAll() {
        $query = "
            SELECT * 
            FROM DOKUMENKEGIATAN 
            INNER JOIN BARANGTRANSAKSI ON DOKUMENKEGIATAN.NOMORDOKKEGIATAN = BARANGTRANSAKSI.NOMORDOKKEGIATAN
            INNER JOIN DOKUMEN ON BARANGTRANSAKSI.IDTRANSAKSI = DOKUMEN.IDTRANSAKSI
        ";
        $stmt = oci_parse($this->conn, $query);
        oci_execute($stmt);
    
        $users = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $users[] = $row;
        }
    
        return $users;
    }
    

    public function create($data) {
        // Begin transaction
        oci_execute(oci_parse($this->conn, "BEGIN TRANSACTION"));

        try {
            // Insert into KEGIATAN
            $queryKegiatan = "INSERT INTO KEGIATAN (NOKEGIATAN, TANGGALKEGIATAN, NPWP, NIB) VALUES (:NOKEGIATAN, :TANGGALKEGIATAN, :NPWP, :NIB)";
            $stmtKegiatan = oci_parse($this->conn, $queryKegiatan);
            oci_bind_by_name($stmtKegiatan, ":NOKEGIATAN", $data['NOKEGIATAN']);
            oci_bind_by_name($stmtKegiatan, ":TANGGALKEGIATAN", $data['TANGGALKEGIATAN']);
            oci_bind_by_name($stmtKegiatan, ":NPWP", $data['NPWP']);
            oci_bind_by_name($stmtKegiatan, ":NIB", $data['NIB']);
            if (!oci_execute($stmtKegiatan)) {
                throw new Exception("Failed to insert into KEGIATAN");
            }

            // Insert into DOKUMENKEGIATAN
            $queryDokumenKegiatan = "INSERT INTO DOKUMENKEGIATAN (NOMORDOKKEGIATAN, TANGGALKEGIATAN, NAMAENTITAS, KDKEGIATAN) VALUES (:NOMORDOKKEGIATAN, :TANGGALKEGIATAN, :NAMAENTITAS, :KDKEGIATAN)";
            $stmtDokumenKegiatan = oci_parse($this->conn, $queryDokumenKegiatan);
            oci_bind_by_name($stmtDokumenKegiatan, ":NOMORDOKKEGIATAN", $data['NOMORDOKKEGIATAN']);
            oci_bind_by_name($stmtDokumenKegiatan, ":TANGGALKEGIATAN", $data['TANGGALKEGIATAN']);
            oci_bind_by_name($stmtDokumenKegiatan, ":NAMAENTITAS", $data['NAMAENTITAS']);
            oci_bind_by_name($stmtDokumenKegiatan, ":KDKEGIATAN", $data['KDKEGIATAN']);
            if (!oci_execute($stmtDokumenKegiatan)) {
                throw new Exception("Failed to insert into DOKUMENKEGIATAN");
            }

            // Insert into BARANGTRANSAKSI
            $queryBarangTransaksi = "INSERT INTO BARANGTRANSAKSI (IDTRANSAKSI, KDKATEGORIBARANG, KDBARANG, URAIANBARANG, JUMLAH, KDSATUAN, NILAI, NOMORDOKKEGIATAN) VALUES (:IDTRANSAKSI, :KDKATEGORIBARANG, :KDBARANG, :URAIANBARANG, :JUMLAH, :KDSATUAN, :NILAI, :NOMORDOKKEGIATAN)";
            $stmtBarangTransaksi = oci_parse($this->conn, $queryBarangTransaksi);
            oci_bind_by_name($stmtBarangTransaksi, ":IDTRANSAKSI", $data['IDTRANSAKSI']);
            oci_bind_by_name($stmtBarangTransaksi, ":KDKATEGORIBARANG", $data['KDKATEGORIBARANG']);
            oci_bind_by_name($stmtBarangTransaksi, ":KDBARANG", $data['KDBARANG']);
            oci_bind_by_name($stmtBarangTransaksi, ":URAIANBARANG", $data['URAIANBARANG']);
            oci_bind_by_name($stmtBarangTransaksi, ":JUMLAH", $data['JUMLAH']);
            oci_bind_by_name($stmtBarangTransaksi, ":KDSATUAN", $data['KDSATUAN']);
            oci_bind_by_name($stmtBarangTransaksi, ":NILAI", $data['NILAI']);
            oci_bind_by_name($stmtBarangTransaksi, ":NOMORDOKKEGIATAN", $data['NOMORDOKKEGIATAN']);
            if (!oci_execute($stmtBarangTransaksi)) {
                throw new Exception("Failed to insert into BARANGTRANSAKSI");
            }

            // Insert into DOKUMEN
            $queryDokumen = "INSERT INTO DOKUMEN (IDDOKUMEN, KODEDOKUMEN, NOMORDOKUMEN, TANGGALDOKUMEN, IDTRANSAKSI) VALUES (:IDDOKUMEN, :KODEDOKUMEN, :NOMORDOKUMEN, :TANGGALDOKUMEN, :IDTRANSAKSI)";
            $stmtDokumen = oci_parse($this->conn, $queryDokumen);
            oci_bind_by_name($stmtDokumen, ":IDDOKUMEN", $data['IDDOKUMEN']);
            oci_bind_by_name($stmtDokumen, ":KODEDOKUMEN", $data['KODEDOKUMEN']);
            oci_bind_by_name($stmtDokumen, ":NOMORDOKUMEN", $data['NOMORDOKUMEN']);
            oci_bind_by_name($stmtDokumen, ":TANGGALDOKUMEN", $data['TANGGALDOKUMEN']);
            oci_bind_by_name($stmtDokumen, ":IDTRANSAKSI", $data['IDTRANSAKSI']);
            if (!oci_execute($stmtDokumen)) {
                throw new Exception("Failed to insert into DOKUMEN");
            }

            // Commit transaction if everything is successful
            oci_execute(oci_parse($this->conn, "COMMIT"));
            return ["message" => "Data inserted successfully"];
            
        } catch (Exception $e) {
            // Rollback transaction if any query fails
            oci_execute(oci_parse($this->conn, "ROLLBACK"));
            return ["message" => "Error: " . $e->getMessage()];
        }
    }

    public function update($data) {
        // Update KEGIATAN
        $query = "UPDATE KEGIATAN SET TANGGALKEGIATAN = :TANGGALKEGIATAN, NPWP= :NPWP, NIB = :NIB WHERE NOKEGIATAN = :NOKEGIATAN";
        $stmt = oci_parse($this->conn, $query);

        oci_bind_by_name($stmt, ":NOKEGIATAN", $data['NOKEGIATAN']);
        oci_bind_by_name($stmt, ":TANGGALKEGIATAN", $data['TANGGALKEGIATAN']);
        oci_bind_by_name($stmt, ":NPWP", $data['NPWP']);
        oci_bind_by_name($stmt, ":NIB", $data['NIB']);

        if (!oci_execute($stmt)) {
            return ["message" => "Error updating KEGIATAN"];
        }

        // Update DOKUMENKEGIATAN
        $query = "UPDATE DOKUMENKEGIATAN SET TANGGALKEGIATAN = :TANGGALKEGIATAN, NAMAENTITAS = :NAMAENTITAS, KDKEGIATAN = :KDKEGIATAN WHERE NOMORDOKKEGIATAN = :NOMORDOKKEGIATAN";
        $stmt = oci_parse($this->conn, $query);

        oci_bind_by_name($stmt, ":NOMORDOKKEGIATAN", $data['NOMORDOKKEGIATAN']);
        oci_bind_by_name($stmt, ":TANGGALKEGIATAN", $data['TANGGALKEGIATAN']);
        oci_bind_by_name($stmt, ":NAMAENTITAS", $data['NAMAENTITAS']);
        oci_bind_by_name($stmt, ":KDKEGIATAN", $data['KDKEGIATAN']);

        if (!oci_execute($stmt)) {
            return ["message" => "Error updating DOKUMENKEGIATAN"];
        }

        // Update BARANGTRANSAKSI
        $query = "UPDATE BARANGTRANSAKSI SET KDKATEGORIBARANG = :KDKATEGORIBARANG, KDBARANG = :KDBARANG, URAIANBARANG = :URAIANBARANG, JUMLAH = :JUMLAH, KDSATUAN = :KDSATUAN, NILAI = :NILAI, NOMORDOKKEGIATAN = :NOMORDOKKEGIATAN WHERE IDTRANSAKSI = :IDTRANSAKSI";
        $stmt = oci_parse($this->conn, $query);

        oci_bind_by_name($stmt, ":IDTRANSAKSI", $data['IDTRANSAKSI']);
        oci_bind_by_name($stmt, ":KDKATEGORIBARANG", $data['KDKATEGORIBARANG']);
        oci_bind_by_name($stmt, ":KDBARANG", $data['KDBARANG']);
        oci_bind_by_name($stmt, ":URAIANBARANG", $data['URAIANBARANG']);
        oci_bind_by_name($stmt, ":JUMLAH", $data['JUMLAH']);
        oci_bind_by_name($stmt, ":KDSATUAN", $data['KDSATUAN']);
        oci_bind_by_name($stmt, ":NILAI", $data['NILAI']);
        oci_bind_by_name($stmt, ":NOMORDOKKEGIATAN", $data['NOMORDOKKEGIATAN']);

        if (!oci_execute($stmt)) {
            return ["message" => "Error updating BARANGTRANSAKSI"];
        }

        // Update DOKUMEN
        $query = "UPDATE DOKUMEN SET KODEDOKUMEN = :KODEDOKUMEN, NOMORDOKUMEN = :NOMORDOKUMEN, TANGGALDOKUMEN = :TANGGALDOKUMEN, IDTRANSAKSI = :IDTRANSAKSI WHERE IDDOKUMEN = :IDDOKUMEN";
        $stmt = oci_parse($this->conn, $query);

        oci_bind_by_name($stmt, ":IDDOKUMEN", $data['IDDOKUMEN']);
        oci_bind_by_name($stmt, ":KODEDOKUMEN", $data['KODEDOKUMEN']);
        oci_bind_by_name($stmt, ":NOMORDOKUMEN", $data['NOMORDOKUMEN']);
        oci_bind_by_name($stmt, ":TANGGALDOKUMEN", $data['TANGGALDOKUMEN']);
        oci_bind_by_name($stmt, ":IDTRANSAKSI", $data['IDTRANSAKSI']);

        if (!oci_execute($stmt)) {
            return ["message" => "Error updating DOKUMEN"];
        }

        return ["message" => "Data updated successfully"];
        
    }

    public function delete($id) {
    $query = "DELETE FROM KEGIATAN WHERE NO_KEGIATAN = :NO_KEGIATAN";
    $stmt = oci_parse($this->conn, $query);
    
    oci_bind_by_name($stmt, ":NO_KEGIATAN", $id);
    
    if (oci_execute($stmt)) {
        return ["message" => "Kegiatan deleted successfully"];
    } else {
        return ["message" => "Error deleting user"];
    }
    $query = "DELETE FROM DOKUMENKEGIATAN WHERE NOMORDOKKEGIATAN = :NOMORDOKKEGIATAN";
    $stmt = oci_parse($this->conn, $query);
    
    oci_bind_by_name($stmt, ":NOMORDOKKEGIATAN", $id);
    
    if (oci_execute($stmt)) {
        return ["message" => "Dokumenkegiatan deleted successfully"];
    } else {
        return ["message" => "Error deleting user"];
    }
    $query = "DELETE FROM BARANGTRANSAKSI WHERE IDTRANSAKSI = :IDTRANSAKSI";
    $stmt = oci_parse($this->conn, $query);
    
    oci_bind_by_name($stmt, ":IDTRANSAKSI", $id);
    
    if (oci_execute($stmt)) {
        return ["message" => "Barangtransaksi deleted successfully"];
    } else {
        return ["message" => "Error deleting user"];
    }
    $query = "DELETE FROM DOKUMEN WHERE IDDOKUMEN = :IDDOKUMEN";
    $stmt = oci_parse($this->conn, $query);
    
    oci_bind_by_name($stmt, ":IDDOKUMEN", $id);
    
    if (oci_execute($stmt)) {
        return ["message" => "Dokumen deleted successfully"];
    } else {
        return ["message" => "Error deleting user"];
    }
}
}
?>
