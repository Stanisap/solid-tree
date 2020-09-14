<?php


namespace application\models;


use application\core\Model;

/**
 * Class Tree
 * @package application\models
 */
class Tree extends Model
{
    /**
     * Required fields in the model
     * @var array that contains a field's name in the table
     */
    protected $fields = ['title', 'parent_id'];

    /**
     * Gets data for sending to the controller
     * @return array|bool the sorted array or false if the passed argument isn't an array
     */
    public function getData() {
        $data = $this->db->getAll('tree');

        return $this->formTree($data);
    }

    /**
     * Returns an sorted array of data to display the tree hierarchy
     * @param array $data the array will be sorted for showing the tree
     * @return array|bool the sorted array or false if the passed argument isn't an array
     */
    private function formTree($data)
    {
        if (!is_array($data)) return false;
        $tree = [];
        foreach ($data as $value) {
            $tree[$value['parent_id']][] = $value;
        }

        return $tree;
    }

    /**
     * Sets a new data in a table of the database
     * @param array $data the data to set to the model
     */
    public function setData($data) {
        if ($this->isInData($data) && $this->checkData($data)) {
            $this->db->query($this->db->queryToAdd('tree', $data), $data);
        }
    }

    /**
     * Removes data by transferred the id number
     * @param int $id the tree note id
     * @return bool to exit the recursive method
     */
    public function deleteBranch($id)
    {
        $data = $this->getData();
        $this->db->deleteById('tree', $id);
        if (isset($data[$id])) {
            foreach ($data[$id] as $item) {
                if ($id == $item['parent_id']) {

                    $this->deleteBranch($item['id']);
                }
            }
        } else {
            return false;
        }
    }

}