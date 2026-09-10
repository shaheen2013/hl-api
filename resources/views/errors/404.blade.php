<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>404</title>
  <link href="{{ mix('css/app.css') }}" rel="stylesheet">
<style>

  * {
  	"Whitney SSm A", "Whitney SSm B", "Helvetica Neue", Helvetica, Arial, Sans-Serif;
  }

    .error-text {
      font-size: 130px;
    }

    @media (min-width: 768px) {
      .error-text {
        font-size: 220px;
      }
    }

</style>

<body>
<div class="h-screen w-screen bg-blue-600 flex justify-center content-center flex-wrap">
  <p class="font-sans text-white error-text">404</p>
</div>

</body>
</html>