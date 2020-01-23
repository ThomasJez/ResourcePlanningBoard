<div class="row">
    <div class="col-12">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="#">Maintainance Tools</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item {{ Route::is('termedit.show') ? 'active' : '' }}">
                        <a class="nav-link" href="{{route('termedit.show')}}">Change Terms</a>
                    </li>
                    <li class="nav-item {{ Route::is('cleanup.show') ? 'active' : '' }}">
                        <a class="nav-link" href="{{route('cleanup.show')}}">Delete old Activities</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('ganttchart')}}">Call the App</a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>
