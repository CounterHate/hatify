@extends('layout')
@section('content')
    <random-tweet url="{{ config('es.ES') }}/{{ config('es.INDEX') }}" es_user={{ config('es.ES_USER') }}
        es_pass={{ config('es.ES_PASS') }} />
@endsection
