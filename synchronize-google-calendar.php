
<?php
ini_set("log_errors", 1);
ini_set("error_log", "php-error.log");

echo "Synchronizing events...\n";

require_once __DIR__ . '/autoload.php';

ini_set('default_charset', 'utf-8');

$service = new CalendarCrudService('ipuppyp/google-calendar-sync', '.store/google-calendar-synch-credentials.json', '.store/google-calendar-synch.token.json');

$eventDao = new EventDao($mysqli);

$calendar = $service->findCalendarByName('test-from');
$googleEvents = $service->findEventsByCalendar($calendar)->getItems();
$events = $eventDao->findFutureImportedEvents();

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
        echo "szar." + $event;
        $eventDao->delete($event);
        $removed ++;
    }
}

echo "Inserted: $inserted, Updated: $updated, removed: $removed.\n";



function contains($googleEvents, $event)
{
    foreach ($googleEvents as $googleEvent) {
        if ($googleEvent->iCalUID == $event->iCalUID) {
            return true;
        }
    }
    return false;
}

function transformGoogleEventToEvent(Google_Service_Calendar_Event $googleEvent, Event $event)
{
    $description =  $googleEvent->description;
    
    $event->startDate = $googleEvent->getStart()->date;
    $event->startDateTime = $googleEvent->getStart()->dateTime;
    $event->summary = $googleEvent->summary;
    $event->location = $googleEvent->location;
    $event->facebookLink = extractInfo($description, "facebook");
    $event->ticketsLink = extractInfo($description, "tickets");
    $event->flyerUrl = extractInfo($description, "flyer");
    $event->visibility = $googleEvent->visibility;
    $event->sequence = $googleEvent->sequence;
    $event->created = $googleEvent->created;
    $event->updated = $googleEvent->updated;
    $event->creator = $googleEvent->getCreator()->email;
}

function extractInfo($description, $data)
{
    $description=str_replace("<br>", "\n", strip_tags ($description,"<br>"));
    
    $matches = array();
    preg_match_all("/^$data\s*:\s*(.*)$/m",$description,$matches);
    return $matches[1] ? trim($matches[1][0]) : null;
}

function changed($gooleEvent, $event)
{
    return $event->updated != date("Y-m-d h:i:s", strtotime($gooleEvent->updated));
}

echo "Synchronizing events DONE.";
?>
