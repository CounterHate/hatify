@extends('layout')
@section('content')
    <search-content data_id='{{ $data_id ?? null }}' media='{{ $media }}'
        author='{{ $author_username ?? null }}' />
@endsection
