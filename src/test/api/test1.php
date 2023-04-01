<?php
require_once(__DIR__ . '/../../lib/framework/setup.php');

$template = \json_decode(\file_get_contents(__DIR__ . '/calc_in1.json'), true, 512, \JSON_THROW_ON_ERROR);
$postdata = \json_encode(['template' => $template]);

$ch = \curl_init("http://127.0.0.1/ShotstackElements/api/convert.php");
\curl_setopt($ch, \CURLOPT_POST, 1);
\curl_setopt($ch, \CURLOPT_POSTFIELDS, $postdata);
\curl_setopt($ch, \CURLOPT_RETURNTRANSFER, 1);
\curl_setopt($ch, \CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
$result = \curl_exec($ch);
\curl_close($ch);

if ($result === false) {
    throw new \Exception("Curl error: " . \curl_error($ch));
}

echo $result;

$result = \json_decode($result, true, 512, \JSON_THROW_ON_ERROR);
if (!$result['success']) {
    throw new \Exception("API error: " . $result['error']);
}

\file_put_contents(__DIR__ . '/calc_out1.json', $result['template']);