@extends('layout')
@section('content')
    <show-tweets :search="true" :user={{ $user->id }} />
@endsection
