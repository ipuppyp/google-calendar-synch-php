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
$events = $service->findEventsByCalendar($calendar);


$i = 0;
$eventDao->deleteAll();
foreach ($events->getItems() as $calEvent ) {
    $i++;
    $event = new Event();     
    $event->setSummary($calEvent->getSummary());
    $event->setCalendarOrigICalUID($calEvent->getICalUID());
    $event->setCalendarStart($calEvent->getStart());
    $event->setLocation($calEvent->getLocation());
    echo "$i: ";
    echo $event->toString();
    $eventDao->insert($event);
    echo "<br>\n";
}





?>
</body>