<?php

class Event
{

    public $id;
    
    public $calendarICalUID;
    
    public $calendarStart;

    public $calendarSummary;

    public $summary;

    public $calendarLocation;

    public $location;

    public $changed;

    public $visible;

    public $created;

    public $updated;

    public $facebookLink;

    public $ticketPurchaseLink;
    
    public function toString()
    {
        return 'id = ' .	$this->id .
        'calendarICalUID = ' .	$this->calendarICalUID .
        ', calendarStart = ' .	$this->calendarStart .
        ', calendarSummary = ' .	$this->calendarSummary .
        ', summary = ' .	$this->summary .
        ', calendarLocation = ' .	$this->calendarLocation .
        ', location = ' .	$this->location .
        ', changed = ' .	$this->changed .
        ', visible = ' .	$this->visible .
        ', created = ' .	$this->created .
        ', updated = ' .	$this->updated .
        ', facebookLink = ' .	$this->facebookLink .
        'ticketPurchaseLink = ' .	$this->ticketPurchaseLink;
    }
     

}
?>