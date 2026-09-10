---
title: Jobs
layout: layout.html
eleventyNavigation:
  key: Jobs
---

# Jobs

In Hotelinking Api we need to execute some requests in the background, either because they are processes that take a long time and it would be unfeasible to do it through a normal HTTP request (e.g. generate a CSV file with all the clients of a brand) or because it needs to react to an event from another Api (e.g. when unsubscribing a user from integrations).

Currently these jobs are run by listening to an [AWS SQS queue](https://aws.amazon.com/es/sqs/) or simply queued directly from an endpoint and handled automatically from the DB thanks to [Laravel's queues](https://laravel.com/docs/9.x/queues).

We currently have all these Jobs:
{% assign navPages = collections.all | eleventyNavigation: "Jobs" %}
{{ navPages | eleventyNavigationToHtml }}
