@extends('layout')
@section('content')
    <show-tweets :search="false" :user={{ $user->id }} />

@endsection
