<!doctype html>
<html lang="en">
<head>
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
