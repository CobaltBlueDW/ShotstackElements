# Problem 6
Shotstack templates use a variety of different units of measure in different places, AND some of those units can be tricky.  For example, let's say you make an info graphic composition, and set the width and hight to (1000X500).  It looks great when rendered at "sd" resolution, but when you add it to a template that's rendering at "1080" resolution, it's WAY too small.

# Solution
Shotstack Elements supports unit calculations.  Using unit calculations, you can choose to use the same unit across all measurements, or pick and choose the right unit for the occassion like you can with CSS.  There are currently 3 supported units.

## Pixels (P)
These are absolute output pixels.  These are what are currently used by shotstack when describing width/height/etc.  e.g. 10px will not scale up or down with the rendering resolution.  Pixel is a great unit to use for logos, or for images/videos that would look bad if scaled-up.

## Ratio (R)
These units represent a fraction/ratio/percentage of the view port.  These are what are currently used by shotstack offsets.  Since these are relative to the view port, they scale with resolution.  Values tend to range from 0 to 1 (e.g. 0.5 means 50%).

## Grid (G)
Grid acts like pixels in that it's a discrete quanitity, except it's relative to resolution.  You can define your grid system (say you'd like the grid to be 1920px by 1080px) and then use it like pixels (for example, calling out an x offset of 16g).  If your grid definition is the same as your output resolution, a grid unit is exactly equal to a pixel (so 16g means 16px), but if your resolution changes a grid unit scales to maintain proportionality.

Example:
    - grid definition: 1600 by 900
    - at 'preview' resolution:
    -- a width of 1600g would equal 512px
    -- a height of 450g would equal 144px
    - at 'sd' resolution
    -- a width of 1600g would equal 1024px
    -- a height of 450g would equal 288px

# Conversion
Shotstack has specific expectations about which unit be provided for each property, but with unit calculations, you can convert any unit into any other unit.  

## Implicit Conversion
Example:
{
    ...
    "offset":{
        "x": "unit: 36g",
        "y": "unit: -12g",
    }
}

Shotstack expects offsets to be Ratios, but if you'd prefer to use Grid, you can provide your Grid unit using "unit:" and it will implicatly convert it to Ratio for you.

## Explicit/Eval Conversion
There are some use-cases where simple implicit conversion might not work, for those cases, you can use the unit conversion mustache lambdas.

Example:
{
    ...
    "offset":{
        "x": "eval: {{#GXtoR}}36{{/GXtoR}}",
        "y": "eval: {{#GYtoR}}-12{{/GYtoR}}",
    }
    ...
    "css": "eval: \"p{ margin-top: {{#GYtoP}}14{{/GYtoP}}px; }\""
}

Note that the conversion needs to know if the value is a vertical "X" value or horizontal "Y" value, provided after the initial unit in the lambda name "G"(for Grid), "X"(for horizontal), "to", "R"(for Ratio).  This vertical and horizontal distinction isn't needed with implicit conversion, as it's derived from the context, but the lambdas need this specificity as they don't always have access to such clear context.  See how the CSS margin-top property uses the GYtoP converter as margin-top is a vertical displacement.


[Back](https://github.com/CobaltBlueDW/ShotstackElements)