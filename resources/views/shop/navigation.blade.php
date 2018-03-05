<div id="navbar">
<nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top">
    <div class="container">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" href="{{route('home')}}">Home</a>
        </li>
        @if(auth()->check())
            <li class="nav-item">
                <a class="nav-link" href="{{route('user.change')}}">{{ucfirst(auth()->user()->name)}}</a>
            </li>
        @else
            <li class="nav-item">
                <a class="nav-link" href="{{route('registration.index')}}">Registration</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('login')}}">Login</a>
            </li>
        @endif
        <li class="nav-item">
            <a class="nav-link" href="{{route('logout')}}">Logout</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{route('products')}}">Products</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{route('contacts')}}">Contacts</a>
        </li>
        <li class="nav-item">
            <nav class="navbar navbar-expand-sm">
                <form class="form-inline" action="{{route('search')}}" method="GET">
                    <input class="form-control" type="text" placeholder="Search">
                    <button class="btn btn-success" type="submit">@lang('message.find')</button>
                </form>
            </nav>
        </li>
    @foreach(\LaravelLocalization::getSupportedLocales() as $locale => $properties)
            <li class="nav-item">
                <a class="nav-link" rel="alternate" hreflang="{{ $locale }}" href="{{ \LaravelLocalization::getLocalizedURL($locale, null, [], true) }}">
                    {{ $properties['name'] }}
                </a>
            </li>
        @endforeach
    </ul>
    </div>
</nav>
</div>




