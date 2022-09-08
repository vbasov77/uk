@extends('layouts.app')
@section('content')
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

    @if (!empty($error))
        <div style="background-color: red; color:#ffffff; padding: 5px;margin: 15px">
            <center>{{$error}}</center>
        </div>
    @endif

    @if (!empty($message))
        <div style="background-color: #43b143; color:#ffffff; padding: 5px;margin: 15px">
            <center> {{$message}}</center>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @include('blade')



    @push('scripts')
        <script src="{{ asset('js/fecha.min.js') }}" defer></script>
        <link href="{{ asset('css/hotel-datepicker.css') }}" rel="stylesheet">
        <script src="{{ asset('js/hotel-datepicker.min.js') }}" defer></script>

        <script>
            var start = @json($start);
            var min = @json((int)$rules[1]);
            var max = @json((int)$rules[2]);
        </script>
        <script src="{{ asset('js/calendars/calendar.js') }}" defer></script>
    @endpush
@endsection

