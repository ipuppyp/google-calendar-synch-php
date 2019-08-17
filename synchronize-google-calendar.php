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

ini_set('default_charset', 'utf-8');

$service = new CalendarCrudService('ipuppyp/google-calendar-sync', 
        '.store/google-calendar-synch-credentials.json', 
        '.store/google-calendar-synch.token.json');

$eventDao = new EventDao($mysqli);

$calendar = $service->findCalendarByName('test-from');
$googleEvents = $service->findEventsByCalendar($calendar)->getItems();
$events = $eventDao->findByCalendarStartGreaterOrEqualThan(date("Y-m-d H:i:s"));

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
    
    else if (changed($gooleEvent, $event) ) {
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
        if ($gooleEvent->getICalUID() == $event->calendarICalUID) {
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
    
    $googleDateTime = $gooleEvent->getStart()->getDateTime();
    $googleEventDate = $googleDateTime ? date("Y-m-d H:i:s", strtotime($googleDateTime)) : "";
    return   $event->calendarSummary != $gooleEvent->getSummary() ||
                $event->calendarStart != $googleEventDate ||
                $event->calendarLocation != $gooleEvent->getLocation();

}


?>
</body>