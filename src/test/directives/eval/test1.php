<?php
require_once(__DIR__ . '/../../../lib/framework/setup.php');

$parser = new \src\lib\transforms\ElementsToTimeline();

$template = $parser->execute([
    "inPath" => __DIR__ . '/eval_in1.json',
    "outPath" => __DIR__ . '/eval_out1.json',
]);