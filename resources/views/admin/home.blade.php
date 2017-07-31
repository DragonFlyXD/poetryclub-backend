@extends('layouts.admin')

@section('content')
    <count-to
        :startVal="0"
        :endVal="2017"
        :duration="4000"
    ></count-to>
@endsection