<header class="p-3 bg-dark text-white">
  <div class="container">
    <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-between">

      <ul class="nav col-sm-auto col-lg-auto me-lg-auto justify-content-center mb-0">
        <li><a href="/" class="nav-link px-2 text-secondary">Home</a></li>
        <li><a href="#" class="nav-link px-2 text-white">FAQs</a></li>
        <li><a href="#" class="nav-link px-2 text-white">About</a></li>
      </ul>

{{--      <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3">--}}
{{--        <input type="search" class="form-control form-control-dark" placeholder="Search..." aria-label="Search">--}}
{{--      </form>--}}

      @auth
        {{auth()->user()->name}}

          <a href="{{ route('logout.perform') }}" class="btn btn-outline-light">Logout</a>

      @endauth

      @guest
        <div class="text-end">
          <a href="{{ route('login.perform') }}" class="btn btn-outline-light me-2">Login</a>
          <a href="{{ route('register.perform') }}" class="btn btn-warning">Sign-up</a>
        </div>
      @endguest
    </div>
  </div>
</header>
