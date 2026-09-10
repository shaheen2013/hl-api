<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bouncer</title>
  <link href="{{ mix('css/app.css') }}" rel="stylesheet">
  <style>
    :root {
      --main-color: {{ $mainColor }};
      --text-color: {{ $textColor }};
      
      --main-color-darken: {{ $mainColorDarken }};
    }

    .main-wrapper {
      background-image: url({{$bgImages["medium"]}});
      background-size: cover;
      background-position: center;
      background-blend-mode: soft-light;
    }

    .flex-grow-1 {
      flex-grow: 1;
    }

    @media (min-width: 1200px) {
      .main-wrapper {
        background-image: url({{$bgImages["large"]}});
      }
    }

    @media (max-width: 480px) {
      .main-wrapper {
        background-image: url({{$bgImages["small"]}});
      }
    }

    html,
    body {
      height: 100%;
      width: 100%;

      background: {
          {
          $mainColor
        }
      }
    }

    .modal {
      top: 0;
      background-color: rgba(0, 0, 0, 0.9);
      z-index: 2002;
    }

    .modal-icon {
      background-color: white;
      position: absolute;
      left: 50%;
      margin-left: -40px;
      top: -40px;
      border-radius: 50%;
      border: 4px solid white;
    }

    .modal-content {
      max-width: 600px;
      max-height: 100vh;
    }

    .modal-body {
      overflow-y: auto;
      max-height: 60vh;
    }
  </style>

<body>
  <div id="instructionsModal" class="modal hidden flex h-screen w-screen fixed items-center justify-center left-0">
    <div class="modal-content rounded bg-white w-10/12 px-4 relative pb-4 pt-12">
      <div class="modal-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 62.155 62.155"><g fill="none" stroke="#c5c5c5" stroke-linecap="round" stroke-linejoin="round" stroke-width="5"><path d="M36.793 42.509h-1.905a3.81 3.81 0 0 1-3.81-3.81v-9.526a1.906 1.906 0 0 0-1.905-1.905h-1.911M30.125 17.741a.953.953 0 1 0 .953.953.953.953 0 0 0-.953-.953h0"/><path d="M31.078 59.655A28.578 28.578 0 1 0 2.5 31.078a28.578 28.578 0 0 0 28.578 28.577Z"/></g></svg>
      </div>

      <p class="modal-body" data-test="modalBody">
        @lang('checkin.instructions')
      </p>

      <div class="flex justify-center">
        <button onclick="understoodButtonClicked(event)" class="btn font-black py-2 px-4 rounded mt-8 text-white uppercase">
          <a href="{{$checkinUrl}}">
            @lang('cna.understood')
          </a>
        </button>
      </div>
    </div>
  </div>

  <div class="main-wrapper h-full w-full bg-black flex flex-col justify-between text-gray-700"
    style="background-color: {{$mainColor}} !important;">
    <div class="bg-color"></div>
    <div class="text-white w-full self-start pt-4 px-8 pb-10 relative h-auto lex items-center justify-between">
      <div class="logo absolute" style="background-image: url('{{$hotel->logo}}');"></div>
      <div class="font-bold text-center header-title">
        <p>@lang('cna.header')</p>
        <p class="font-light">
          <small>{{$hotel->name}}</small>
        </p>
      </div>
    </div>
    
    <div class="flex flex-1 bg-white flex-col rounded-t-2xl">
      <div class="flex flex-col w-full h-full px-8 pt-16 pb-8 sm:px-20 md:px-40 lg:px-64 xl:px-96 overscroll-auto">
        <h1 class="text-xl mb-6 font-black w-full text-center">@lang('cna.welcome') {{$hotel->name}}</h1>
        <p class="mb-16 flex-grow-1">
          @lang('cna.text')
        </p>
        <button onclick="checkinButtonClicked()" class="btn font-bold py-3 px-5 rounded w-full shadow-lg focus:outline-none uppercase">
          <a class="text-white" href="{{$isInHotel && $isInCna ? 'javascript:void(0)' : $checkinUrl}}">
            @lang('cna.checkinButton')
          </a>
        </button>

        @if($isInHotel)
        <form name="redirect" method="{{$method}}" action="{{$appUrl}}">
          @foreach ($formParams as $key => $param)
            <input type="hidden" name="{{$key}}" value="{{ $param }}">
          @endforeach
          
          <button type="submit" class="btn mt-5 font-bold py-3 px-5 rounded w-full shadow-lg focus:outline-none uppercase">
            <span class="text-white">  
              @lang('cna.connectWifibutton')
            </span>
          </button>
        </form>
        @endif
      </div>
    </div>
  </div>
</body>

</html>

<script>
  function checkinButtonClicked () {
    if ("{{$isInHotel && $isInCna}}") {
      var modal = document.getElementById("instructionsModal");
      modal.classList.remove("hidden");
    } else {
      window.location.href = "{{$checkinUrl}}";
    }
  }

  function understoodButtonClicked(e) {
    if ("{{$isInHotel && $isAndroid && $isInCna}}") {
      e.preventDefault();

      // If the user is in the hotel, inside the CNA and it is an android device, we will open the intent link inside an iframe to know if this device has this functionality.
      // If the device has intent, it will automatically exit the CNA, but if not we will redirect the user to the fallback url, which will contain the steps to exit the CNA by hand.
      var ifrm = document.createElement("iframe");
      ifrm.setAttribute("src", "{{$checkinUrl}}");
      document.body.appendChild(ifrm);

      setTimeout(function() { 
        window.location.href = "{{$fallbackUrl}}";
      }, 500);
    } 
  }

</script>