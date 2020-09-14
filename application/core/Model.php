<?php
/**
 * The class is a base class of models
 */
namespace application\core;


use application\lib\DB;
use PDO;

class Model
{
    /**
     * Object of database
     * @var DB|PDO|null
     */
    protected $db;

    /**
     * Required fields in the model
     * @var array
     */
    protected $fields;

    /**
     * Model constructor.
     */
    public function __construct()
    {
        $this->db = DB::getInstance();
    }

    /**
     * Returns true if the transmitted data is not empty
     * @param $data
     * @param array $keys
     * @return bool
     */
    protected function isInData($data, $keys = [])
    {
        $count = 0;

        if (!empty($keys)) {
            foreach ($keys as $key) {
                if (isset($data["$key"]) && $data["$key"] == "") {
                    $count++;
                }
            }
        } else {
            foreach ($data as $key => $val) {
                if ($val == "") {
                    $count++;
                }
            }
        }

        if ($count != 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Returns true if the data contains keys that exists this array of $fields
     * @param $data
     * @return bool
     */
    protected function checkData($data)
    {
        $count = 0;
        foreach ($this->fields as $field) {
            if (array_key_exists($field, $data)) {
                $count++;
            }
        }
        if ($count = count($this->fields)) return true;
        return false;
    }
}