<!DOCTYPE html>
<html>
    <title></title>
    <body>
        <h1>Hi, </h1>
        <h1>You just claim your company. Click here: {{ env('URL_FRONTEND').'claim-company/active?v='.$data['token'] }}</h1>
    </body>
</html>
