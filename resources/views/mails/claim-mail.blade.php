<!DOCTYPE html>
<html>
    <title></title>
    <body>
        <h4>Hi, {{ isset($name) ?? '' }}</h4>
        <h5>You just claim your company. Click <a href="{{ env('URL_FRONTEND').'/claim-company/active?v='.isset($token) ?? '' }}">here</a></h5>
    </body>
</html>
