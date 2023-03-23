# Problem 5

When we talk about injection variables being relative, we don't just want the values to be relative, we'd like the references to be relative as well.


# Solution

Variables are scoped.


# Scopes

Depending on where you are in the template, determines what values are available to you, and how a variable reference will be evaluated.  In previuos examples, we used references like "scene.start" and "global.z.overlay".  "global" and "scene" are both scopes.


# Scene Scope

While you are in a Scene, you have access to that Scene's scope.  Every property attached to that Scene is available in that Scene's scope.  You reference your current scene's scope with the name "scene".

## Example:
    {
        "region": {
            "x": 0.5,
            "y": 0,
            "width": 0.5,
            "height": 1
        },
        "elements": [
            {
                ...
                "width": "eval: {{scene.region.width}}"
            },
            {
                ...
                "width": "eval: {{scene.region.width}}"
            },
            ...
        ]
    }

## Special Values

Each scope will have some specialty computed run-time values that you can access.  These can be incredibly useful if utilized properly.

## Example:

    {
        "elements":[
            {
                "start": "eval: {{system.duration}}
                "elements": [
                    {
                        "start": "eval: {{scene.start}} + 10",
                    },
                    ...
                ]
            },
            {
                "start": "eval: {{system.duration}}
                "elements": [
                    {
                        "start": "eval: {{scene.start}} + 1.5",
                    },
                    ...
                ]
            },
        ]
    }

In this example, we use the system.duration (representing the length of the video up until now) and store it as the "start" value for each of our scenes.  This makes each consecutive scene "start" when the last one ends.  We can then make all start times within a scene relative to the start of the scene.

## Special Scene Values

Here are the list of special values attached to each scene:
    #TODO: add values


# Global Scope

The global scope is a special version of a scene scope.  The name "global" always references the scope of the root scene in the template.  The global scope is a great place to add settings that should apply to the entire template, since those values are so easily accesible.

## Example:

    {
        "source-domain": "my.cdn.cloud",
        "elements":[
            {
                "width": 32,
                "elements":[
                    {
                        "src": "eval: \"http://{{global.source-domain}}/background.png\" ",
                        ...
                        "width": "eval: {{scene.width}}"
                    },
                    ...
                ]
            },
            ...
        ]
    }

## Special Global Values
Here are the list of special values attached to the global scope:
    #TODO: add values


# Named Elements

You can name elements in a scene, by defining the elements list as an object instead of an array, and giving each item a name as a key.  Named items can then be traversed in references.

## Example:

    {
        "elements":{
            "info-graphic":{
                ...
                "width": 27
            },
            "drop-shadow":{
                ...
                "width": "eval: {{scene.elements.info-graphic.width}}"
            },
            "modal-backdrop":{
                ...
            }
        }
    }


# System Scope

The system scope exists to hold values defined outside the template.  It sits separately from the "global" scope to help prevent naming colissions.

## System Values
Here are the list of values attached to the system scope:
    #TODO: add values


[Back](https://github.com/CobaltBlueDW/ShotstackElements) | [Next](https://github.com/CobaltBlueDW/ShotstackElements/tree/main/examples/example6)