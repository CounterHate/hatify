@extends('layout')
@section('content')
    <show-tweets url={{ ENV('ES') }} index={{ ENV('INDEX') }} :search="false"/>

@endsection
