<?php

class EventDao {
    private $mysqli;
    
    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
        
    }
    
    public function insert(Event $event) {
        $INSERT_SQL = "INSERT INTO events (
            ICALUID,
            STARTDATE, 
            STARTDATETIME,
            SUMMARY,
            LOCATION,    
            FACEBOOKLINK,
            TICKETPURCHASELINK,
            FLYERURL,
            VISIBILITY,
            SEQUENCE,
            CREATED,
            UPDATED,
            CREATOR) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, )";
        $stmt = $this->mysqli->prepare($INSERT_SQL);
        $stmt->bind_param("ssssssssssssss", 
            $event->calendarSummary,                   
            $event->calendarLocation,
            $event->calendarICalUID,
            $event->calendarStart);
        if (!$stmt->execute()) {
            printf("error during insert event: %s, %s\n", $event->calendarSummary,  $stmt->error);
        }
        $stmt->close();
        
    }
    
    public function update(Event $event) {
        //echo "update: " . $event->calendarSummary . "\n"; 
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
        if (!$stmt->execute()) {
            printf("error during update event: %s, %s\n", $event->calendarSummary,  $stmt->error);
            exit();
        }
        $stmt->close();
        
    }
    
    public function delete(Event $event) {
        $INSERT_SQL = "DELETE FROM events WHERE ID = ?";
        $stmt = $this->mysqli->prepare($INSERT_SQL);
        $stmt->bind_param("s", $event->id);
        if (!$stmt->execute()) {
            printf("error during delete event: %s, %s\n", $event->calendarSummary,  $stmt->error);
            exit();
        }
        $stmt->close();
    }
    
   
    public function findByCalendarStartGreaterOrEqualThan($calendarStart) {
        $SELECT_SQL = "SELECT ID, CALENDARICALUID FROM EVENTS WHERE CALENDARSTART >= ?";
        $stmt = $this->mysqli->prepare($SELECT_SQL);
        $stmt->bind_param("s", $calendarStart);
        $stmt->execute();
        if (!$stmt->execute()) {
            printf("error during findByCalendarStartGreaterOrEqualThan event: %s, %s\n", $calendarStart,  $stmt->error);
            exit();
        }
        $result = $stmt->get_result();

        $events = array($stmt->num_rows);
        $i = 0;
        $event = null;            
        while ($row = $result->fetch_assoc()) {
            $event = new Event();
            $event->calendarICalUID = $row["CALENDARICALUID"];
            $event->id = $row["ID"];
            $events[$i++] = $event;
        }
        $result->close();
        $stmt->close();
        return $events;
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
        if (!$stmt->execute()) {
            printf("error during findByOrigCalUID event: %s, %s\n", $event->calendarSummary,  $stmt->error);
            exit();
        }
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
        return $event;
    }
        
    
}
?>
