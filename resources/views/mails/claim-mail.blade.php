<!DOCTYPE html>
<html>
    <title></title>
    <body>
        <h4>Hi, {{ $name }}</h4>
        <h5>You just claim your company. Click <a href="{{ env('URL_FRONTEND').'/claim-company/active?v='.$token }}">here</a></h5>
    </body>
</html>
