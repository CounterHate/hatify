@extends('layout')
@section('content')
    <random-tweet :user={{ $user->id }} />
@endsection
