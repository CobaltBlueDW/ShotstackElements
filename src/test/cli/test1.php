<?php
require_once(__DIR__ . '/../../lib/framework/setup.php');

$scriptPath = __DIR__ . '/../../../cli/convert.php';
$inPath = __DIR__ . '/calc_in1.json';
$outPath = __DIR__ . '/calc_out1.json';
passthru("php {$scriptPath} --from=\"{$inPath}\" --to=\"{$outPath}\" ");