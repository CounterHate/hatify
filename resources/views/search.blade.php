@extends('layout')
@section('content')
    <show-tweets url="{{ config('es.ES') }}/{{ config('es.INDEX') }}" :search="true"
        es_user={{ config('es.ES_USER') }} es_pass={{ config('es.ES_PASS') }} :user={{ $user }} />

@endsection
