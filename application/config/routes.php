<?php
/*
| -------------------------------------------------------------------------------------
|  url routes
|--------------------------------------------------------------------------------------
|   Here is where you can register routes for the application and add a controller's name
| and an action's name which will rules by next actions for viewing the content.
| The associative array key is a human-readable url without a domain name.
| This key contains an associative array with the keys "controller" and "action",
| in this case we set the name of the child class of the controller and the method
| that will implement the logic
|--------------------------------------------------------------------------------------
*/
return [
    '' => [
        'controller' => 'main',
        'action' => 'index',
        'model' => 'tree',
    ],

    'add' => [
        'controller' => 'main',
        'action' => 'add',
        'model' => 'tree',
    ],

    'delete' => [
        'controller' => 'main',
        'action' => 'delete',
        'model' => 'tree',
    ],

];