<?php

class EventDao {
    private $mysqli;
    
    private $FIELDS = " ICALUID,
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
                        CREATOR";
    
    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
    }
    
    public function insert(Event $event) {
        $SQL = "INSERT INTO events (
            $this->FIELDS) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->mysqli->prepare($SQL);
        
        $stmt->bind_param("sssssssssssss", 
            $event->iCalUID,
            $event->startDate,
            $this->createDateTime($event->startDateTime),
            $event->summary,
            $event->location,
            $event->facebookLink,
            $event->ticketPurchaseLink,
            $event->flyerUrl,
            $event->visibility,
            $event->sequence,
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
        echo $value. "\n";
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
            TICKETPURCHASELINK = ?,
            FLYERURL = ?,
            VISIBILITY = ?,
            SEQUENCE = ?,
            CREATED = ?,
            UPDATED = ?,
            CREATOR = ?
            WHERE ICALUID = ?";
        $stmt = $this->mysqli->prepare($SQL);
        $stmt->bind_param("sssssssssssss", 
            $event->startDate,
            $this->createDateTime($event->startDateTime),
            $event->summary,
            $event->location,
            $event->facebookLink,
            $event->ticketPurchaseLink,
            $event->flyerUrl,
            $event->visibility,
            $event->sequence,
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
        $INSERT_SQL = "DELETE FROM events WHERE ICALUID = ?";
        $stmt = $this->mysqli->prepare($INSERT_SQL);
        $stmt->bind_param("s", $event->iCalUID);
        if (!$stmt->execute()) {
            printf("error during delete event: %s, %s\n", $event->calendarSummary,  $stmt->error);
            exit();
        }
        $stmt->close();
    }
   
    public function findFutureEvents() {
        $SQL = "SELECT ICALUID 
                        FROM EVENTS 
                        WHERE STARTDATE >= CURRENT_TIMESTAMP OR STARTDATETIME >= CURRENT_TIMESTAMP";
        $result = $this->mysqli->query($SQL);

        if (!$result) {
            printf("error during findFutureEvents event: %s\n", $result->error);
            exit();
        }
        $events = array($result->num_rows);
        $i = 0;
        while ($row = $result->fetch_assoc()) {
            $event = new Event();
            $event->iCalUID = $row["ICALUID"];
            $events[$i++] = $event;
        }
        $result->close();
        return $events;
    }
    
    public function findAll() {
        $SQL = "SELECT $this->FIELDS FROM EVENTS";
        $stmt = $this->mysqli->prepare($SQL);
        $stmt->execute();
        if (!$stmt->execute()) {
            printf("error during findAll : %s\n", $stmt->error);
            exit();
        }
        $result = $stmt->get_result();
        
        $events = array($stmt->num_rows);
        $i = 0;
        while ($row = $result->fetch_assoc()) {
            $events[$i++] = $this->createEventFromRow($row);
        }
        $result->close();
        $stmt->close();
        return $events;
    }
    
    
    public function findByOrigCalUID($iCalUID) {
        $SQL = "SELECT  
            $this->FIELDS 
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
        $event->iCalUID = $row["ICALUID"];
        $event->startDate = $row["STARTDATE"];
        $event->startDateTime = $row["STARTDATETIME"];
        $event->summary = $row["SUMMARY"];
        $event->location = $row["LOCATION"];
        $event->facebookLink = $row["FACEBOOKLINK"];
        $event->ticketPurchaseLink = $row["TICKETPURCHASELINK"];
        $event->flyerUrl = $row["FLYERURL"];
        $event->visibility = $row["VISIBILITY"];
        $event->sequence = $row["SEQUENCE"];
        $event->created = $row["CREATED"];
        $event->updated = $row["UPDATED"];
        $event->creator = $row["CREATOR"];
        return $event;
    }
        
}
?>
