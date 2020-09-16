<?php


namespace application\models;


use application\core\Debug;
use application\core\Model;
use Cassandra\Duration;

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
     * The field initialize in this method getAllChildren and contains all descendants of this node
     * @var array
     */
    private $children = [];

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

        if (isset($data['parent_id']) && isset($data['is_child'])) {
            $this->db->updateFields('tree', ['is_child' => 1], ['id' => $data['parent_id']]);
        }
        unset($data['is_child']);
        if ($this->isInData($data) && $this->checkData($data)) {
            $this->db->queryToAdd('tree', $data);
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
        return true;
    }

    /**
     * Changes a field 'hide' in this table and returns all showed nodes
     * @param array $params the request of the method post where contains id this node
     * @return array|bool all showed nodes
     */
    public function showChildren($params)
    {
        $children = $this->getAllChildren($this->getData(), $params['id']);
        $this->db->updateFieldsIn(
            'tree',
            ['hide' => "0"],
            ['id' => $children]
        );

        return $this->getAllNotHideChildren();
    }

    /**
     * Changes a field 'hide' in this table and returns all showed nodes
     * @param array $params the request of the method post where contains id this node
     * @return array|bool all showed nodes
     */
    public function hideChildren($params)
    {
        $children = $this->getAllChildren($this->getData(), $params['id']);
        $this->db->updateFieldsIn(
            'tree',
            ['hide' => 1],
            ['id' => $children]
        );

        return $this->getAllNotHideChildren();
    }

    /**
     * Renames a field 'title' in the table and returns a new array of the tree,
     * where all nodes is showing
     * @param $params
     * @return array|bool
     */
    public function renameNode($params)
    {
        $this->db->updateFields(
            'tree',
            ['title' => $params['title']],
            ['id' => $params['id']]
        );
        return $this->getAllNotHideChildren();
    }

    /**
     * Returns new array the tree, where all nodes is showing
     * @return array|bool a new array of the tree
     */
    public function getAllNotHideChildren() {
        $data = $this->db->getAllRowByCondition('tree', ['hide' => "0"], '=');
        return $this->formTree($data);
    }

    /**
     * Updates field 'is_child' in the table to 0, indicating that this node no longer has children
     * @param int $id ID of a node that no has children now
     */
    public function notChildren($id) {
        $this->db->updateFields(
            'tree',
            ['is_child' => "0"],
            ['id' => $id]
        );
    }

    /**
     * Returns initialized $this->children. The array contains all hierarchy of the parent.
     * @param array $tree the array contains the tree
     * @param int $parent_id ID parent of the node
     * @param array $children the array exists to save a list of children in the recursive method
     * @return array|bool this privet property
     */
    private function getAllChildren($tree, $parent_id, $children = [])
    {

        if (isset($tree[$parent_id])) {
            $this->children = $children;
            foreach ($tree[$parent_id] as $item) {

                array_push($children, $item['id']);

                foreach ($children as $child) {
                    if (!in_array($child, $this->children)) {
                        array_push($this->children, $child);
                    }
                }

                $this->getAllChildren($tree, $item['id'], $children);
            }
        } else {
            return false;
        }
        sort($this->children);
        return $this->children;
    }

}