<?php

global $DI;

$DI = new \src\lib\framework\di\DIGlobal();

//Example
//$DI->addDefinition('openai_Network', function(){ return new \src\lib\openai\Network(); });