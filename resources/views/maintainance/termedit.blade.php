@extends('layouts.app')
@section('content')
    <div class="container">
        @include('maintainance.menu')
        <div class="row py-5 ml-5">
            <div class="col-6">
                <h1>Change Terms</h1>
            </div>
        </div>
        <form action={{route('termedit.update')}} method="post">
            @csrf
            @foreach($configEntries as $configEntry)
                <div class="row ml-5">
                    <div class="col-3">
                        {{ $configEntry->humanString }}
                    </div>
                    <div class="col">
                        <input type="text" name="termedit{{ $configEntry->id }}" value="{{ $configEntry->value }}">
                    </div>
                </div>
            @endforeach
            <div class="row pt-3 ml-5">
                <div class="col-1 m-1">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                <div class="col-1 m-1">
                    <button type="reset" class="btn btn-primary">Revert</button>
                </div>
            </div>
        </form>
    </div>
@endsection
