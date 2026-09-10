---
title: Introduction
layout: layout.html
orderPath: /__index
eleventyNavigation:
  key: Index
  order: 0
---
# Hotelinking Api

The Hotelinking Api is used to supply a communication with the Hotelinking app functionalities.

## Getting Started

To install the project and start working, please follow the general setup instructions defined in our <a href="https://hotelinking.atlassian.net/wiki/spaces/MA/pages/2272264235/Configuracion+Inicial+Hotelinking+-+2" target="_blank">Counfluence</a>.

## Testing

### Create an automatic test

To do automatic test, you have to create them first. All test are in the folder *tests*
To create a new test execute that command in your command line:

```
php artisan make:test <YourTestName>
```

Note that all test uses factories to fill the database with some information.
That factories uses the library faker to input random information.

### Run automatic tests on local

To run in local the tests in the same conditions as the pipeline will do, follow this steps

```
docker exec -it hlapi bash
```

Now, you are in the docker and you can execute all the test like that will execute in the pipeline

```
composer test
```

If you want to run only the tests of a class, you can do it with the following command

```
vendor/bin/phpunit --filter {className}
```