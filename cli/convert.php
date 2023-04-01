<?php
require_once(__DIR__ . '/../src/lib/framework/setup.php');

use src\lib\transforms\ElementsToTimeLine;

if (php_sapi_name() !== 'cli') {
    exit;
}

$options = getopt('', ['from:', 'to:', 'config::']);

if (empty($options['from']) || empty($options['to'])) {
    echo 'Usage: php convert.php --from=from --to=to [--config=config]' . PHP_EOL;
    exit;
}

$converter = new ElementsToTimeLine();
if (!empty($options['config'])) {
    $converter->setAll(\json_decode(\file_get_contents($options['config'])));
}

$converter->execute([
    "inPath" => $options['from'],
    "outPath" => $options['to'],
]);