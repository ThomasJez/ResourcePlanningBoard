@extends('layouts.app')
@section('content')
    <div class="container">
         @include('maintainance.menu')
        <div class="row justify-content-md-center pt-5">
            <div class="col-8">
                <h1>Delete old Activities from Database</h1>
            </div>
        </div>
        @if(count($pastActivities) > 0)
            <div class="row pb-1">
                <div class="col">Start</div>
                <div class="col">Duration in Days</div>
                <div class="col">Assigned Resource</div>
                <div class="col">Assigned Rule</div>
            </div>
            @foreach($pastActivities as $activity)
                <div class="row">
                    <div class="col">{{ $activity->start }}</div>
                    <div class="col">{{ $activity->duration }}</div>
                    <div class="col">{{ $activity->resource->name }}</div>
                    <div class="col">{{ $activity->rule->name }}</div>
                </div>
            @endforeach
            <div class="row pt-3">
                <div class="col">
                    The activities above are ending before the scheduling starts at {{ $start }}. Do you want to delete them?
                </div>
            </div>
            <div class="row pt-2">
                <div class="col alert alert-danger" role="alert">Be careful! You cannot reverse this.</div>
                <div class="col-3">
                    <form action={{route('cleanup.update')}} method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete all</button>
                    </form>
                </div>
            </div>
        @else
            <div class="row pt-3">
                <div class="col">
                    There are'nt any activities ending before the scheduling start at {{ $start }}.
                </div>
            </div>
        @endif
    </div>
@endsection
