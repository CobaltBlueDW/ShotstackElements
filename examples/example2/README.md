# Problem 2
Shotstack templates concieve of things in terms of timelines, but it's often helpful to conceive of things in terms of compositions and scenes.

# Solution
With template construction being decoupled from composition, it's now much easier to compose a template out of smaller templates.  If you have a graphic or visual effect that requires multiple assets spread across a variety of layers, you can make a separate (partial) template with that conceptual composition and then easily merge many such templates together for a final product.

Shotstack Elements has also implemented the concept of a Scene.  A Scene is simply a sub-collection of assets with it's own scope (more on scopes later).  Any object with an "elements" property will be interpreted as a Scene.  You can use them to organize your assets however you'd like.  The base JSON object is infact, itself, just a Scene.


[Back](https://github.com/CobaltBlueDW/ShotstackElements)