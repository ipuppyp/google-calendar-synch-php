<?php


if (!function_exists('curl_reset'))
{
    function curl_reset(&$ch)
    {
        $ch = curl_init();
    }
}

class CalendarCrudService {
    private $service;
    

    public function __construct($applicationName, $credentials, $tokenPath) {
        $this->init($applicationName, $credentials, $tokenPath);
        
    }
    
    public function findCalendarByName($name) {
        $service = $this->service;
        $calendarList = $service->calendarList->listCalendarList();
        
        foreach ($calendarList->getItems() as $calendarListEntry) {
            if ($calendarListEntry->getSummary() == $name) {
                $cal = new Google_Service_Calendar_Calendar();
                $cal->setId($calendarListEntry->getId());
                return $cal;
            }
        }
    }

    public function findEventsByCalendar(Google_Service_Calendar_Calendar $calendar) {
        $calendarId = $calendar->getId();
        
        $optParams = array(
            'maxResults' => 100,
            'orderBy' => 'startTime',
            'singleEvents' => true,
            'timeMin' => date('c'),
        );
        
        $events = $this->service->events->listEvents($calendarId, $optParams);
        return $events;
    }
    
    function init($applicationName, $credentials, $tokenPath)
    {
        $client = new Google_Client();
        $client->setApplicationName($applicationName);
        $client->setScopes(Google_Service_Calendar::CALENDAR_READONLY);
        $client->setAuthConfig($credentials);
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);
        }
        
        if ($client->isAccessTokenExpired()) {
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                $authUrl = $client->createAuthUrl();
                printf("Open the following link in your browser:\n%s\n", $authUrl);
                print 'Enter verification code: ';
                $authCode = trim(fgets(STDIN));
                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                $client->setAccessToken($accessToken);
                
                if (array_key_exists('error', $accessToken)) {
                    throw new Exception(join(', ', $accessToken));
                }
            }
            
            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }
        $this->service = new Google_Service_Calendar($client);
    }
} 

?>