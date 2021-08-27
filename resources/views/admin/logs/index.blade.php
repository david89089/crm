@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Список логов</div>
                    <Logs v-bind:logs="{{ json_encode($logs) }}"></Logs>
                </div>
            </div>
            <notifications v-bind:user_id="{{Auth::user()->id}}"></notifications>
        </div>
    </div>
@endsection
