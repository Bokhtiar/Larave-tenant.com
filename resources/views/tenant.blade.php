<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form action="{{ url('tenant/store') }}" method="POST">
        @csrf
    <input type="text" name="name" id="">
    <input type="text" name="subdomain" id="">
    <input type="submit" name="" id="">
    </form>

    @foreach ($tenants as $item)
        <ul>
            <li>{{ $item->name }}</li>
        </ul>
    @endforeach
</body>
</html>