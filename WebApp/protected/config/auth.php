<?php
return array(
    'guest' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Guest',
        'bizRule' => null,
        'data' => null
    ),
    'user' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'User',
        'children' => array(
        'guest', // унаследуемся от гостя
        ),
        'bizRule' => null,
        'data' => null
    ),
    'student' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Student',
        'children' => array(
            'user',
        ),
        'bizRule' => null,
        'data' => null
    ),
    'teacher' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Teacher',
        'children' => array(
            'student',
        ),
        'bizRule' => null,
        'data' => null
    ),
    'moderator' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Moderator',
        'children' => array(
        'teacher',          // позволим модератору всё, что позволено пользователю
        ),
        'bizRule' => null,
        'data' => null
    ),
    'administrator' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Administrator',
        'children' => array(
        'moderator',         // позволим админу всё, что позволено модератору
        ),
        'bizRule' => null,
        'data' => null
        ),
    );
?>