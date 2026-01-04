<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="stylesheet" href="{{ public_path('documents/templates/template_1.css') }}" type="text/css">
    </head>
    <body class="page">
        @php
            $src = ' ';
            if(is_file('storage/customers-profile-photos/' . $customer->profile_photo_url)){
                $path = public_path('storage/customers-profile-photos/' . $customer->profile_photo_url);
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $base64 = base64_encode(file_get_contents($path));
                $src = "data:image/{$type};base64,{$base64}";
            }
        @endphp

        <div class="section header">
            <img src="{{ $src }}" class="avatar" alt="customer profile image">
            <h1>{{ $customer->full_name }}</h1>
            <p>{{ $customer->email }}</p>
        </div>

        <div class="section">
            <h2>Personal Information</h2>
            <p><b>Gender:</b> {{ $customer->gender }}</p>
            <p><b>Date of birth:</b> {{ date_format($customer->dob, 'd-m-Y') }}</p>
            <p><b>Nationality:</b> {{ $customer->nationality }}</p>
        </div>

        <div class="section">
            <h2>Skills</h2>
            <ul>
                @foreach($skills as $skill)
                    <li>
                        <b>{{ $skill['name'] }}</b><br>
                        {{ $skill['description'] }}<br>
                        <b>Years:</b> {{ $skill['years'] }}
                    </li>
                @endforeach
            </ul>
        </div>

        <div>
            <h2>Professional Summary</h2>
            {{ $summary }}
        </div>
    </body>
</html>