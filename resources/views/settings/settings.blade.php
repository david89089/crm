@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Настройки</div>

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
                        @if (session('status') == 'two-factor-authentication-enabled')
                            <div class="mb-4 font-medium text-sm text-green-600">
                                Two factor authentication has been enabled.
                            </div>
                        @endif

                        @if(empty(auth()->user()->two_factor_secret))
                            <form action="{{route('two-factor.enable')}}" method="post">
                                @csrf
                                <button type="submit" class="btn btn-primary">Включить 2AF</button>
                            </form>
                        @else
                            <form action="{{route('two-factor.disable')}}" method="post">
                                @csrf
                                @method('delete')

                                <div>
                                    {!! auth()->user()->twoFactorQrCodeSvg() !!}
                                </div>

                                <button type="submit" class="btn btn-primary mt-2">Отключить 2AF</button>
                            </form>
                        @endif

                        <p class="mt-4">Управление беседой</p>
                        @if(!Auth::user()->chat)
                            <form method="POST" action="{{ route('chat.store') }}">
                                @csrf
                                <input type="hidden" name="id" value="{{ auth()->id() }}">
                                <button type="submit" class="btn btn-success">Создать беседу</button>
                            </form>
                        @elseif(Auth::user()->chat)
                            <form method="POST" action="{{ route('chat.destroy') }}">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="id" value="{{ auth()->id() }}">
                                <button type="submit" class="btn btn-danger">Удалить беседу</button>
                            </form>
                        @endif

                    </div>
                </div>
            </div>
            <notifications v-bind:user_id="{{Auth::user()->id}}"></notifications>
        </div>
    </div>
@endsection
