@extends('argon')
@section('content')
    <random-tweet :user={{ $user->id }} />
@endsection
