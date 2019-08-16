<?php

class EventDao {
    private $mysqli;
    
    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
        
    }
    
    public function insert(Event $event) {
        $INSERT_SQL = "INSERT INTO events (CALENDARORIGICALUID, CALENDARSTART, CALENDARLOCATION, CREATED, UPDATED) 
                        VALUES (?, ?, ?, ?, ?)";
        $now = date("Y-m-d H:i:s");
        $now2 = date("Y-m-d H:i:s");
        $stmt = $this->mysqli->prepare($INSERT_SQL);
        //$stmt->bind_param($event->getCalendarOrigICalUID(), $event->getCalendarStart(), $event->getCalendarLocation(), $now, $now2);
        echo "saved\n";
        $stmt->execute();
        $this->mysqli->commit();
        
    }
    
    public function deleteAll() {
        $DELETE_ALL_SQL = "delete from Events";
        $this->mysqli->query($DELETE_ALL_SQL);        
    }
    
    
    
}
?>
