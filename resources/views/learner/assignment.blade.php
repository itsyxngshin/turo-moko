@extends('layouts.layout2')

@section('title', 'Assignment')
@section('page-title', 'Course Assignment')

@section('content')
    <livewire:learner.assignment :id="$id" />
@endsection
