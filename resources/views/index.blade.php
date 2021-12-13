<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


{{--    <meta property="og:image" content="http://themepixels.me/slim/img/slim-social.png">--}}
{{--    <meta property="og:image:secure_url" content="http://themepixels.me/slim/img/slim-social.png">--}}
{{--    <meta property="og:image:type" content="image/png">--}}
{{--    <meta property="og:image:width" content="1200">--}}
{{--    <meta property="og:image:height" content="600">--}}

    <!-- Meta -->
    <meta name="description" content="Premium Quality and Responsive UI for Dashboard.">
    <meta name="author" content="ThemePixels">

    <title>April Dashboard</title>

    <!-- vendor css -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

    <link href="{{url('assets')}}/lib/Ionicons/css/ionicons.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Slim CSS -->
    <link rel="stylesheet" href="{{url('assets')}}/css/slim.css">

</head>
<body>
<div id="app">
    <router-view></router-view>
</div>

<script src="{{asset("/js/app.js")}}"></script>
<script src="{{url('assets')}}/lib/jquery/js/jquery.js"></script>
<script src="{{url('assets')}}/lib/popper.js/js/popper.js"></script>

</body>
</html>
