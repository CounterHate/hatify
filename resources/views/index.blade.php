@extends('layout')
@section('content')
    <show-tweets url={{ ENV('ES') }} index={{ ENV('INDEX') }} :search="false" es_user={{ ENV('ES_USER') }} es_pass={{ ENV('ES_PASS') }}/>

@endsection
