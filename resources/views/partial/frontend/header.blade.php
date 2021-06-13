<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('frontend.index') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            <!-- Left Side Of Navbar -->
            <ul class="mr-auto nav justify-content-center">
                <li class="nav-link"><a href="{{ route('users.post.create') }}">Create Post</a></li>
                <li class="nav-link"><a href="{{ route('frontend.posts.show', 'about-us') }}">About Us</a></li>
                <li class="nav-link"><a href="{{ route('frontend.posts.show', 'our-vision') }}">Our Vision</a></li>
                <li class="nav-link"><a href="{{ route('frontend.contacts.create') }}">Contact Us</a></li>

                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        Categories <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        @foreach($global_categories as $global_category)
                            <a class="dropdown-item"
                               href="{{ route('frontend.category.posts', $global_category->slug) }}">{{ $global_category->name }}</a>
                        @endforeach
                        <form id="logout-form" action="{{ route('frontend.logout') }}" method="POST"
                              style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('frontend.login') }}">{{ __('Login') }}</a>
                    </li>
                    @if (Route::has('frontend.show_register_form'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('frontend.register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ auth()->user()->name }} <span class="caret"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ auth()->user()->isAdmin() ? route('admin.index') : route('users.dashboard')}}">Dashboard</a>
                            <a class="dropdown-item" href="{{ route('frontend.logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>
                            <form id="logout-form" action="{{ route('frontend.logout') }}" method="POST"
                                  style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

