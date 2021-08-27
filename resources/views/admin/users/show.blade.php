@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card" style="width: 50rem">
                    <div class="card-header">Пользователь {{$user->name}}</div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                    </div>

                    <div class="info">
                        <div class="main-container">
                            @if($user->photo)
                                <img class="avatar" src="{{ $user->photo }}">
                            @else
                                <img class="avatar" src="https://pmdoc.ru/wp-content/uploads/default-avatar.png">
                            @endif

                            <form method="POST" action="{{ route('admin.users.update.status', ['status' => \App\Enums\UsersEnum::STATUS_ACCESS]) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                <button type="submit" class="btn btn-success size-btn">Принять</button>
                            </form>

                            <form method="POST" action="{{route('admin.users.update.status', ['status' => \App\Enums\UsersEnum::STATUS_REJECT])}}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                <button type="submit" class="btn btn-danger size-btn">Отказать</button>
                            </form>

                            <form method="POST" action="{{route('admin.users.update.status', ['status' => \App\Enums\UsersEnum::STATUS_DELETE])}}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                <button type="submit" class="btn btn-secondary size-btn">Удалить</button>
                            </form>
                        </div>

                        <div class="person">
                            <h2 class="text-bold">{{$user->name}},{{$user->age}}</h2>
                            <p>INFO</p>
                            <p class="text-margin">Birth date</p>
                            <p class="text-margin text-bold">{{$user->birth_date}}</p>
                            <p class="text-margin">E-mail</p>
                            <p class="text-margin text-bold">{{$user->email}}</p>
                            <p class="text-margin">Phone</p>
                            <p class="text-margin text-bold">{{$user->phone}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <notifications v-bind:user_id="{{Auth::user()->id}}"></notifications>
        </div>
    </div>
@endsection
