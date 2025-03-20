<?php
include '../database.php';

class sensorData {
    private $conn;

    /**
     * Constructor for the class.
     * @param mysqli $conn - The database connection.
     */
    public function __construct($conn) {
        $this->conn = $conn;
    }

    /**
     * Retrieves all sensor data.
     * @return array - An associative array containing sensor data.
     * @throws Exception - If an SQL error occurs.
     */
    public function getSensorDatas() {
        $sql = "
            SELECT 
                sd.id AS sensor_id, 
                sd.temperature, 
                sd.humidity, 
                sd.timestamp
            FROM 
                sensor_data sd
        ";
        $result = $this->conn->query($sql);

        if (!$result) {
            throw new Exception("Error executing the query: " . $this->conn->error);
        }

        $sensorDatas = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $sensorDatas[] = $row;
            }
        }
        return $sensorDatas;
    }

    /**
     * Retrieves a specific sensor data by its ID.
     * @param int $id - The ID of the sensor data to retrieve.
     * @return array|null - An associative array containing sensor data, or null if not found.
     * @throws Exception - If an SQL error occurs.
     */
    public function getSensorData($id) {
        $sql = "
            SELECT 
                sd.id AS sensor_id, 
                sd.temperature, 
                sd.humidity, 
                sd.timestamp
            FROM 
                sensor_data sd
            WHERE
                sd.id = $id
        ";
        $result = $this->conn->query($sql);

        if (!$result) {
            throw new Exception("Error executing the query: " . $this->conn->error);
        }

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }

    /**
     * Adds a new sensor data entry.
     * @param array $data - An associative array containing sensor data.
     *   - 'temperature' (float): The temperature value.
     *   - 'humidity' (float): The humidity value.
     *   - 'timestamp' (string): The timestamp value.
     * @return int - The ID of the newly created sensor data entry.
     * @throws Exception - If an SQL error occurs.
     */
    public function addSensorData($data) {
        $temperature = $data['temperature'];
        $humidity = $data['humidity'];
        $timestamp = $data['timestamp'];

        $sql = "
            INSERT INTO sensor_data (temperature, humidity, timestamp)
            VALUES ($temperature, $humidity, '$timestamp')
        ";
        $result = $this->conn->query($sql);

        if (!$result) {
            throw new Exception("Error executing the query: " . $this->conn->error);
        } 
    }

    /**
     * Updates a sensor data entry.
     * @param int $id - The ID of the sensor data entry to update.
     * @param array $data - An associative array containing sensor data.
     *   - 'temperature' (float): The temperature value.
     *   - 'humidity' (float): The humidity value.
     *   - 'timestamp' (string): The timestamp value.
     * @throws Exception - If an SQL error occurs.
     */
    public function updateSensorData($id, $data) {
        $temperature = $data['temperature'];
        $humidity = $data['humidity'];
        $timestamp = $data['timestamp'];

        $sql = "
            UPDATE sensor_data
            SET temperature = $temperature, humidity = $humidity, timestamp = '$timestamp'
            WHERE id = $id
        ";
        $result = $this->conn->query($sql);

        if (!$result) {
            throw new Exception("Error executing the query: " . $this->conn->error);
        }
    }

    /**
     * Deletes a sensor data entry.
     * @param int $id - The ID of the sensor data entry to delete.
     * @throws Exception - If an SQL error occurs.
     */
    public function deleteSensorData($id) {
        $sql = "
            DELETE FROM sensor_data
            WHERE id = $id
        ";
        $result = $this->conn->query($sql);

        if (!$result) {
            throw new Exception("Error executing the query: " . $this->conn->error);
        }
    }
}
?>