<?php

abstract class BaseCatracas {

    protected $fields = array();



    public function __construct($str = null) {
        if(!is_null($str))
            $this->parse_string($str);
    }



    private function get_regexp() {
        $regexp = '';

        foreach($this->fields as $field => $size)
            $regexp .= "(\\d{{$size}})";

        $regexp = '/^' . $regexp . '$/';

        return $regexp;
    }



    protected function parse_string($str) {
        if(false !== preg_match($this->get_regexp(), $str, $matches))
            $this->set_data(array_slice($matches, 1));
    }



    protected abstract function set_data($data);

}

?>
