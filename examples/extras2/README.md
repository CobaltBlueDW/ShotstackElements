# Lazy Loading
You can load template into and property using the "json:" directive.

## Local File
Example:
>{
>    "elements": "json://path/to/elements/file.json"
>}

## Remote Files
If enabled in the Shotstack Element's config, remote files can even be loaded. (off by default as a security precausion)

Example:
{
    "elements": "json:http://domain/path/to/elements/file.json"
}


[Back](https://github.com/CobaltBlueDW/ShotstackElements)