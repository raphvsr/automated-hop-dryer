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
        $sql = "SELECT id, temperature, timestamp
            FROM sensor_data";
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
     *   - 'timestamp' (string): The timestamp value.
     * @return int - The ID of the newly created sensor data entry.
     * @throws Exception - If an SQL error occurs.
     */
    public function addSensorData($data) {
        $temperature = $data['temperature'];
        $timestamp = $data['timestamp'];

        $sql = "
            INSERT INTO sensor_data (temperature, timestamp)
            VALUES (?, ?)
        ";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error preparing the query: " . $this->conn->error);
        }

        $stmt->bind_param("dd", $temperature, $humidity, $timestamp);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return $stmt->insert_id;
        } else {
            throw new Exception("Error adding the sensor_data: " . $stmt->error);
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
            SET temperature = ?, humidity = ?, timestamp = ?
            WHERE id = ?
        ";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error preparing the query: " . $this->conn->error);
        }

        $stmt->bind_param("ddsi", $temperature, $humidity, $timestamp, $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            throw new Exception("Error updating the sensor_data: " . $stmt->error);
        }
    }

    /**
     * Deletes a sensor data entry.
     * @param int $id - The ID of the sensor data entry to delete.
     * @throws Exception - If an SQL error occurs.
     */
    public function deleteSensorData($id) {
        $sql = "DELETE sensor_data WHERE id = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new Exception("Error preparing the query: " . $this->conn->error);
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            throw new Exception("Error deleting the sensor_data: " . $stmt->error);
        }
    }
}
?>
