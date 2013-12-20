<?php

namespace Inter;

class DB
{
    public $q;
    public $smc;

    public function __construct()
    {
        global $smcFunc;

        $this->smc =& $smcFunc;
    }

    /**
     * @return array
     */
    public static function get($query, array $args = [], $func = null)
    {
        return self::query($query, $args)->fetch_all($func);
    }

    public static function query($sql, array $args = [])
    {
        $db = new DB();
        $db->_query($sql, $args);

        return $db;
    }

    protected function _query($sql, array $args = [])
    {
        $this->q = $this->smc['db_query']('', $sql, $args);
    }

    public function num_rows()
    {
        return $this->smc['db_num_rows']($this->q);
    }

    public function affected_rows()
    {
        return $this->smc['db_affected_rows']();
    }

    public function fetch_row()
    {
        return $this->smc['db_fetch_row']($this->q);
    }

    public function fetch_assoc()
    {
        return $this->smc['db_fetch_assoc']($this->q);
    }

    public function free()
    {
        return $this->smc['db_free_result']($this->q);
    }

    /**
     * @return array
     */
    public function fetch_all($func = null)
    {
        $ary = [];
        if (!$this->num_rows())
            return $ary;

        if (is_callable($func)) {
            while($row = $this->fetch_assoc())
                $func($ary, $row);
        } else {
            while($row = $this->fetch_assoc())
                $ary[] = $row;
        }
        $this->free();

        return $ary;
    }

    public function __get($key)
    {
        $m = ['num_rows', 'affected_rows', 'fetch_row', 'fetch_assoc', 'free']; 
        if (in_array($key, $m))
            return $this->$key();

        /*
        if (array_key_exists($key, $this->data))
            return $this->data[$key];
        */

        trigger_error('Key not found');
    }
}