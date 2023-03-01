@extends('argon')
@section('content')
    <div class="card card-frame" style="margin-bottom: 16px">
        <div class="card-body">
            <!-- <div v-if="$can('anotate')">You can anotate posts.</div> -->
            <form action="/permissions/update/{{ $user->id }}" method="POST">
                @csrf
                <label>
                    {{ $user->name }}
                </label>
                @foreach ($permissions as $p)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" @if ($user->can($p->name)) checked @endif
                            id="{{ $p->name }}" name="{{ $p->name }}">
                        <label class="form-check-label" for="flexCheckDefault">
                            {{ $p->name }}
                        </label>
                    </div>
                @endforeach
                <button type="submit" class="btn btn-primary">
                    Zapisz
                </button>

            </form>
        </div>
    </div>
@endsection
