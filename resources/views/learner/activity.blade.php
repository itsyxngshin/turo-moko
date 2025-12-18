@extends('layouts.learner-layout')

@section('title', 'Activity')
@section('page-title', 'Course Activity')

@section('content')
    <livewire:learner.assignment-component :id="$id" />
@endsection
