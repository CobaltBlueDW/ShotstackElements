<?php

$scene1 = json_decode( file_get_contents('scene1.json') );
$scene2 = json_decode( file_get_contents('scene2.json') );

//preserve scenes
echo json_encode([
    'elements' => [
        $scene1,
        $scene2,
    ]
]);

//or merge scenes
echo json_encode([
    'elements' => [
        ... $scene1['elements'],
        ... $scene2['elements'],
    ]
]);