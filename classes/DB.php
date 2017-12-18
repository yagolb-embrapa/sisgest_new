<?php

class DB {

    private static $connection = null;
    private static $parameters = array('host'     => 'localhost',
                                       'port'     => '5432',
                                       'dbname'   => 'sisgest',
                                       'user'     => 'sisgest',
                                       'password' => 'sisgest');
    private static $resultset = null;


    public static function set_parameters($parameters) {
        DB::$parameters = array_merge(DB::$parameters, $parameters);
    }



    public static function get_parameter($parameter) {
        return DB::$parameters[$parameter];
    }



    public static function get_parameters() {
        return DB::$parameters;
    }



    private static function set_connection($connection) {
        DB::$connection = is_resource($connection) ? $connection : null;
    }



    private static function get_connection() {
        return DB::$connection;
    }



    public static function is_connected() {
        return is_resource(DB::get_connection()) ? true : false;
    }



    public static function get_connection_string() {
        $connection_string = array();

        foreach(DB::get_parameters() as $param => $value)
            $connection_string[] = "{$param}={$value}";

        return implode(' ', $connection_string);
    }



    private static function set_resultset($resultset) {
        DB::$resultset = is_resource($resultset) ? $resultset : null;
    }



    private static function get_resultset() {
        return DB::$resultset;
    }



    public static function connect() {
        if(!DB::is_connected()) {
            $connection = @pg_connect(DB::get_connection_string(), PGSQL_CONNECT_FORCE_NEW);

            if(false !== $connection) {
                DB::set_connection($connection);

                return true;
            } else {
                DB::set_connection(null);

                return false;
            }
        } else {
            return true;
        }
    }



    public static function close() {
        if(DB::is_connected()) {
            $closed = @pg_close(DB::get_connection());
            DB::free_resultset(DB::get_resultset());
            DB::set_connection(null);
            DB::set_resultset(null);

            return $closed;
        } else {
            return false;
        }
    }



    private static function free_resultset() {
        $free = true;

        if(is_resource(DB::get_resultset())) {
            $free = @pg_free_result(DB::get_resultset());
            DB::set_resultset(null);
        } else {
            DB::set_resultset(null);
        }

        return $free;
    }



    public static function execute($query, $debug = false) {
        if(DB::is_connected()) {
            if(is_resource(DB::get_resultset()))
                DB::free_resultset();

            $resultset = @pg_query(DB::get_connection(), $query);

            if(false === $resultset) {
                if(true === $debug) 
                    echo pg_last_error(DB::get_connection());

                return false;
            } else {
                DB::set_resultset($resultset);
            }
            
            return true;
        } else {
            if(DB::connect())
                return DB::execute($query);
            else 
                return false;
        }
    } 



    public static function fetch($row = null) {
        if(!is_resource(DB::get_resultset()))
            return false;

        $result = pg_fetch_assoc(DB::get_resultset(), $row);

        return $result;
    }



    public static function fetch_all($query = null) {
        $rows = array();

        if(is_null($query)) {
            while($row = DB::fetch())
                $rows[] = $row;
    
            return $rows;
        } else {
            $old_rs = DB::get_resultset();
            DB::execute($query);
            $rows = DB::fetch_all();
            DB::set_resultset($old_rs);

            return $rows;
        }
    }



    public static function num_rows() {
        if(is_resource(DB::get_resultset()))
            return pg_num_rows(DB::get_resultset());
        else
            return -1;
    }

}

?>
