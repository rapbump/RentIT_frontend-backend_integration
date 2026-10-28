<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <link rel="stylesheet" href="{{ asset('css/Seller-Page_CSS/settings.css') }}">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Outfit:wght@100..900&display=swap"
        rel="stylesheet">
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <title>Settings</title>
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
</head>

<body>
    <header>
        <nav class="nav-bar">
            <div class="logo">
                <img src="{{ asset('images/logo-icon-wrap.png') }}" alt="">
                <div class="name">
                    <h2 class="black">Rent</h2>
                    <h2 class="orange">IT</h2>
                </div>
            </div>
            <div class="nav-cont">
                <ul>
                    <li class="nav">
                        <a href="{{ route('lender.dashboard')}}" class="links">
                            <ion-icon name="bar-chart-outline"></ion-icon>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav">
                        <a href="{{ route('lender.store')}}" class="links">
                            <ion-icon name="bag-handle-outline"></ion-icon>
                            My Store
                        </a>
                    </li>
                    <li class="nav">
                        <a href="{{ route('lender.gadgets')}}" class="links">
                            <ion-icon name="phone-portrait-outline"></ion-icon>
                            Gadgets
                        </a>
                    </li>
                    <li class="nav">
                        <a href="{{ route('lender.renters')}}" class="links">
                            <ion-icon name="people-outline"></ion-icon>
                            Renters
                        </a>
                    </li>
                    <li class="nav">
                        <a href="{{ route('lender.transaction')}}" class="links">
                            <ion-icon name="document-outline"></ion-icon>
                            Transactions
                        </a>
                    </li>
                    <li class="active">
                        <a href="{{ route('lender.settings')}}" class="links">
                            <ion-icon name="settings-outline"></ion-icon>
                            Settings
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <main>
        <section>
            <div class="settings-header">
                <div class="head-right">
                    <h1>Settings</h1>
                    <p>Update your display name, destination, and password.</p>
                </div>
            </div>
            @if (session('status'))
                <p class="form-success" role="status">{{ session('status') }}</p>
            @endif
            <div class="main-cont settings-grid">
                <div class="card settings-card">
                    <h2>Profile</h2>
                    <p>Display name: <strong>{{ $user->name }}</strong></p>
                    <form action="{{ route('lender.settings.profile') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="field">
                            <label for="name">Display Name</label>
                            <input class="cont" id="name" name="name" type="text" value="{{ old('name', $user->name) }}" maxlength="255" required>
                            @error('name')
                                <small class="form-error">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="field">
                            <label for="location">Destination</label>
                            <input class="cont" id="location" name="location" type="text" value="{{ old('location', $user->location) }}" maxlength="255" placeholder="Enter your destination or location">
                            @error('location')
                                <small class="form-error">{{ $message }}</small>
                            @enderror
                        </div>
                        <button type="submit">Save Profile</button>
                    </form>
                </div>
                <div class="pass-sec settings-card">
                    <h3>Password</h3>
                    <p>Change your account password.</p>
                    @if ($errors->hasAny(['current_password', 'new_password']))
                        <div class="form-errors" role="alert">
                            <ul>
                                @foreach ($errors->get('current_password') as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                                @foreach ($errors->get('new_password') as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('lender.settings.password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="field">
                            <label for="current_password">Current Password</label>
                            <input class="cont" id="current_password" name="current_password" type="password" autocomplete="current-password" required>
                        </div>
                        <div class="field">
                            <label for="new_password">New Password</label>
                            <input class="cont" id="new_password" name="new_password" type="password" autocomplete="new-password" minlength="8" required>
                        </div>
                        <div class="field">
                            <label for="new_password_confirmation">Confirm New Password</label>
                            <input class="cont" id="new_password_confirmation" name="new_password_confirmation" type="password" autocomplete="new-password" minlength="8" required>
                        </div>
                        <button type="submit">Update Password</button>
                    </form>
                </div>
            </div>
            <div class="action-card settings-card">
                <h3>Account Actions</h3>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit">
                        <ion-icon name="exit-outline"></ion-icon>
                        Sign Out
                    </button>
                </form>
            </div>
        </section>
    </main>
</body>

</html>