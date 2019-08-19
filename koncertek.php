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


$eventDao = new EventDao($mysqli);

$events = $eventDao->findAll();

foreach ($events as $event) {
    
    $start = $event->startDate == null ? $event->startDateTime : $event->startDate;
    $facebookLink = $event->facebookLink ? "<a href=\"$event->facebookLink\" target=\"_blank\">facebookLink</a>" : "";
    $ticketsLink = $event->ticketsLink ? "<a href=\"$event->ticketsLink\" target=\"_blank\">Jegyváráslás</a>" : "";
    $flyerURL = $event->flyerUrl ? "<img src=\"$event->flyerUrl\"><br>\n" : "";
    
    
    
    
    printf("%s, %s, %s %s %s %s<br>\n", 
            $start,
            $event->summary, 
            $event->location, 
            $facebookLink, 
            $ticketsLink, 
            $flyerURL);
}


?>
</body>