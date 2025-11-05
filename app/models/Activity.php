<?php

class Activity
{
    private $id;
    private $type;
    private $monitor;
    private $place;
    private $date;

    public function __construct($id = null, $type = '', $monitor = '', $place = '', $date = '')
    {
        $this->id = $id;
        $this->type = $type;
        $this->monitor = $monitor;
        $this->place = $place;
        $this->date = $date;
    }



    // Getters
    public function getId(){return $this->id;}

    public function getType(){return $this->type;}

    public function getMonitor(){return $this->monitor;}

    public function getPlace(){return $this->place;}
    
    public function getDate(){return $this->date;}


    // Setters

    public function setId($id){$this->id = $id;}

    public function setType($type){$this->type = $type;}

    public function setMonitor($monitor){$this->monitor = $monitor;}

    public function setPlace($place){$this->place = $place;}

    public function setDate($date){$this->date = $date;}





    public function toArray()
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'monitor' => $this->monitor,
            'place' => $this->place,
            'date' => $this->date
        ];
    }
}
?>