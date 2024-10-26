<?php
class Database {
    private $host = '127.0.0.1';
    private $port = '1521';
    private $service_name = 'lokal';
    private $username = 'system';
    private $password = 'mclaren';
    private $conn;

    public function connect() {
        $this->conn = oci_connect($this->username, $this->password, "//" . $this->host . ":" . $this->port . "/" . $this->service_name);
        
        if (!$this->conn) {
            $e = oci_error();
            echo "Connection failed: " . $e['message'];
            return null;
        }
        
        return $this->conn;
    }
}
?>
