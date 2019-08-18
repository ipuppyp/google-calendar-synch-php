<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
</head>

<body>
<?php
ini_set("log_errors", 1);
ini_set("error_log", "php-error.log");

require_once __DIR__ . '/autoload.php';

ini_set('default_charset', 'utf-8');

$service = new CalendarCrudService('ipuppyp/google-calendar-sync', '.store/google-calendar-synch-credentials.json', '.store/google-calendar-synch.token.json');

$eventDao = new EventDao($mysqli);

$calendar = $service->findCalendarByName('test-from');
$googleEvents = $service->findEventsByCalendar($calendar)->getItems();
$events = $eventDao->findFutureEvents();

$updated = 0;
$inserted = 0;
$removed = 0;

foreach ($googleEvents as $gooleEvent) {
    $event = $eventDao->findByOrigCalUID($gooleEvent->iCalUID);
    if ($event == null) {
        $event = new Event();
        $event->iCalUID = $gooleEvent->iCalUID;
        transformGoogleEventToEvent($gooleEvent, $event);
        $eventDao->insert($event);
        $inserted ++;
    } 
    else if (changed($gooleEvent, $event)) {
        transformGoogleEventToEvent($gooleEvent, $event);
        $eventDao->update($event);
        $updated ++;
    }
}

foreach ($events as $event) {
    if (!contains($googleEvents, $event)) {
        $eventDao->delete($event);
        $removed ++;
    }
}

echo "Inserted: $inserted, Updated: $updated, removed: $removed.\n";

$mysqli.close();

function contains($googleEvents, $event)
{
    foreach ($googleEvents as $gooleEvent) {
        if ($gooleEvent->getICalUID() == $event->iCalUID) {
            return true;
        }
    }
    return false;
}

function transformGoogleEventToEvent($gooleEvent, $event)
{
    $event->startDate = $gooleEvent->getStart()->date;
    $event->startDateTime = $gooleEvent->getStart()->dateTime;
    $event->summary = $gooleEvent->summary;
    $event->location = $gooleEvent->location;
    $event->facebookLink = "facebooklink";
    $event->ticketPurchaseLink = "ticket purchase url";
    $event->flyerUrl = "fyler URL";
    $event->visibility = $gooleEvent->visibility;
    $event->sequence = $gooleEvent->sequence;
    $event->created = $gooleEvent->created;
    $event->updated = $gooleEvent->updated;
    $event->creator = $gooleEvent->getCreator()->email;
}

function getFacebookLink($description) {
    $stripped = strip_tags($description);
    $matches = array();
    $s = preg_match('^.*?\bfacebook:\b([^$]*)$', $stripped, $matches);
    print_r($matches[1]);
    
    
}

function changed($gooleEvent, $event)
{
    $googleEventUpdated = date("Y-m-d h:i:s", strtotime($gooleEvent->updated));
    return $event->updated != $googleEventUpdated;
}

?>
</body>