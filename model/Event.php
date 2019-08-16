<?php

class Event
{

    private $id;
    
    private $calendarOrigICalUID;
    
    private $calendarStart;

    private $calendarSummary;

    private $summary;

    private $calendarLocation;

    private $location;

    private $changed;

    private $visible;

    private $created;

    private $updated;

    private $facebookLink;

    private $ticketPurchaseLink;
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getCalendarOrigICalUID()
    {
        return $this->calendarOrigICalUID;
    }

    /**
     * @return mixed
     */
    public function getCalendarStart()
    {
        return $this->calendarStart;
    }

    /**
     * @return mixed
     */
    public function getCalendarSummary()
    {
        return $this->calendarSummary;
    }

    /**
     * @return mixed
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @return mixed
     */
    public function getCalendarLocation()
    {
        return $this->calendarLocation;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @return mixed
     */
    public function getChanged()
    {
        return $this->changed;
    }

    /**
     * @return mixed
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @return mixed
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @return mixed
     */
    public function getFacebookLink()
    {
        return $this->facebookLink;
    }

    /**
     * @return mixed
     */
    public function getTicketPurchaseLink()
    {
        return $this->ticketPurchaseLink;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param mixed $calendarOrigICalUID
     */
    public function setCalendarOrigICalUID($calendarOrigICalUID)
    {
        $this->calendarOrigICalUID = $calendarOrigICalUID;
    }

    /**
     * @param mixed $calendarStart
     */
    public function setCalendarStart($calendarStart)
    {
        $this->calendarStart = $calendarStart;
    }

    /**
     * @param mixed $calendarSummary
     */
    public function setCalendarSummary($calendarSummary)
    {
        $this->calendarSummary = $calendarSummary;
    }

    /**
     * @param mixed $summary
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
    }

    /**
     * @param mixed $calendarLocation
     */
    public function setCalendarLocation($calendarLocation)
    {
        $this->calendarLocation = $calendarLocation;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @param mixed $changed
     */
    public function setChanged($changed)
    {
        $this->changed = $changed;
    }

    /**
     * @param mixed $visible
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;
    }

    /**
     * @param mixed $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @param mixed $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * @param mixed $facebookLink
     */
    public function setFacebookLink($facebookLink)
    {
        $this->facebookLink = $facebookLink;
    }

    /**
     * @param mixed $ticketPurchaseLink
     */
    public function setTicketPurchaseLink($ticketPurchaseLink)
    {
        $this->ticketPurchaseLink = $ticketPurchaseLink;
    }

    
    public function toString()
    {
        return 'id = ' .	$this->id .
        'calendarOrigICalUID = ' .	$this->calendarOrigICalUID .
        //'calendarStart = ' .	$this->calendarStart .
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