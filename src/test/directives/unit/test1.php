<?php
require_once(__DIR__ . '/../../../lib/framework/setup.php');

$parser = new \src\lib\transforms\ElementsToTimeline();

$template = $parser->execute([
    "inPath" => __DIR__ . '/unit_in1.json',
    "outPath" => __DIR__ . '/unit_out1.json',
]);