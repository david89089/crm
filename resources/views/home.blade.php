@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Имя</th>
                                @if(Auth::user()->chat)
                                    <th scope="col">Пригласить</th>
                                @endif
                                <th scope="col">Беседы</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <th scope="row">{{$user->id}}</th>
                                    <td>{{$user->name}}</td>
                                    @if(Auth::user()->chat)
                                        @if($user->id != auth()->id())
                                            @if(\App\Repository\ChatRepository::isChatUser(Auth::user()->chat, $user->id))
                                                <td>
                                                    <form method="POST" action="{{route('chat.delete.user', ['chat' => Auth::user()->chat->id])}}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                                        <button type="submit" class="btn btn-warning">Выгнать</button>
                                                    </form>
                                                </td>
                                            @else
                                                <td>
                                                    <form method="POST" action="{{route('chat.add.user', ['chat' => Auth::user()->chat->id])}}">
                                                        @csrf
                                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                                        <button type="submit" class="btn btn-warning">Пригласить</button>
                                                    </form>
                                                </td>
                                            @endif
                                        @else
                                            <td>Нельзя приглашать себя</td>
                                        @endif
                                    @endif
                                    @if($user->chat)
                                        @if(\App\Repository\ChatRepository::isChatUser($user->chat, auth()->id()))
                                            <td>
                                                <form method="GET" action="{{route('chat.index', ['chat' => $user->chat->id])}}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-primary">Зайти</button>
                                                </form>
                                            </td>
                                        @else
                                            <td></td>
                                        @endif
                                    @else
                                        <td></td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{$users->links("pagination::bootstrap-4")}}
                </div>
            </div>
        </div>
        <notifications v-bind:user_id="{{Auth::user()->id}}"></notifications>
    </div>
</div>
@endsection
