<x-guest-layout>
    <h2> {{ $customer->full_name }}</h2>
    <p>{{$customer->email}}</p>
    <p>{{$customer->gender}}</p>
    <p>{{$customer->dob}}</p>
    <p>{{$customer->nationality}}</p>
    <h2> Skills </h2>
    <ul>
        @foreach($skills as $skill)
            <li> <b>{{ $skill['name']  }}</b> <br> {{ $skill['description'] }} <br> <b>Years:</b> {{ $skill['years'] }}</li>
        @endforeach
    </ul>
</x-guest-layout>