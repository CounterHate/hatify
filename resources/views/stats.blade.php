@extends('layout')
@section('content')
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Uzytkownik</th>
                <th scope="col">Email</th>
                <th scope="col">Anotował</th>
                <th scope="col">Zweryfikował</th>
                <th scope="col">Nie był pewny</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <th scope="row">{{ $user->id }}</th>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->anotatedCount() }}</td>
                    <td>{{ $user->verifiedCount() }}</td>
                    <td>{{ $user->notSureCount() }}</td>
                </tr>
            @endforeach

        </tbody>
    </table>
    <stats-view></stats-view>
@endsection
