<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid">
        <div class="d-flex align-items-center">
            <button class="btn btn-link sidebar-toggle">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>

        <div class="d-flex align-items-center">
            <!-- Notifications -->
            <div class="dropdown mr-3">
                <button class="btn btn-link dropdown-toggle" type="button" id="notificationsDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa-solid fa-bell"></i>
                    <span class="badge badge-danger">3</span>
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="notificationsDropdown">
                    <h6 class="dropdown-header">Notifications</h6>
                    <a class="dropdown-item" href="#">
                        <i class="fa-solid fa-exclamation-circle text-danger"></i>
                        Low stock alert for 5 products
                    </a>
                    <a class="dropdown-item" href="#">
                        <i class="fa-solid fa-shopping-cart text-info"></i>
                        3 new orders received
                    </a>
                    <a class="dropdown-item" href="#">
                        <i class="fa-solid fa-user text-success"></i>
                        2 new customers registered
                    </a>
                </div>
            </div>

            <!-- User Profile -->
            <div class="dropdown">
                <button class="btn btn-link dropdown-toggle" type="button" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="{{ asset('assets/images/user/1.jpg') }}" alt="user" class="rounded-circle" width="40">
                    <span class="ml-2">{{ auth()->user()->name }}</span>
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="{{ route('profile') }}">
                        <i class="fa-solid fa-user"></i> Profile
                    </a>
                    <a class="dropdown-item" href="{{ route('profile.change-password') }}">
                        <i class="fa-solid fa-key"></i> Change Password
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="fa-solid fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav> 