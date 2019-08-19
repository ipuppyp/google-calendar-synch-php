<?php

class EventDao {
    private $mysqli;
    
    private $FIELDS = " ICALUID,
                        STARTDATE,
                        STARTDATETIME,
                        SUMMARY,
                        LOCATION,
                        FACEBOOKLINK,
                        TICKETSLINK,
                        FLYERURL,
                        VISIBILITY,
                        CREATED,
                        UPDATED,
                        CREATOR";
    
    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
    }
    
    public function insert(Event $event) {
        $SQL = "INSERT INTO events (
            $this->FIELDS) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->mysqli->prepare($SQL);
        
        $stmt->bind_param("ssssssssssss", 
            $event->iCalUID,
            $event->startDate,
            $this->createDateTime($event->startDateTime),
            $event->summary,
            $event->location,
            $event->facebookLink,
            $event->ticketsLink,
            $event->flyerUrl,
            $event->visibility,
            $this->createDateTime($event->created),
            $this->createDateTime($event->updated),
            $event->creator);
        if (!$stmt->execute()) {
            printf("error during insert event: %s, %s\n", $event->summary,  $stmt->error);
        }
        $stmt->close();
        
    }
    


    private function createDateTime($value)
    {
        $result = null;
        if ($value != null) {
            $result = date("Y-m-d h:i:s", strtotime($value));
        }
        return $result;
    }

    public function update(Event $event) {
        //echo "update: " . $event->calendarSummary . "\n"; 
        $SQL = "UPDATE events set 
            STARTDATE = ?, 
            STARTDATETIME = ?,
            SUMMARY = ?,
            LOCATION = ?,    
            FACEBOOKLINK = ?,
            TICKETSLINK = ?,
            FLYERURL = ?,
            VISIBILITY = ?,
            CREATED = ?,
            UPDATED = ?,
            CREATOR = ?
            WHERE ICALUID = ?";
        $stmt = $this->mysqli->prepare($SQL);
        $stmt->bind_param("ssssssssssss", 
            $event->startDate,
            $this->createDateTime($event->startDateTime),
            $event->summary,
            $event->location,
            $event->facebookLink,
            $event->ticketsLink,
            $event->flyerUrl,
            $event->visibility,
            $this->createDateTime($event->created),
            $this->createDateTime($event->updated),
            $event->creator,
            $event->iCalUID);
        if (!$stmt->execute()) {
            printf("error during update event: %s, %s\n", $event->summary,  $stmt->error);
            exit();
        }
        $stmt->close();
        
    }
    
    public function delete(Event $event) {
        $SQL = "DELETE FROM events WHERE ID = ?";
        $stmt = $this->mysqli->prepare($SQL);
        $stmt->bind_param("s", $event->id);
        if (!$stmt->execute()) {
            printf("error during delete event: %s, %s\n", $event->calendarSummary,  $stmt->error);
            exit();
        }
        $stmt->close();
    }
   
    public function findFutureImportedEvents() {
        $SQL = "SELECT ID, ICALUID 
                        FROM EVENTS 
                        WHERE (STARTDATE >= CURRENT_TIMESTAMP OR STARTDATETIME >= CURRENT_TIMESTAMP) AND ICALUID IS NOT NULL";
        $result = $this->mysqli->query($SQL);

        if (!$result) {
            printf("error during findFutureEvents event: %s\n", $result->error);
            exit();
        }
        $events = array();
        if ($result->num_rows) {            
            $events = array($result->num_rows);
            $i = 0;
            while ($row = $result->fetch_assoc()) {
                $event = new Event();
                $event->id = $row["ID"];
                $event->iCalUID = $row["ICALUID"];
                $events[$i++] = $event;
            }
        }
        $result->close();
        return $events;
    }
    
    public function findAll() {
        $SQL = "SELECT ID, $this->FIELDS FROM EVENTS WHERE VISIBILITY IS NULL OR VISIBILITY = 'public'";
        $stmt = $this->mysqli->prepare($SQL);
        $stmt->execute();
        if (!$stmt->execute()) {
            printf("error during findAll : %s\n", $stmt->error);
            exit();
        }
        $result = $stmt->get_result();
        
        $events = array();
        if ($result->num_rows) {
            $events = array($stmt->num_rows);
            $i = 0;
            while ($row = $result->fetch_assoc()) {
                $events[$i++] = $this->createEventFromRow($row);
            }
            $result->close();
            $stmt->close();
        }
        return $events;
        
    }
    
    
    public function findByOrigCalUID($iCalUID) {
        $SQL = "SELECT  
            ID, $this->FIELDS 
            FROM EVENTS WHERE ICALUID = ?";
        $stmt = $this->mysqli->prepare($SQL);
        $stmt->bind_param("s", $iCalUID);
        $stmt->execute();
        if (!$stmt->execute()) {
            printf("error during findByOrigCalUID event: %s, %s\n", $iCalUID,  $stmt->error);
            exit();
        }
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        $event = null;
        if ($row) {
            $event = $this->createEventFromRow($row);
        }
        $result->close();
        $stmt->close();
        return $event;
    }
    
    private function createEventFromRow($row) {
        $event = new Event();
        $event->id = $row["ID"];
        $event->iCalUID = $row["ICALUID"];
        $event->startDate = $row["STARTDATE"];
        $event->startDateTime = $row["STARTDATETIME"];
        $event->summary = $row["SUMMARY"];
        $event->location = $row["LOCATION"];
        $event->facebookLink = $row["FACEBOOKLINK"];
        $event->ticketsLink = $row["TICKETSLINK"];
        $event->flyerUrl = $row["FLYERURL"];
        $event->visibility = $row["VISIBILITY"];
        $event->created = $row["CREATED"];
        $event->updated = $row["UPDATED"];
        $event->creator = $row["CREATOR"];
        return $event;
    }
        
}
?>
