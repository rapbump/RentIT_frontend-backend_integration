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
                    <p>Manage your seller account, store preferences, and security.</p>
                </div>
                <div class="head-left">
                    <form action="">
                        <button>Save Changes</button>
                    </form>
                </div>
            </div>
            <div class="main-cont">
                <div class="right-side">
                    <div class="card">
                        <h2>Account Information</h2>
                        <div class="profile">
                            <img src="" alt="">
                            <div class="button">
                                <input type="file">
                                <button>Change Button</button>
                            </div>
                        </div>
                        <div class="full-name">
                            <div class="name-container">
                                <p>First Name</p>
                                <div class="container">
                                    <p></p>
                                </div>
                            </div>
                            <div class="name-container">
                                <p>Last Name</p>
                                <div class="container">
                                    <p></p>
                                </div>
                            </div>
                        </div>
                        <div class="email-cont">
                            <p>Email Address</p>
                            <div class="container">
                                <p></p>
                            </div>
                        </div>
                        <div class="full-name">
                            <div class="name-container">
                                <p>Contact Number</p>
                                <div class="container">
                                    <p></p>
                                </div>
                            </div>
                            <div class="name-container">
                                <p>Location</p>
                                <div class="container">
                                    <p></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <h2>Store Preferences</h2>
                        <div class="store-name">
                            <p>Store Name</p>
                            <div class="s-name-cont">
                                <p></p>
                            </div>
                        </div>
                        <div class="about">
                            <p>About yur store</p>
                            <div class="about-cont">
                                <p></p>
                            </div>
                        </div>
                        <div class="full-name">
                            <div class="name-container">
                                <p>Operating Days</p>
                                <div class="container">
                                    <p></p>
                                </div>
                            </div>
                            <div class="name-container">
                                <p>Business Hours</p>
                                <div class="container">
                                    <p></p>
                                </div>
                            </div>
                        </div>
                        <div class="about">
                            <p>Meetup Locations</p>
                            <div class="about-cont">
                                <p></p>
                            </div>
                            <p>Only verified meetup locations are shown to renters.</p>
                        </div>
                    </div>
                </div>
                <div class="left-side">
                    <div class="verification-card">
                        <h3>Seller Verification</h3>
                        <div class="status">
                            <p>Verify Lender</p>
                        </div>
                        <p>Your identity and store have been verified. Keep your account details up to date to maintain renter trust.</p>
                        <div class="owner">
                            <p>Account owner</p>
                            <p class="name"></p>
                        </div>
                        <div class="store-status">
                            <p>Store status</p>
                            <p class="name"></p>
                        </div>
                    </div>
                    <div class="pass-sec">
                        <h3>Password & Security</h3>
                        <p>Use a strong password to protect your seller account.</p>
                        <div class="field">
                            <p>Current Password</p>
                            <div class="cont">
                                <p></p>
                            </div>
                        </div>
                        <div class="field">
                            <p>New Password</p>
                            <div class="cont">
                                <p></p>
                            </div>
                        </div>
                        <div class="field">
                            <p>Confirm New Password</p>
                            <div class="cont">
                                <p></p>
                            </div>
                        </div>
                        <form action="">
                            <button type="submit"> Update Password</button>
                        </form>
                    </div>
                    <div class="action-card">
                        <h3>Account Actions</h3>
                        <a href="">
                            <button>
                                <ion-icon name="exit-outline"></ion-icon>
                                Sign Out
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>

</html>