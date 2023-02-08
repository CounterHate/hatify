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
                                    <span class="mb-0 text-xs">{{ $user->name }}</span>
                                </td>
                                @if ($user->can('remove keywords'))
                                    <td class="mb-0 text-xs">Yes</td>
                                @else
                                    <td class="mb-0 text-xs">No</td>
                                @endif
                                @if ($user->can('start stream'))
                                    <td class="mb-0 text-xs">Yes</td>
                                @else
                                    <td class="mb-0 text-xs">No</td>
                                @endif
                                @if ($user->can('download'))
                                    <td class="mb-0 text-xs">Yes</td>
                                @else
                                    <td class="mb-0 text-xs">No</td>
                                @endif

                                @if ($user->can('search keywords'))
                                    <td class="mb-0 text-xs">Yes</td>
                                @else
                                    <td class="mb-0 text-xs">No</td>
                                @endif
                                @if ($user->can('see authors'))
                                    <td class="mb-0 text-xs">Yes</td>
                                @else
                                    <td class="mb-0 text-xs">No</td>
                                @endif
                                @if ($user->can('anotate'))
                                    <td class="mb-0 text-xs">Yes</td>
                                @else
                                    <td class="mb-0 text-xs">No</td>
                                @endif

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
