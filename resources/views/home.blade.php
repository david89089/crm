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
                            <th scope="col">Зайти в чат</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <th scope="row">{{$user->id}}</th>
                                    <td>{{$user->name}}</td>
                                    @if($user->id != auth()->id())
                                        <td>
                                            @if(\App\Service\ChatService::checkChat(auth()->id(), $user->id))
                                                <form action="{{route('chat.check')}}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="companion_id" value="{{ $user->id }}">
                                                    <button type="submit" class="btn btn-primary">Зайти в чат</button>
                                                </form>
                                            @else
                                                <form action="{{route('chat.store')}}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="companion_id" value="{{ $user->id }}">
                                                    <button type="submit" class="btn btn-primary">Создать чат</button>
                                                </form>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
