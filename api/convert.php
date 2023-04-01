<?php
require_once(__DIR__ . '/../src/lib/framework/setup.php');

use src\lib\transforms\ElementsToTimeLine;

ob_start(); // Prevent output
try {

    $params = \json_decode(file_get_contents('php://input'), true, 512, \JSON_THROW_ON_ERROR);

    if (empty($params['template'])){
        throw new \Exception('Template is empty');
    }

    $converter = new ElementsToTimeLine();
    if (!empty($params['config'])) {
        $converter->setAll($params['config']);
    }

    $result = $converter->execute([
        "inputTemplate" => $params['template']
    ]);

    ob_end_clean();
    echo \json_encode([
        "success" => true,
        "template" => $result
    ]);
    exit;

} catch (\Exception $e) {
    ob_end_clean();
    echo \json_encode([
        "success" => false,
        "error" => $e->getMessage()
    ]);
    exit;
}