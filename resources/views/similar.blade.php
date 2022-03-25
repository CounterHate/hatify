@extends('layout')
@section('content')
    <similar-tweets data_id='{{ $data_id }}' media='{{ $media }}' />
@endsection
