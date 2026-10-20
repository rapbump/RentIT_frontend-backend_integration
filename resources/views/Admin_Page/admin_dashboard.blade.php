<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../../css/Admin_Page_CSS/admin_page.css" />
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Outfit:wght@100..900&display=swap"
        rel="stylesheet" />
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <title>Document</title>
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
                    <li class="active">
                        <a href="{{ route('admin.dashboard')}}" class="links">
                            <ion-icon name="bar-chart-outline"></ion-icon>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav">
                        <a href="{{ route('admin.stores')}}" class="links">
                            <ion-icon name="bag-handle-outline"></ion-icon>
                            Sellers & Stores
                        </a>
                    </li>
                    <li class="nav">
                        <a href="{{ route('admin.rentals')}}" class="links">
                            <ion-icon name="document-text-outline"></ion-icon>
                            Rentals
                        </a>
                    </li>
                    <li class="nav">
                        <a href="{{ route('admin.settings')}}" class="links">
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
            <div class="main-cont-header">
                <div class="right-side">
                    <h2 class="head">Welcome</h2>
                    <p class="intro">
                        Welcome to the RentIT administration command center. Here is your
                        overview for Bulacan tech rentals.
                    </p>
                </div>
                <div class="left-side">
                    <p>Today: <span id="dateToday"></span></p>
                </div>
            </div>
            <div class="main-cont">
                <div class="boxes-container">
                    @foreach ($metrics as $metric)
                        <div class="boxes">
                            <div class="box-head">
                                <p class="t-stores">{{ $metric['label'] }}</p>
                                <div class="ion-icon-box">
                                    <ion-icon name="{{ $metric['icon'] }}"></ion-icon>
                                </div>
                            </div>
                            <div class="box-cont">
                                <p class="total" data-stat="{{ $metric['key'] }}">{{ $metric['value'] }}</p>
                                <p class="green">{{ $metric['detail'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="activity-feed">
                    <div class="activity-feed-head">
                        <h2 class="act-head">Recent Activity Feed</h2>
                    </div>
                    <div class="activity-lists">
                        @forelse ($recentActivity as $activity)
                            <div class="activity-item{{ $loop->iteration }}">
                                <div class="icon">
                                    <ion-icon name="{{ $activity['icon'] }}"></ion-icon>
                                </div>
                                <div class="contents">
                                    <div class="whos">
                                        <p class="who">{{ $activity['actor'] }} <span>{{ $activity['verb'] }} {{ $activity['subject'] }}</span></p>
                                    </div>
                                    <div class="date-and-time">
                                        <p class="date">{{ $activity['createdAt']->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="date">No recent activity yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>
<script src="/js/Admin-Page/admin.js"></script>

</html>