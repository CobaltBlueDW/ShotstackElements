<?php

namespace src\lib\transforms;

use src\lib\framework\behaviors\ObjectingInterface;
use src\lib\transforms\directives\Evaluate;
use src\lib\transforms\directives\Json;
use src\lib\transforms\directives\Unit;
use src\lib\transforms\directives\Calc;

class ElementsToTimeLine implements ObjectingInterface{
    use \src\lib\framework\behaviors\ObjectingTrait {
        \src\lib\framework\behaviors\ObjectingTrait::__construct as ObjectingConstruct;
        \src\lib\framework\behaviors\ObjectingTrait::setAll as ObjectingSetAll;
    }
    
    const OUTPUT_RESOLUTIONS = [
        "preview" => [
            "x" => 512,
            "y" => 288,
            "fps" => 15,
        ],
        "mobile" => [
            "x" => 640,
            "y" => 360,
            "fps" => 25,
        ],
        "sd" => [
            "x" => 1024,
            "y" => 576,
            "fps" => 25,
        ],
        "hd" => [
            "x" => 1280,
            "y" => 720,
            "fps" => 25,
        ],
        "1080" => [
            "x" => 1920,
            "y" => 1080,
            "fps" => 25,
        ],
    ];
    
    public $resolution = "preview";
    public $outputConfig = [
        "format" => "mp4",
        "resolution" => "preview",
    ];
    public $callbackConfig = null;
    public $soundtrackConfig = null;
    public $fontConfig = null;

    public $inPath = null;
    public $outPath = null;
    public $inputTemplate = null;

    public $outputTemplate = null;

    public $directives = [];
    public $context = null;

    public function __construct($options = null) {
        $this->directives = [
            new Evaluate(),
            new Json(),
            new Unit(),
            new Calc(),
        ];
        $this->ObjectingConstruct($options);
    }

    public function execute($options = null) {
        if (!empty($options)) {
            $this->setAll($options);
        }
        
        if (!empty($this->inPath)) {
            $this->inputTemplate = \json_decode(\file_get_contents($this->inPath, true), true, 1024, \JSON_THROW_ON_ERROR);
        }
        $this->outputTemplate = [];
        
        //setup context
        $this->context = $this->prepareContext($this->inputTemplate);

        //echo "post context:".\json_encode($this->context)."\n\n";

        //process elements
        $this->processTemplate($this->inputTemplate);

        //echo "post process:".\json_encode($this->inputTemplate, \JSON_PRETTY_PRINT)."\n\n";

        //flatten elements
        $this->flattenTemplate($this->inputTemplate['elements']);

        //echo "post flatten:".\json_encode($this->inputTemplate, \JSON_PRETTY_PRINT)."\n\n";

        $this->prepOutputTemplate($this->outputTemplate, $this->inputTemplate);

        //convert elements to timeline tracks
        $rootscene = null;
        if (isset($this->inputTemplate['timeline']['elements'])) {
            $rootscene = &$this->inputTemplate['timeline']['elements'];
        } else if (isset($this->inputTemplate['elements'])) {
            $rootscene = &$this->inputTemplate['elements'];
        }
        if ($rootscene === null) {
            throw new \Exception("No root scene found");
        }
        $this->outputTemplate['timeline']['tracks'] = $this->convertToTracks($rootscene);

        //echo "post convert:".\json_encode($this->outputTemplate)."\n\n";
        
        $this->outputTemplate = \json_encode($this->outputTemplate, \JSON_THROW_ON_ERROR | \JSON_PRETTY_PRINT);
        if (!empty($this->outPath)) {
            \file_put_contents($this->outPath, $this->outputTemplate);
        }
        
        return $this->outputTemplate;
    }
    
    public function prepareContext(&$template){
        $system = new \src\lib\transforms\contexts\TemplateSystem();
        $global = new \src\lib\transforms\contexts\TemplateGlobal($template);
        $envy = new \src\lib\transforms\contexts\TemplateEnvironment([
            "system" => $system,
            "global" => $global,
            "scene" => $global,
        ]);

        //echo "pre context:".\json_encode($envy->toObject())."\n\n";
        
        return $envy;
    }

    public function processTemplate(&$template){
        if (!is_array($template)) {
            $template = (array)$template;
        }

        $this->context->system->duration = 0;
        
        $path = [];
        $depth = 0;
        //$count = 0;
        $arrayIterator = new \RecursiveArrayIterator($template);
        $recursiveIterator = new \RecursiveIteratorIterator($arrayIterator, \RecursiveIteratorIterator::SELF_FIRST);
        foreach ($recursiveIterator as $key => $value) {

            // prevent infinite loops
            //$count++;
            //if ($count > 100) {
            //    break;
            //}

            $curIt = $recursiveIterator->getSubIterator();
            if ($curIt->offsetExists("elements")) {
                $this->context->scene = $curIt->getArrayCopy();
            }

            // we only work with arrays in here
            if (is_object($value)) {
                $value = (array)$value;
            }

            // update our path, context, etc.
            if ($recursiveIterator->getDepth() < $depth) {
                $path = array_slice($path, 0, $recursiveIterator->getDepth());
            } else {
                $depth = $recursiveIterator->getDepth();
            }
            
            if (is_array($value)) {
                $path []= $key;

                // we need to change all arrays into RecursiveArrayIterators or else we directives can't modify the values
                $curIt->offsetSet($key, new \RecursiveArrayIterator($value));
                continue;
            }

            //echo "before($key):".\json_encode($value)."\n\n";

            // find and apply directives
            if ($this->applyDirective($value, [...$path, $key], $template)){
                echo "after($key):".\json_encode($value)."\n\n";

                if (is_array($value)) {
                    $curIt->offsetSet($key, new \RecursiveArrayIterator($value));
                } else {
                    $curIt->offsetSet($key, $value);
                }

                //echo "template after:".\json_encode($recursiveIterator->getArrayCopy(), \JSON_THROW_ON_ERROR | \JSON_PRETTY_PRINT)."\n\n";
                
                // there isn't a great way to just go back one item in the iterator, and we need to reprocess the current item, so rewind the whole iterator :/
                $recursiveIterator->rewind();
                continue;
            }

            if ($curIt->offsetExists("start") && $curIt->offsetExists("length")) {
                $end = floatval($curIt->offsetGet("start")) + floatval($curIt->offsetGet("length"));
                $this->context->system->duration = max($this->context->system->duration, $end);
            }

        }

        $template = $recursiveIterator->getArrayCopy();

        //echo "template end:".\json_encode($template)."\n\n";
    }

    public function applyDirective(&$value, $path, $template):bool {
        if (!is_string($value)) {
            return false;
        }
        foreach($this->directives as $directive){
            if ($directive->found($value)) {
                $directive->context = &$this->context;
                $directive->apply($value, $path, $template);
                return true;
            }
        }
        return false;
    }

    public function flattenTemplate(&$template){
        $flattened = false;

        if (is_object($template)) {
            $template = (array)$template;
        }

        foreach($template as $key => $sub){
            if (is_object($sub)) {
                $sub = (array)$sub;
            }

            if (isset($sub['elements'])) {
                $flattened = true;

                //echo "flattening:".json_encode($sub)."\n\n";
                $elements = array_values((array)$sub['elements']);
                unset($sub['elements']);
                array_push($sub, ...(array)$elements);
                
                //echo "sub2:".json_encode($sub)."\n\n";
                unset($template[$key]);
                array_push($template, ...(array)$sub);
                //echo "template:".json_encode($template, \JSON_PRETTY_PRINT)."\n\n";
            }
        }

        //echo "flattening:".json_encode($template, \JSON_PRETTY_PRINT)."\n\n";

        if ($flattened == true) {
            $this->flattenTemplate($template);
        }

        //echo "flattened:".json_encode($template, \JSON_PRETTY_PRINT)."\n\n";
    }

    public function prepOutputTemplate(&$out, $in){
        if (!isset($out)) {
            $out = [];
        }

        if (!isset($out["output"])) {
            $out["output"] = $this->outputConfig;
        }
        if (!isset($out['callback']) && !empty($this->callbackConfig)) {
            $out['callback'] = $this->callbackConfig;
        }
        if (!isset($out['timeline'])) {
            $out['timeline'] = [];
        }
        if (!isset($out['timeline']['soundtrack']) && !empty($this->soundtrackConfig)) {
            $out['timeline']['soundtrack'] = $this->soundtrackConfig;
        }
        if (!isset($out['timeline']['fonts']) && !empty($this->fontConfig)) {
            $out['timeline']['fonts'] = $this->fontConfig;
        }

        if (isset($in['output'])){
            $out['output'] = array_merge($out['output'], $in['output']);
        }
        if (isset($in['callback'])){
            $out['callback'] = $in['callback'];
        }
        if (isset($in['timeline']['soundtrack'])){
            $out['timeline']['soundtrack'] = array_merge($out['timeline']['soundtrack'], $in['timeline']['soundtrack']);
        }
        if (isset($in['timeline']['fonts'])){
            $out['timeline']['fonts'] = array_merge($out['timeline']['fonts'], $in['timeline']['fonts']);
        }
    }

    public function convertToTracks($elements):array {
        $tracks = [];

        echo "elements:".json_encode($elements, \JSON_PRETTY_PRINT)."\n\n";

        foreach($elements as $element){
            $z = 0;
            if (!empty($element["z"])) {
                $z = $element["z"];
                unset($element["z"]);
            }

            if (!isset($tracks[(string)$z])) {
                $tracks[$z] = [
                    "clips" => [],
                ];
            }
            $tracks[(string)$z]["clips"] []= $element;
        }

        krsort($tracks, \SORT_NUMERIC);
        $tracks = array_values($tracks);

        return $tracks;
    }
    
}
