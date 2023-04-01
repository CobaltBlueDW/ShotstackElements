<?php
require_once(__DIR__ . '/../../lib/framework/setup.php');

$parser = new \src\lib\transforms\ElementsToTimeline();

//$template = \json_decode(\file_get_contents(__DIR__ . '/flat_in1.json', true), true, 1024, \JSON_THROW_ON_ERROR);
//echo \json_encode($template, \JSON_THROW_ON_ERROR | \JSON_PRETTY_PRINT);
//$parser->flattenTemplate($template['elements']);
//echo \json_encode($template, \JSON_THROW_ON_ERROR | \JSON_PRETTY_PRINT);

$template = $parser->execute([
    "inPath" => __DIR__ . '/flat_in1.json',
    "outPath" => __DIR__ . '/flat_out1.json',
]);
