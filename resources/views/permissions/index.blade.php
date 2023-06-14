@extends('argon')
@section('content')
    <div class="card card-frame" style="margin-bottom: 16px">
        <div class="card-body">
            <!-- <div v-if="$can('anotate')">You can anotate posts.</div> -->
            <div class="table-responsive">
                <table class="table table-striped align-items-center mb-0">
                    <thead>
                        <tr>
                            <th scope="col"
                                class="
                      text-uppercase text-secondary text-xxs
                      font-weight-bolder
                      opacity-7
                    ">
                                #
                            </th>
                            <th scope="col"
                                class="
                      text-uppercase text-secondary text-xxs
                      font-weight-bolder
                      opacity-7
                    ">
                                Użytkownik
                            </th>
                            <th scope="col"
                                class="
                  text-uppercase text-secondary text-xxs
                  font-weight-bolder
                  opacity-7
                ">
                                Email
                            </th>
                            @foreach ($permissions as $p)
                                <th scope="col"
                                    class="
                    text-uppercase text-secondary text-xxs
                    font-weight-bolder
                    opacity-7
                  ">
                                    {{ $p->name }}
                                </th>
                            @endforeach

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <th scope="row" class="mb-0 text-xs">{{ $loop->index + 1 }}
                                </th>
                                <td>
                                    <a href="/permissions/edit/{{ $user->id }}"><span
                                            class="mb-0 text-xs">{{ $user->name }}</span></a>
                                </td>
                                <td>
                                    <a href="/permissions/edit/{{ $user->id }}"><span
                                            class="mb-0 text-xs">{{ $user->email }}</span></a>
                                </td>
                                @foreach ($permissions as $p)
                                    @if ($user->can($p->name))
                                        <td class="mb-0 text-xs"><span class="badge bg-success">Yes</span></td>
                                    @else
                                        <td class="mb-0 text-xs"><span class="badge bg-danger">No</span></td>
                                    @endif
                                @endforeach

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
