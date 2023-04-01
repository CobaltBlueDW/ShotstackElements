<?php
require_once(__DIR__ . '/../../../lib/framework/setup.php');

$parser = new \src\lib\transforms\ElementsToTimeline();

$template = $parser->execute([
    "inPath" => __DIR__ . '/unit_in2.json',
    "outPath" => __DIR__ . '/unit_out2.json',
]);