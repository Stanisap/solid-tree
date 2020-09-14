<?php
function buildTree($tree, $parent_id = 0)
{
    if (is_array($tree) && isset($tree[$parent_id])) {
        $result = '<ul class="list-group">';
        foreach ($tree[$parent_id] as $item) {
            $spanClass = isset($tree[$item['id']]) ? 'oi oi-caret-bottom' : 'oi oi-caret-right';
            $result .= "<li class=\"list-group-item\">
                            <div class=\"row justify-content-start border border-dark rounded\" style='width: 200px'>
                                <div class=\"col-auto\">
                                    <span class='$spanClass'></span>
                                    {$item['title']}
                                </div>
                                <div class=\"col-sm-1\">
                                    <button onclick='addRoot(this, {$item['id']})' class=\"btn btn-success btn-sm\"><span class=\"oi oi-plus\"></span></button>
                                </div>
                                <div class=\"col-sm-1\">
                                        <button onclick='removeRoot(this, {$item['id']})' type=\"submit\" class=\"btn btn-danger btn-sm\" data-toggle=\"modal\" data-target=\"#deleteModal\"><span class=\"oi oi-minus\"></span></button>
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

echo buildTree($data, 0);
