<?php
require_once(__DIR__ . '/../../lib/framework/setup.php');

$parser = new \src\lib\transforms\ElementsToTimeline();

$template = $parser->execute([
    "inPath" => __DIR__ . '/first_in.json',
    "outPath" => __DIR__ . '/first_out.json',
]);