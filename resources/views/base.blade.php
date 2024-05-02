<!DOCTYPE html = "omar">
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
         <<link href="https://unpkg.com/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
         <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
        <link rel="stylesheet" href="{{ asset('assets/style.css')}}">
    </head>
    <body class="bg-dark d-flex flex-column min-vh-100">
        <div class="container bg-white">
            <header class="text-center"></header>
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="margin-top:5px;">
                    <div class="container-fluid">
                    <a class="navbar-brand d-flex align-items-center" href="{{ route('animes.index') }}">
                        <img src="{{URL::asset('/assets/logo.png')}}"  alt="logo..." width="110">
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav mr-auto">
                            @if(Auth::check())
                            <li class="nav-item active">
                                <a class="nav-link {{{ Route::current()->getName() == 'animes.user_list' ? 'active' : '' }}}" href="{{ route('animes.user_list', ['userId' => Auth::id()]) }}">My Liste</a>
                            </li>
                            @endif
                            <li class="nav-item active">
                                <a class="nav-link {{{ Route::current()->getName() == 'animes.index' ? 'active' : '' }}}" href="{{ route('animes.index') }}">Animes</a>
                            </li>
                            <li class="nav-item active">
                                <a class="nav-link {{{ Route::current()->getName() == 'studios.index' ? 'active' : '' }}}" href="{{ route('studios.index') }}">Studios</a>
                            </li>
                            <li class="nav-item active">
                                <a class="nav-link {{{ Route::current()->getName() == 'status.index' ? 'active' : '' }}}" href="{{ route('status.index') }}">Status</a>
                            </li>
                            @can('isAdmin')
        <li class="nav-item">
            <a class="nav-link {{{ Route::current()->getName() == 'animes.create' ? 'active' : '' }}}" href="{{ route('animes.create') }}">New Anime</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{{ Route::current()->getName() == 'studios.create' ? 'active' : '' }}}" href="{{ route('studios.create') }}">New Studio</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{{ Route::current()->getName() == 'status.create' ? 'active' : '' }}}" href="{{ route('status.create') }}">New Status</a>
        </li>
    @endcan

                    </ul>
                    <ul class="navbar-nav ms-auto d-flex align-items-center">

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">

                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark">
                                <li>
                                    <form action="{{ route('login') }}" method="GET">
                                        @csrf
                                        <button type="submit" class="btn btn-link dropdown-item text-white" style="text-decoration: none;">
                                            <i class="fa-solid fa-sign-in-alt"></i> Login
                                        </button>
                                    </form>
                                </li>
                                <li>
                                    <form action="{{ route('register') }}" method="GET">
                                        @csrf
                                        <button type="submit" class="btn btn-link dropdown-item text-white" style="text-decoration: none;">
                                            <i class="fa-solid fa-user-plus"></i> Register
                                        </button>
                                    </form>
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-link dropdown-item text-white" style="text-decoration: none;">
                                            <i class="fa-solid fa-sign-out-alt"></i> Exit
                                        </button>
                                    </form>
                                </li>
                            </ul>

                        </li>
                    </ul>
                </div>
            </nav>
            <div class="content mt-5">

                @yield('content')
            </div>
        </div>

        <footer class="mt-5 text-light bg-dark py-4">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <h5>About Us</h5>
                        <p>Cool anime Website</p>
                    </div>
                    <div class="col-md-4">
                        <h5>Contact Us</h5>
                        <ul class="list-unstyled">
                            <li>Email: contact@example.com</li>
                            <li>Phone: +1234567890</li>
                            <li>Address: 123 Street, City, Country</li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h5>Follow Us</h5>
                        <ul class="list-unstyled">
                            <li><a href="#"><i class="fab fa-facebook"></i> Facebook</a></li>
                            <li><a href="#"><i class="fab fa-twitter"></i> Twitter</a></li>
                            <li><a href="#"><i class="fab fa-instagram"></i> Instagram</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="text-center py-2">
                <small>&copy; 2024 Animes. All rights reserved.</small>
            </div>
        </footer>


    <script src="https://kit.fontawesome.com/f2866a4178.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>






































</body>
</html>
