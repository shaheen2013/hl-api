<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Checkin</title>
  <link href="{{ mix('css/app.css') }}" rel="stylesheet">
  <style>
    :root {
      --main-color: {{ $mainColor }};
      --text-color: {{ $textColor }};
      --main-color-darken: {{ $mainColorDarken }};

    }

    .main-wrapper {
      background-image: url({{$bgImages["medium"]}});
    }

    @media (min-width: 1200px) {
      .main-wrapper {
        background-image: url({{$bgImages["large"]}});
      }
    }

    @media (max-width: 960px) {
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
  </style>

<body>
  <div id="loading" class="loading flex flex-col justify-center items-center h-full fixed w-full z-10 bg-white">
    <div class="spinner">
      <svg
        version="1.1"
        id="loader-1"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        width="80px"
        height="80px"
        viewBox="0 0 40 40"
        enable-background="new 0 0 40 40"
        xml:space="preserve"
      >
        <path
          opacity="0.2"
          fill="{{$mainColorDarken}}"
          d="M20.201,5.169c-8.254,0-14.946,6.692-14.946,14.946c0,8.255,6.692,14.946,14.946,14.946
  s14.946-6.691,14.946-14.946C35.146,11.861,28.455,5.169,20.201,5.169z M20.201,31.749c-6.425,0-11.634-5.208-11.634-11.634
  c0-6.425,5.209-11.634,11.634-11.634c6.425,0,11.633,5.209,11.633,11.634C31.834,26.541,26.626,31.749,20.201,31.749z"
        />
        <path
          fill="{{$mainColor}}"
          d="M26.013,10.047l1.654-2.866c-2.198-1.272-4.743-2.012-7.466-2.012h0v3.312h0
  C22.32,8.481,24.301,9.057,26.013,10.047z"
        >
          <animateTransform
            attributeType="xml"
            attributeName="transform"
            type="rotate"
            from="0 20 20"
            to="360 20 20"
            dur="0.5s"
            repeatCount="indefinite"
          />
        </path>
      </svg>
    </div>
  </div>
  <div class="main-wrapper min-h-full w-full bg-black flex flex-col flex-1"
    style="background-color: {{$mainColor}} !important;">
    <div class="bg-color"></div>
    <div class="header text-color-calculated w-full self-start pt-4 px-8 pb-10 relative">
      <div class="font-bold text-center w-full header-title">
        <p>@lang('checkin.header')</p>
        <p class="font-light">
          <small>{{$hotel->name}}</small>
        </p>
      </div>
      <div class="logo absolute" style="background-image: url('{{$hotel->logo}}');">
      </div>
    </div>
    <div class="checkin flex flex-1 flex-col justify-between px-8 pt-16 pb-8 sm:px-20 md:px-40 lg:px-64 xl:px-96 rounded-t-2xl bg-white">
        <div class="step-group">
          <h1 class="text-xl mb-6 font-black text-center">@lang('checkin.start')</h1>
          @if($isInHotel)
            <p class="mb-6">@lang('checkin.instructions')</p>
          @endif
          <button id="checkinButton" class="btn font-bold rounded w-full shadow-lg focus:outline-none uppercase mb-16">
            <a id="clickButton" class="clickButton block py-3 text-white" href="{{$url}}">
              @lang('cna.checkinButton')
            </a>
          </button>
          <p class="mb-6">@lang('checkin.followSteps'))</p>
          <div class="step flex items-center mb-4">
            <div class="number rounded-full h-8 w-8 border-2 flex items-center justify-center border-gray-300 flex-shrink-0">
              <span class="text-gray-500">1</span>
            </div>
            <p class="ml-3">@lang('checkin.copy')</p>
          </div>
          <div class="relative">
            <span id="copiedTooltip" class="transition-opacity duration-300 ease-out opacity-0 absolute right-0 bottom-16 bg-black px-4 py-2 text-white rounded uppercase text-xs font-black">@lang('checkin.copied')</span>
            <label>
              <span class="text-gray-700 font-black uppercase text-sm mb-2 cursor-pointer" onclick="copy()">@lang('checkin.clickToCopy')</span>
              <input id="url" type="text" onclick="copy()" class="rounded block w-full p-4 mb-6 border-2 border-gray-200 focus:outline-none cursor-pointer" readonly="readonly" value="{{$url}}" />
            </label>
          </div>
          <div class="step flex items-center mb-6">
            <div class="number rounded-full h-8 w-8 border-2 flex items-center justify-center border-gray-300 flex-shrink-0">
              <span class="text-gray-500">2</span>
            </div>
            <p class="ml-3">@lang('checkin.openBrowser')</p>
          </div>
          <div class="step flex items-start">
            <div class="number rounded-full h-8 w-8 border-2 flex items-center justify-center border-gray-300 flex-shrink-0">
              <span class="text-gray-500">3</span>
            </div>
            <p class="ml-3">@lang('checkin.pasteInstructions')</p>
          </div>  
        </div>
        <p class="text-center uppercase text-xs text-red-500 mt-4 flex-shrink"> @lang('checkin.finishMessage')</p>
    </div>
  </div>
</body>
<script>
  function copy() {
    var copyText = document.getElementById('url')

    /* Select the text field */
    copyText.select();
    copyText.setSelectionRange(0, 99999); /* For mobile devices */

    /* Copy the text inside the text field */
    document.execCommand("copy");
    document.getElementById("copiedTooltip").classList.remove("opacity-0");
    copyText.setSelectionRange(0, 0);

    setTimeout(() => {
      document.getElementById("copiedTooltip").classList.add("opacity-0");
    }, 1500);
  }

  function hideElement(id) {
    var element = document.getElementById(id);
    element.classList.add("hidden");
  }

  // Click on checkin link via javascript if it is an Apple device or is outside the CNA
  // For android devices without intent, we have to keep the user on this page to see the steps to exit the CNA by hand.
  if ("{{$isApple || !$isInCna}}") {
    var timeout = "{{ ($isApple  ? 1500 : 500) }}";
    setTimeout(function() {
      document.getElementById('clickButton').click();
      
      // Prevents show the form while loading the new windows
      setTimeout(function() {
        hideElement("loading");
      }, 500);
    }, timeout);
  } else {
    hideElement("loading");
    hideElement("checkinButton");
  }
  

</script>
</html>