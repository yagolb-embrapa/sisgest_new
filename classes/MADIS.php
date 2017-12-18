<?php

class MADIS extends BaseCatracas {


    protected $fields = array('null1'  => 9,
                              'id'     => 6,
                              'date'   => 6,
                              'time'   => 4,
                              'io'     => 2,
                              'null2'  => 5);

    public function __construct($str = null) {
        parent::__construct($str);
    }



    protected function set_data($data) {
        list($null, $this->id, $this->date, $this->time, $this->io, $this->reason, $null) = $data;
    }

}

?>
