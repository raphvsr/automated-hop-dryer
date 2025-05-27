<!-- //                 file alerts.php                
// ===============================================
//        Original Author: Romain Provencel       
// ===============================================

// COMMIT HISTORY:
// ============================================================
// 2025-04-02 - changed folder name removed the space - fateh kabbani
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-03-20 - move the file to raspberry pi - fateh kabbani
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-03-20 - Refactor database interactions in sensorData and DryingCampaigns classes for improved error handling and SQL injection protection; add alerts class for managing alert data. - Romain Provencel
//   web/backend/php/classes/alerts.php | 173 +++++++++++++++++++++++++++++++++++++
//   1 file changed, 173 insertions(+)
//
// ============================================================ -->

<?php
include '../database.php';

class alerts {
    private $conn;

    /**
     * Constructor for the class.
     * @param mysqli $conn - The database connection.
     */
    public function __construct($conn) {
        $this->conn = $conn;
    }

    /**
     * Retrieves all alerts.
     * @return array - An associative array containing alerts.
     * @throws Exception - If an SQL error occurs.
     */
    public function getAlerts() {
        $sql = "
            SELECT 
                a.id AS alert_id, 
                a.campaign_id, 
                a.alert_time, 
                a.alert_type, 
                a.message
            FROM 
                alerts a
            LEFT JOIN 
                drying_campaigns dc ON a.campaign_id = dc.id
        ";
        $result = $this->conn->query($sql);

        if (!$result) {
            throw new Exception("Error executing the query: " . $this->conn->error);
        }

        $alerts = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $alerts[] = $row;
            }
        }
        return $alerts;
    }

    /**
     * Retrieves a specific alert by its ID.
     * @param int $id - The ID of the alert to retrieve.
     * @return array|null - An associative array containing alert data, or null if not found.
     * @throws Exception - If an SQL error occurs.
     */
    public function getAlert($id) {
        $stmt = $this->conn->prepare("
            SELECT 
                a.id AS alert_id, 
                a.campaign_id, 
                a.alert_time, 
                a.alert_type, 
                a.message
            FROM 
                alerts a
            LEFT JOIN 
                drying_campaigns dc ON a.campaign_id = dc.id
            WHERE
                a.id = ?
        ");
        if (!$stmt) {
            throw new Exception("Error preparing the query: " . $this->conn->error);
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }

    /**
     * Adds a new alert to the database.
     * @param array $data - An associative array containing alert data.
     *   - 'campaign_id': int - The ID of the associated drying campaign.
     *   - 'alert_time': string - The time the alert was triggered.
     *   - 'alert_type': string - The type of alert.
     *   - 'message': string - The alert message.
     * @return int - The ID of the newly inserted alert.
     * @throws Exception - If an SQL error occurs.
     */
    public function addAlert($data) {
        $campaign_id = $data['campaign_id'];
        $alert_time = $data['alert_time'];
        $alert_type = $data['alert_type'];
        $message = $data['message'];

        $sql = "INSERT INTO alerts (campaign_id, alert_time, alert_type, message) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error preparing the query: " . $this->conn->error);
        }

        $stmt->bind_param("isss", $campaign_id, $alert_time, $alert_type, $message);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return $stmt->insert_id;
        } else {
            throw new Exception("Error adding the alert: " . $stmt->error);
        }
    }

    /**
     * Updates an existing alert.
     * @param int $id - The ID of the alert to update.
     * @param array $data - An associative array containing updated alert data.
     *   - 'campaign_id': int - The ID of the associated drying campaign.
     *   - 'alert_time': string - The time the alert was triggered.
     *   - 'alert_type': string - The type of alert.
     *   - 'message': string - The alert message.
     * @return bool - True if the update was successful, false otherwise.
     * @throws Exception - If an SQL error occurs.
     */
    public function updateAlert($id, $data) {
        $campaign_id = $data['campaign_id'];
        $alert_time = $data['alert_time'];
        $alert_type = $data['alert_type'];
        $message = $data['message'];

        $sql = "UPDATE alerts SET campaign_id = ?, alert_time = ?, alert_type = ?, message = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error preparing the query: " . $this->conn->error);
        }

        $stmt->bind_param("isssi", $campaign_id, $alert_time, $alert_type, $message, $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            throw new Exception("Error updating the alert: " . $stmt->error);
        }
    }

    /**
     * Deletes an alert by its ID.
     * @param int $id - The ID of the alert to delete.
     * @return bool - True if the deletion was successful, false otherwise.
     * @throws Exception - If an SQL error occurs.
     */
    public function deleteAlert($id) {
        $sql = "DELETE FROM alerts WHERE id = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new Exception("Error preparing the query: " . $this->conn->error);
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            throw new Exception("Error deleting the alert: " . $stmt->error);
        }
    }
}
?>