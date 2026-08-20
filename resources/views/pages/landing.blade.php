@extends('layouts.guest')

{{-- Active le design system PEUB sur cette page uniquement.
     Le layout guest est partage avec des vues non encore migrees. --}}
@section('html-attrs', 'data-ds')

@section('title', 'PEUB - Connecter l\'Excellence aux Opportunités')

@section('content')
@include('landing.partials.hero')

@include('landing.partials.stats')

@include('landing.partials.about')

@include('landing.partials.opportunities')

@include('landing.partials.partners')

@include('landing.partials.news')
@endsection 