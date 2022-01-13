@extends('layout')
@section('content')
    <verify-tweet :user={{ $user->id }} />
@endsection
