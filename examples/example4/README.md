# Problem 4
Injecting values using mustache templating only gets us so far, if the values are always absolute values.  For partial templates of compositional elements to be reusable, we really need some way to describe things relatively.

# Solution
Shotstack Elements supports Calculations.  You can either run a simple calculation using the "calc:" directive, or use the mustache lambda "calc".

# Simple Calculations
Example:
{
    ...
    "z": "calc: 10 * 4 - 3",
}

Renders to:
{
    ...
    "z": 37,
}

# Lambda Calculations
eval: can handle basic arithmetic expressions using a mustache lambda called "calc".  To use "calc", simply wrap your equation in {{#calc}} {{\calc}} tags.

Example:
{
    "start": "eval: {{#calc}} {{scene.start}} + 1 {{\calc}}",
    ...
}

Renders to:
{
    "start": 13,
    ...
}

Notice how you can use variables in your calculations.  This is a powerful tool for templating, as you can describe things in your partial composition templates relative to other values.  If, for example, you were describing an animation with 3 stages, you could describe all of the start times as duration off-sets from an initial animation start time, and everywhere you used that composite animation, it would just work without having to manually calculate the start time of each asset in the animation.
