# Problem 1

The Tracks/Clips paradigm can be a cumbersom format for managing assets and dealing with layering.

# Solution

Shotstack Elements inverts the concept of tracks by adding a "z" property to assets, which works like an html/css z-index to describe the asset's layering order.

Now your template construction isn't coupled to your scene composition.

## Example:
>{
>  "elements":[
>    {
>      "asset": {
>        "type": "html",
>        "html": "<p>Hello World!</p>",
>      },
>      "z": 2,
>      "start": 1,
>      "length": 10,
>    },
>    {
>      "asset": {
>        "type": "image",
>        "src": "https://templates.shotstack.io/basic/asset/image/overlay/slanted-panel-cyan-highlite.png"
>      },
>      "z": 1,
>      "start": 0,
>      "length": 11
>    }
>  ]
>}

## Renders To:
>{
>  "tracks": [
>    {
>      "clips": [
>        {
>          "asset": {
>            "type": "html",
>            "html": "<p>Hello World!</p>",
>          },
>          "start": 1,
>          "length": 10,
>        }
>      ]
>    },
>    {
>      "clips": [
>        {
>          "asset": {
>            "type": "image",
>            "src": "https://templates.shotstack.io/basic/asset/image/overlay/slanted-panel-cyan-highlite.png"
>          },
>          "start": 0,
>          "length": 11
>        }
>      ]
>    }
>  ]
>}

[Back](https://github.com/CobaltBlueDW/ShotstackElements)