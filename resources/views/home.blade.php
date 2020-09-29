@extends('adminlte::page')

@section('title', 'LiveData DevOps Dashboard')

@section('content_header')
    <h1 class="m-0 text-dark">Dashboard</h1>
@stop

@section('content')
    <style>
        html,body {
           height:100%;
           margin-top:0;
           margin-bottom:0;
        }
        .h_iframe iframe {
           width:100%;
           height:100%;
        }
        .h_iframe {
           height: 100vh;
        }
    </style>
    <div class="container-fluid">
        <section class="h_iframe">
            <iframe src="{{route('dashboard')}}" frameborder="0" allowfullscreen></iframe>
        </section>
    </div>
@stop
