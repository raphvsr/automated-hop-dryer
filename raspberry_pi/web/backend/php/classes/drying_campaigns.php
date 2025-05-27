<?php
//            file drying_campaigns.php           
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
//   web/backend/php/classes/drying_campaigns.php | 11 ++++++++++-
//   1 file changed, 10 insertions(+), 1 deletion(-)
//
// 2025-03-19 - Add DryingCampaigns class for managing drying campaigns and associated hop varieties - Romain Provencel
//   web/backend/php/classes/drying_campaigns.php | 158 +++++++++++++++++++++++++++
//   1 file changed, 158 insertions(+)
//
// ============================================================

include '../database.php';

class DryingCampaigns {
    private $conn;

    /**
     * Constructor for the class.
     * @param mysqli $conn - The database connection.
     */
    public function __construct($conn) {
        $this->conn = $conn;
    }

    /**
     * Retrieves all drying campaigns with associated hop variety information.
     * @return array - An associative array containing campaign and variety data.
     * @throws Exception - If an SQL error occurs.
     */
    public function getDryingCampaigns() {
        $sql = "
            SELECT 
                dc.id AS campaign_id, 
                dc.start_time, 
                dc.end_time, 
                hv.name AS variety_name, 
                hv.max_temperature
            FROM 
                drying_campaigns dc
            JOIN 
                hop_varieties hv ON dc.variety_id = hv.id
        ";
        $result = $this->conn->query($sql);

        if (!$result) {
            throw new Exception("Error executing the query: " . $this->conn->error);
        }

        $dryingCampaigns = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $dryingCampaigns[] = $row;
            }
        }
        return $dryingCampaigns;
    }

    /**
     * Retrieves a specific drying campaign by its ID, with associated hop variety information.
     * @param int $id - The ID of the campaign to retrieve.
     * @return array|null - An associative array containing campaign and variety data, or null if not found.
     * @throws Exception - If an SQL error occurs.
     */
    // Example response
    // {
    //     "campaign_id": 1,
    //     "start_time": "2024-01-01 08:00:00",
    //     "end_time": "2024-01-01 14:00:00",
    //     "variety_name": "Cascade",
    //     "max_temperature": 55.00
    // }
    public function getDryingCampaign($id) {
        $sql = "
            SELECT 
                dc.id AS campaign_id, 
                dc.start_time, 
                dc.end_time, 
                hv.name AS variety_name, 
                hv.max_temperature
            FROM 
                drying_campaigns dc
            JOIN 
                hop_varieties hv ON dc.variety_id = hv.id
            WHERE 
                dc.id = ?
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $dryingCampaign = $result->fetch_assoc();
        return $dryingCampaign;
    }

    /**
     * Creates a new drying campaign.
     * @param array $data - An associative array containing the campaign data:
     *   - 'variety_id' (int): The ID of the hop variety.
     *   - 'start_time' (string): The start date and time of the campaign (DATETIME format).
     *   - 'end_time' (string|null): The end date and time of the campaign (DATETIME format, optional).
     * @return int - The ID of the created campaign.
     * @throws Exception - If the variety_id is invalid or if an SQL error occurs.
     */
    public function createDryingCampaign($data) {
        try {
            if (!isset($data['start_time'])) {
                throw new Exception("start_time is required.");
            }

            $sql = "INSERT INTO drying_campaigns (variety_id, start_time, end_time) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($sql);

            $end_time = isset($data['end_time']) ? $data['end_time'] : null;

            $stmt->bind_param("iss", $data['variety_id'], $data['start_time'], $end_time);
            $stmt->execute();
            return $stmt->insert_id;
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1452) {
                throw new Exception("Invalid variety_id: " . $data['variety_id']);
            } else {
                throw $e;
            }
        }
    }

    /**
     * Updates an existing drying campaign.
     * @param int $id - The ID of the campaign to update.
     * @param array $data - An associative array containing the new campaign data:
     *   - 'variety_id' (int): The new ID of the hop variety.
     *   - 'start_time' (string): The new start date and time of the campaign (DATETIME format).
     *   - 'end_time' (string|null): The new end date and time of the campaign (DATETIME format, optional).
     * @throws Exception - If the variety_id is invalid or if an SQL error occurs.
     */
    public function updateDryingCampaign($id, $data) {
        try {
            $sql = "UPDATE drying_campaigns SET variety_id = ?, start_time = ?, end_time = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);

            $end_time = isset($data['end_time']) ? $data['end_time'] : null;

            $stmt->bind_param("issi", $data['variety_id'], $data['start_time'], $end_time, $id);
            $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1452) {
                throw new Exception("Invalid variety_id: " . $data['variety_id']);
            } else {
                throw $e;
            }
        }
    }

    /**
     * Deletes a drying campaign.
     * @param int $id - The ID of the campaign to delete.
     * @return bool - True if the deletion was successful, false otherwise.
     */
    public function deleteDryingCampaign($id) {
        $sql = "DELETE FROM drying_campaigns WHERE id = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new Exception("Error preparing the query: " . $this->conn->error);
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            throw new Exception("Error deleting the drying_campaign: " . $stmt->error);
        }
    }
}
?>