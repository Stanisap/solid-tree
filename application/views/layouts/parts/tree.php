<?php

/**
 * The function builds the tree as nested lists.
 * @param array $tree the array was built in the model Tree
 * @param int $parent_id the id of a parent those children
 * @return bool|string a part of the html document where is built the hierarchy of this tree
 */
function buildTree($tree, $parent_id = 0)
{
    if (is_array($tree) && isset($tree[$parent_id])) {
        $result = '<ul class="list-group">';
        foreach ($tree[$parent_id] as $item) {
            $spanClass = '';
            $showChildren = '';
            if ($item['is_child']) {
                if (isset($tree[$item['id']])) {
                    $spanClass = 'oi oi-caret-bottom';
                    $showChildren = "onclick='hideChildren({$item['id']}, {$item['parent_id']})';";
                } else {
                    $spanClass = 'oi oi-caret-right';
                    $showChildren = "onclick='showChildren({$item['id']}, {$item['parent_id']})';";
                }
            }
            $result .= "<li class=\"list-group-item\" id=\"item{$item['id']}\">
                            <div class=\"row justify-content-start border border-dark rounded\" style='width: 200px'>
                                <div class=\"col-auto\">
                                    <span class='$spanClass' $showChildren></span>
                                    <div onclick='renameNode({$item['id']})' class='d-inline-block' data-toggle=\"modal\" data-target=\"#renameModal\" data-whatever='id:{$item['id']}'>{$item['title']}</div>
                                </div>
                                <div class=\"col-sm-1\">
                                    <button onclick='addRoot({$item['id']})' class=\"btn btn-success btn-sm\"><span class=\"oi oi-plus\"></span></button>
                                </div>
                                <div class=\"col-sm-1\">
                                        <button onclick='removeRoot({$item['id']}, {$item['parent_id']})' type=\"submit\" class=\"btn btn-danger btn-sm\" data-toggle=\"modal\" data-target=\"#deleteModal\"><span class=\"oi oi-minus\"></span></button>
                                    </form>
                                </div>
                            </div>";
            $result .= buildTree($tree, $item['id']);
            $result .= '</li>';
        }
        $result .= '</ul>';
    } else {
        return false;
    }

    return $result;

}
$parent_id = key($data);

echo buildTree($data, $parent_id);
