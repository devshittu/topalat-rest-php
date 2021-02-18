<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{$details['title']}}</title>

</head>
<body class="antialiased">
<h1>{{$details['title']}}</h1>
<p>{{$details['body']}}</p>
<h2>About Sender</h2>
<p>
    Name: <strong>{{$details['full_name']}}</strong>
    <br>
    Phone Number: <strong>{{$details['phone']}}</strong>
</p>
</body>
</html>
