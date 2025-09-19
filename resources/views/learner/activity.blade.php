@extends('layouts.layout2')

@section('title', 'Activity')
@section('page-title', 'Course Activity')

@section('content')
    <livewire:learner.activity :id="$id" />
@endsection
