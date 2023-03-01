@extends('argon')
@section('content')
    <admin-panel @can('start_stream')
            can_start_stream='true'
        @endcan
        @cannot('start_stream')
            can_start_stream='false'
        @endcannot
        @can('remove_keywords')
            can_remove_keywords='true'
        @endcan
        @cannot('remove_keywords')
            can_remove_keywords='false'
        @endcannot></admin-panel>
@endsection
