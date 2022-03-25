@extends('layout')
@section('content')
    <user-tweets username='{{$username}}' media='{{$media}}'/>
@endsection
