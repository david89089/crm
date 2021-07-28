@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Легкая версия чатика :)</div>

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
                    <form action="{{route('chat.message.store')}}" method="post">
                        @csrf
                        <table class="table">
                            <div style="height: 60vh;width: 100%;overflow-y: auto">
                                @auth
                                    @foreach($chat->messages as $message)
                                        @if($message->user->id == auth()->id())
                                            <div class="border border-secondary alert alert-light col-md-6 " role="alert" style="margin-left: 50%; width: 50%;"  >
                                                <div style="display: flex;justify-content: space-between;">
                                                    <p style="margin: 0;">{{$message->user->name}}</p>
                                                    <p style="margin: 0;">| {{$message->created_at}}</p>
                                                </div>
                                                <hr style="margin: 4px 0;">
                                                <p class="mb-0">{{$message->message}}</p>
                                            </div>
                                        @else
                                            <div class="border border-secondary alert alert-light col-6" role="alert" style="margin-right: 50%; width: 50%;">
                                                <div style="display: flex;justify-content: space-between;">
                                                    <p style="margin: 0;">{{$message->user->name}}</p>
                                                    <p style="margin: 0;">| {{$message->created_at}}</p>
                                                </div>
                                                <hr style="margin: 4px 0;">
                                                <p class="mb-0">{{$message->message}}</p>
                                            </div>
                                        @endif
                                    @endforeach
                                @endauth
                            </div>
                                <div class="row g-3 align-items-center">
                                    <div class="col-9">
                                        <input type="hidden" name="chat_id" value="{{ $chat->id }}">
                                        <input class="form-control" type="text" name="message" placeholder="Ваше величество напишите сообщение" required>
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-primary">Отправить</button>
                                    </div>
                                </div>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
