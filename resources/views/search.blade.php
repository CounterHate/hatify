@extends('argon')
@section('content')
    <search-content data_id='{{ $data_id ?? null }}' media='{{ $media }}' author='{{ $author_username ?? null }}'
        content='{{ $content ?? null }}' category='{{ $category ?? null }}' declination='{{ $declination ?? null }}'
        @can('see_authors')
            can_see_authors='true' 
        @endcan
        @cannot('see_authors')
            can_see_authors='false' 
        @endcannot
        @can('download')
            can_download='true'
        @endcan
        @cannot('download')
            can_download='false'
        @endcannot
        @can('search_keywords')
            can_search_keywords='true'
        @endcan
        @cannot('search_keywords')
            can_search_keywords='false'
        @endcannot />
@endsection
