@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header" style="display:flex;justify-content: space-between;">
                        Список пользователей
                        <form action="{{route('admin.logs.index')}}" method="GET">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-sm">Посмотреть логи</button>
                        </form>
                    </div>
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


                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Имя</th>
                                <th scope="col">Действия</th>
                                <th scope="col">Статус</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <th scope="row">{{$user->id}}</th>
                                    <td>{{$user->name}}</td>
                                    <td>
                                        <form action="{{route('admin.users.show', $user->id)}}" method="GET">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $user->id }}">
                                            <button type="submit" class="btn btn-primary">Посмотреть анкету</button>
                                        </form>
                                    </td>
                                    <td>
                                        {{\App\Enums\UsersEnum::getNameByStatus($user->status ?? 0)}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{$users->links("pagination::bootstrap-4")}}
                </div>
            </div>
            <notifications v-bind:user_id="{{Auth::user()->id}}"></notifications>
        </div>
    </div>
@endsection
