<?php

class EventDao {
    private $mysqli;
    
    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
        
    }
    
    public function insert(Event $event) {
        echo "insert: " . $event->calendarSummary . "\n";
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        
        $INSERT_SQL = "INSERT INTO events (
            CALENDARSUMMARY, 
            CALENDARLOCATION, 
            CALENDARICALUID, 
            CALENDARSTART, 
            CREATED, 
            UPDATED) 
            VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
        $stmt = $this->mysqli->prepare($INSERT_SQL);
        $stmt->bind_param("ssss", 
            $event->calendarSummary,                   
            $event->calendarLocation,
            $event->calendarICalUID,
            $event->calendarStart);
        $stmt->execute();
        $stmt->close();
        
    }
    
    public function update(Event $event) {
        echo "update: " . $event->calendarSummary . "\n"; 
        $INSERT_SQL = "UPDATE events set 
            CALENDARSUMMARY = ?, 
            CALENDARLOCATION = ?, 
            CALENDARSTART = ?, 
            UPDATED = CURRENT_TIMESTAMP, 
            CHANGED = 1
            WHERE ID = ?";
        $stmt = $this->mysqli->prepare($INSERT_SQL);
        $stmt->bind_param("ssss", 
            $event->calendarSummary,
            $event->calendarLocation,
            $event->calendarStart,
            $event->id);
        $stmt->execute();
        $stmt->close();
        
    }
    
    public function delete(Event $event) {
        $INSERT_SQL = "DELETE FROM events WHERE ID = ?";
        $stmt = $this->mysqli->prepare($INSERT_SQL);
        $stmt->bind_param("s", $event->getId());
        $stmt->execute();
        $stmt->close();
    }
    
   
    public function findByCalendarStartGreaterThan($calendarstart) {
        return [];    
    }
    
    
    public function findByOrigCalUID($calendarICalUID) {
        $INSERT_SQL = "SELECT  
            CALENDARLOCATION, 
            CALENDARICALUID, 
            CALENDARSTART, 
            CALENDARSUMMARY, 
            CHANGED, 
            CREATED, 
            DESCRIPTION, 
            FACEBOOKLINK, 
            ID, 
            LOCATION, 
            TICKETPURCHASELINK, 
            UPDATED, 
            VISIBLE 
                    FROM EVENTS WHERE CALENDARICALUID = ?";
        $stmt = $this->mysqli->prepare($INSERT_SQL);
        $stmt->bind_param("s", $calendarICalUID);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        $event = null;
        if ($row) {
            $event = new Event();
            $event->calendarLocation = $row["CALENDARLOCATION"];
            $event->calendarICalUID = $row["CALENDARICALUID"];
            $event->calendarStart = $row["CALENDARSTART"];
            $event->calendarSummary = $row["CALENDARSUMMARY"];
            $event->id = $row["ID"];
        }
        $result->close();
        $stmt->close();
        echo "find: " . $event->calendarSummary . "\n";
        return $event;
    }
    
    public function deleteAll() {
        $DELETE_ALL_SQL = "delete from Events";
        $this->mysqli->query($DELETE_ALL_SQL);         
    }
    
    
    
}
?>
