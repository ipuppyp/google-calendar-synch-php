<!DOCTYPE html>
<html lang="en"> 
<head>
	<meta charset="utf-8"/>
</head>

<body>
<?php 
ini_set("log_errors", 1);
ini_set("error_log", "php-error.log");

require_once __DIR__ . '/autoload.php';

$service = new CalendarCrudService('ipuppyp/google-calendar-sync', 
        '.store/google-calendar-synch-credentials.json', 
        '.store/google-calendar-synch.token.json');

$eventDao = new EventDao($mysqli);

$calendar = $service->findCalendarByName('APACUKA_RSS');
$googleEvents = $service->findEventsByCalendar($calendar)->getItems();
$events = $eventDao->findByCalendarStartGreaterThan(date("Y-m-d H:i:s"));

$updated = 0;
$inserted = 0;
$removed = 0;

foreach ($googleEvents as $gooleEvent ) {
    $event = $eventDao->findByOrigCalUID($gooleEvent->getICalUID());
    if ($event == null) {
        $event = new Event();
        $event->calendarICalUID = $gooleEvent->getICalUID();
        transformGoogleEventToEvent($gooleEvent, $event);
        $eventDao->insert($event);
        $inserted++;
    }
    
    if (changed($gooleEvent, $event) ) {
        transformGoogleEventToEvent($gooleEvent, $event);        
        $eventDao->update($event);
        $updated++;
    }    
}


foreach ($events as $event) {     
    if (!contains($googleEvents, $event)) {
        $eventDao->delete($event);
        $removed++;
    }
}

echo "Inserted: $inserted, Updated: $updated, removed: $removed.\n";

function contains($googleEvents, $event) {
    foreach ($googleEvents as $gooleEvent) {
        if ($gooleEvent->$gooleEvent->getICalUID() == $event->calendarICalUID) {
            return true;
        }        
    }
    return false;
}
    


function transformGoogleEventToEvent($gooleEvent, $event) {
    $event->calendarSummary = $gooleEvent->getSummary();    
    $event->calendarStart = $gooleEvent->getStart()->getDateTime();
    $event->calendarLocation = $gooleEvent->getLocation();
    
}


function changed($gooleEvent, $event) {
    return  $event->calendarSummary != $gooleEvent->getSummary() ||
            $event->calendarStart != $gooleEvent->getStart()->getDateTime() ||
            $event->calendarLocation = $gooleEvent->getLocation();
}


?>
</body>