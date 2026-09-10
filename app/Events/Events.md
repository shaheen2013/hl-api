---
title: Events
layout: layout.html
eleventyNavigation:
  key: Events
---

# Events

We can send events from _Hotelinking Api_ to _Hotelinking Gateway_ that will be emitted when certain activities are happening inside our application, so we can recollect and process information in the background. We have an old and a new way to create events. 

The new way of sending events implies creating a class inside the _Events_ folder that will extend from _EventBridgeEvent_ class. A payload will be sent so we can recollect all the necessary info. Then the event must be registered in our listener, the EventBridgeDispatcherListener. Finally, we have to dispatch the event so it can be actually emitted. We call a event() function that receives a new instance of the event class as parameter and it should be placed after that certain action is performed.

Check out [Amazon EventBridge](https://aws.amazon.com/es/eventbridge/) to learn more about it.

We currently have docs for the following Events:
{% assign navPages = collections.all | eleventyNavigation: "Events" %}
{{ navPages | eleventyNavigationToHtml }}
