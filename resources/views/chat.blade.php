@extends('layouts.app')

@section('content')
    @if(Auth::check())
        <Chat v-bind:chat="{{ json_encode($chat) }}" v-bind:user="{{ json_encode(Auth::user()) }}"></Chat>
    @endif
@endsection
