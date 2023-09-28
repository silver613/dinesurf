<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <script>
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}'
        }
    </script>

    <title inertia>
        @isset($page['props']['title'])
            {{ $page['props']['title'] }}
        @endisset
        @if (!isset($page['props']['title']))
            Dinesurf
        @endif
    </title>

    <!-- meta data -->
    <meta name="title" content="@isset($page['props']['title']) {{ $page['props']['title'] }} @endisset"/>
    <meta name="description" content="@isset($page['props']['description']) {{ $page['props']['description'] }} @endisset">
    <meta name="keywords" content="@isset($page['props']['keywords']) {{ $page['props']['keywords'] }} @endisset">
    <meta name="url" content="@isset($page['props']['metaUrl']) {{ $page['props']['metaUrl'] }} @endisset">
    
    <meta name="image" property="og:image"   content="@isset($page['props']['metaImage']) {{ $page['props']['metaImage'] }} @endisset"/>
    <meta property="og:image:secure_url" content="@isset($page['props']['metaImage']) {{ $page['props']['metaImage'] }} @endisset"/>
    <meta property="og:image:width" content="600" />
    <meta property="og:image:height" content="450" />
    <meta property="og:image:alt"   content="@isset($page['props']['title']) {{ $page['props']['title'] }} @endisset" />


    <meta property="og:type" content="website" />
    <meta property="og:title" content="@isset($page['props']['title']) {{ $page['props']['title'] }} @endisset"/>
    <meta property="og:url"content="@isset($page['props']['metaUrl']) {{ $page['props']['metaUrl'] }} @endisset" />
    <meta property="og:description" content="@isset($page['props']['description']) {{ $page['props']['description'] }} @endisset"/>

    <meta property="twitter:type" content="website" />
    <meta property="twitter:title" content="@isset($page['props']['title']) {{ $page['props']['title'] }} @endisset"/>
    <meta property="twitter:url"content="@isset($page['props']['metaUrl']) {{ $page['props']['metaUrl'] }} @endisset" />
    <meta property="twitter:description" content="@isset($page['props']['description']) {{ $page['props']['description'] }} @endisset"/>

    


    <meta name="google-site-verification" content="3RiRD9-ux5_8Hp6JhT9E4l6o_HYIQUk3ZV049ttrvaY" />

    <!-- Favicon -->
    <link rel="icon"
        href="@if (isset($page['props']['favicon'])) {{ $page['props']['favicon'] }} @else {{ asset('images/favicon.jpg') }} @endif"
        type="image/x-icon" />
    <link rel="apple-touch-icon" sizes="180x180"
        href="@if (isset($page['props']['favicon'])) {{ $page['props']['favicon'] }}@else {{ asset('images/favicon.jpg') }} @endif">
    <link rel="icon" type="image/svg"
        href="@if (isset($page['props']['favicon'])) {{ $page['props']['favicon'] }}@else {{ asset('images/favicon.jpg') }} @endif"
        sizes="32x32">
    <link rel="icon" type="image/svg"
        href="@if (isset($page['props']['favicon'])) {{ $page['props']['favicon'] }}@else {{ asset('images/favicon.jpg') }} @endif"
        sizes="16x16">
    <link rel="mask-icon"
        href="@if (isset($page['props']['favicon'])) {{ $page['props']['favicon'] }}@else {{ asset('images/favicon.jpg') }} @endif"
        color="#ffffff">

    <!-- Fonts -->
    {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap"> --}}

    <!-- Styles -->
    <link href="{{ asset('fonts/fontawesome-free-6.1.2-web/css/all.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">



    <!-- Scripts -->
    @routes
    @vite('resources/js/app.js')
    @inertiaHead


    @if (config('app.env') == 'production')
        <script id="mcjs">
            ! function(c, h, i, m, p) {
                m = c.createElement(h), p = c.getElementsByTagName(h)[0], m.async = 1, m.src = i, p.parentNode.insertBefore(m,
                    p)
            }(document, "script",
                "https://chimpstatic.com/mcjs-connected/js/users/0dcf4e6612a93def9b158f53a/08aa7508ada32b0edd709e7b5.js");
        </script>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-0FYPKXT79J"></script>
    @endif

    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', 'G-0FYPKXT79J');
    </script>

    <!-- Hotjar Tracking Code for https://app.dinesurf.com/dashboard -->
    <script>
        (function(h, o, t, j, a, r) {
            h.hj = h.hj || function() {
                (h.hj.q = h.hj.q || []).push(arguments)
            };
            h._hjSettings = {
                hjid: 3187300,
                hjsv: 6
            };
            a = o.getElementsByTagName('head')[0];
            r = o.createElement('script');
            r.async = 1;
            r.src = t + h._hjSettings.hjid + j + h._hjSettings.hjsv;
            a.appendChild(r);
        })(window, document, 'https://static.hotjar.com/c/hotjar-', '.js?sv=');
    </script>

    @if (Request::is('vendors/*'))
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    @endif

</head>

<body class="font-sans antialiased ">
    @inertia
</body>

<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
    crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="{{ route('api.maps') }}" async defer></script>
<script src="https://js.paystack.co/v2/inline.js"></script>


<!-- Response Messages -->
@if (Session::has('success'))
    <script>
        toastr.options.timeOut = 0;
        toastr.options.extendedTimeOut = 0;
        toastr.options.closeButton = true;
        toastr.options.progressBar = true;
        toastr.success("{{ Session::get('success') }}");
    </script>
@endif
@if (Session::has('error'))
    <script>
        toastr.options.timeOut = 0;
        toastr.options.extendedTimeOut = 0;
        toastr.options.closeButton = true;
        toastr.options.progressBar = true;
        toastr.error("{{ Session::get('error') }}");
    </script>
@endif
@if ($errors->any())
    @foreach ($errors->all() as $error)
        <script>
            toastr.options.timeOut = 0;
            toastr.options.extendedTimeOut = 0;
            toastr.options.closeButton = true;
            toastr.options.progressBar = true;
            toastr.error("{{ $error }}");
        </script>
    @endforeach
@endif
@if (session()->has('success_stay'))
    <script>
        toastr.options.timeOut = 0;
        toastr.options.extendedTimeOut = 0;
        toastr.options.closeButton = true;
        toastr.options.progressBar = true;
        toastr.success("{{ session()->get('success_stay') }}")
        // $('.alert-success').html("{{ session()->get('success') }}");
        // $('.alert-success').show();
        // $('.alert-x').show();
    </script>
@endif
<!--End Response Messages -->

{{-- Avoid CRSF Miss Match --}}


<!-- Avoid CRSF Miss Match -->
<script>
    // window.onload = function() {
    //     if (!window.location.hash) {
    //         window.location = window.location + '#loaded';
    //         window.location.reload();
    //     }
    // }
</script>



</html>
