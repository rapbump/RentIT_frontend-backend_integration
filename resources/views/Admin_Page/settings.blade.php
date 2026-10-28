<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="{{ asset('css/Admin_Page_CSS/admin_page.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/Admin_Page_CSS/admin_settings.css') }}" />
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Outfit:wght@100..900&display=swap"
        rel="stylesheet" />
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <title>Admin Settings</title>
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
</head>

<body>
    <header>
        <nav class="nav-bar">
            <div class="logo">
                <img src="{{ asset('images/logo-icon-wrap.png') }}" alt="" />
                <div class="name">
                    <h2 class="black">Rent</h2>
                    <h2 class="orange">IT</h2>
                </div>
            </div>
            <div class="nav-cont">
                <ul>
                    <li class="nav">
                        <a href="{{ route('admin.dashboard') }}" class="links">
                            <ion-icon name="bar-chart-outline"></ion-icon>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav">
                        <a href="{{ route('admin.stores') }}" class="links">
                            <ion-icon name="bag-handle-outline"></ion-icon>
                            Sellers &amp; Stores
                        </a>
                    </li>
                    <li class="nav">
                        <a href="{{ route('admin.rentals') }}" class="links">
                            <ion-icon name="document-text-outline"></ion-icon>
                            Rentals
                        </a>
                    </li>
                    <li class="active">
                        <a href="{{ route('admin.settings') }}" class="links">
                            <ion-icon name="settings-outline"></ion-icon>
                            Settings
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <main>
        <section class="admin-settings">
            <div class="settings-header">
                <h1>Admin Settings</h1>
                <p>Manage your account and platform defaults.</p>
            </div>

            @if (session('status'))
                <p class="settings-success" role="status">{{ session('status') }}</p>
            @endif

            <div class="settings-grid">
                <div class="settings-card">
                    <h2>Admin Profile</h2>
                    <form action="{{ route('admin.settings.profile') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="settings-field">
                            <label for="name">Display name</label>
                            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" maxlength="255" required>
                            @error('name')
                                <small class="settings-error">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="settings-field">
                            <label for="email">Email address</label>
                            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" maxlength="255" required>
                            @error('email')
                                <small class="settings-error">{{ $message }}</small>
                            @enderror
                        </div>
                        <button type="submit">Save Profile</button>
                    </form>
                </div>

                <div class="settings-card">
                    <h2>Change Password</h2>
                    <form action="{{ route('admin.settings.password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="settings-field">
                            <label for="current_password">Current password</label>
                            <input id="current_password" name="current_password" type="password" autocomplete="current-password" required>
                            @error('current_password')
                                <small class="settings-error">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="settings-field">
                            <label for="new_password">New password</label>
                            <input id="new_password" name="new_password" type="password" autocomplete="new-password" minlength="8" required>
                            @error('new_password')
                                <small class="settings-error">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="settings-field">
                            <label for="new_password_confirmation">Confirm new password</label>
                            <input id="new_password_confirmation" name="new_password_confirmation" type="password" autocomplete="new-password" minlength="8" required>
                        </div>
                        <button type="submit">Update Password</button>
                    </form>
                </div>

                <div class="settings-card">
                    <h2>Platform Defaults</h2>
                    <p class="settings-note">This stores the platform default rate. Each rental transaction keeps the fee amount saved on its record.</p>
                    <form action="{{ route('admin.settings.platform') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="settings-field">
                            <label for="platform_fee_percentage">Default platform fee (%)</label>
                            <input
                                id="platform_fee_percentage"
                                name="platform_fee_percentage"
                                type="number"
                                min="0"
                                max="100"
                                step="0.01"
                                value="{{ old('platform_fee_percentage', $platformSetting->platform_fee_percentage) }}"
                                required>
                            @error('platform_fee_percentage')
                                <small class="settings-error">{{ $message }}</small>
                            @enderror
                        </div>
                        <button type="submit">Save Platform Settings</button>
                    </form>
                </div>
            </div>

            <div class="settings-signout">
                <h2>Account Actions</h2>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit">Sign Out</button>
                </form>
            </div>
        </section>
    </main>
</body>

</html>
