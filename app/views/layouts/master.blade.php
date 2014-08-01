<!doctype html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    {{ stylesheet_link_tag() }}

    <meta charset="UTF-8">
    <title></title>

</head>
<body>

    @yield('content')

    {{ javascript_include_tag() }}


    @yield('inlineScripts')
</body>
</html>
