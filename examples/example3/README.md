# Problem 3
In the last example, merging our two scenes only worked because the "start" times in each scene template had absolute values that lined-up properly (e.g. scene1.json had start values of 1, and scene2.json had start values of 10), but that's not very reusable.  If we really want our composition and scene templates to be properly re-usable, they need a strong variable injection system.

# Solution
Introducing the "eval:" directive; a powerful feature of Shotstack Elements.  Any property value that starts with "eval:" will be evaluated and rendered.

# Mustache Templating
In an eval: you can use mustache templating (full docs: https://mustache.github.io/mustache.5.html).  This includes but is not limited to the use of variables.  Which variables you have access to at any given location will be describe in a later section covering scopes.  Here's a quick example of it in action.

Example:
{
    ...
    "start": "eval: {{scene.start}}",
    "z": "eval: {{global.z.overlay}}",
    ...
}

Renders to (assuming scene.start = 12 and global.z.overlay = 100):
{
    ...
    "start": 12,
    "z": 100,
    ...
}

Mustache templating encapsulates each variable in double curly brackets, and uses dot traversal notation to reference sub-properties.  --So, in the example above, we replace {{scene.start}} with the value in a property named "start" of an object named "scene".

Here's another example of using mustache notation in an eval.  We make a responsive layout by using the mustache's conditional notation to change a property based on another value.

Example:
{
    ...
    "scale": "eval: {{#global.output.mobile}} 1 {{/global.output.mobile}} {{^global.output.mobile}} 0.5 {{/global.output.mobile}} ",
    "offset": {
        "x": "eval: {{#global.output.mobile}} 0 {{/global.output.mobile}} {{^global.output.mobile}} 0.5 {{/global.output.mobile}} ",
        "y": 0
    }
    ...
}

On mobile, the render would be:
{
    ...
    "scale": 1,
    "offset": {
        "x": 0,
        "y": 0
    }
    ...
}

On non-mobile, the render would be:
{
    ...
    "scale": 0.5,
    "offset": {
        "x": 0.5,
        "y": 0
    }
    ...
}



[Back](https://github.com/CobaltBlueDW/ShotstackElements)