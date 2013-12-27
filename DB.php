<?php

namespace Inter;

class DB
{
    public $q;
    public $smc;
    protected $methods = ['num_rows', 'affected_rows', 'fetch_row', 'fetch_assoc', 'free'];

    public static function query($sql, array $args = [])
    {
        $db = new self();
        $db->_query($sql, $args);

        return $db;
    }

    public function __construct()
    {
        global $smcFunc;

        $this->smc =& $smcFunc;
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
        if (in_array($key, $this->methods))
            return $this->$key();

        trigger_error('Key not found');
    }
}
