# Rendering
In most of the examples, the result of an "eval:" string was a number. (e.g. "eval: 1 + 1" is a string, and the result of the evaluation is 2, a number.)  However, eval actually evaluates to a JSON object, strings and numbers happen to be valid JSON objects.  This means you could do quite a bit more complicated constructions if you so chose, by mixing mustache templating with JSON encoding.

Example:
{
    "elements": "eval: [ {{#narrations}} { "asset":{ "type": "audio", "src": "{{#src}}", ... }, ... } {{^end}}, {{/end}} {{/narrations}} ]"
}

With scope of:
{
    "narrations":[
        {"src":"http://one.com"},
        {"src":"http://two.com"},
        {"src":"http://three.com"},
        {"src":"http://four.com", "end": true},
    ]
}

Renders to:
{
    "elements": [
        { "asset":{ "type": "audio", "src": "http://one.com", ... }, ... },
        { "asset":{ "type": "audio", "src": "http://two.com", ... }, ... },
        { "asset":{ "type": "audio", "src": "http://three.com", ... }, ... },
        { "asset":{ "type": "audio", "src": "http://four.com", ... }, ... },
    ]
}


[Back](https://github.com/CobaltBlueDW/ShotstackElements)