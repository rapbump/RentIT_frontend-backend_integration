<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Outfit:wght@100..900&display=swap"
        rel="stylesheet">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="../../css/Seller-Page_CSS/gadgets.css">
    <title>Gadgets</title>
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
                    <li class="active">
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
                    <li class="nav">
                        <a href="{{ route('lender.settings')}}" class="links">
                            <ion-icon name="settings-outline"></ion-icon>
                            Settings
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <main class="left">
        <section>
            <div class="gadgets_head">
                <div class="txt">
                    <h2>My Gadgets</h2>
                    <p>Manage and edit your listed technology equipment.</p>
                </div>
                <div class="txt_btn">
                    <a class="add-gadget-btn" href="{{ route('lender.add-gadget') }}">
                        <ion-icon name="add-outline"></ion-icon>
                        Add Gadget
                    </a>
                </div>
            </div>
            @if (session('success'))
                <p class="success-message" role="status">{{ session('success') }}</p>
            @endif
            <div class="search-box">
                <ion-icon name="search-outline"></ion-icon>
                <input type="search" placeholder="Search products...">
            </div>
            <div class="container">
                @forelse ($gadgets as $gadget)
                    <div class="box">
                        <div class="name-price-pic">
                            <div class="pic-box">
                                <img src="{{ $gadget->photo_path ? \Illuminate\Support\Facades\Storage::url($gadget->photo_path) : asset('images/cp.png') }}" alt="{{ $gadget->name }}">
                            </div>
                            <div class="name-price">
                                <div class="category-status">
                                    <div class="category">
                                        <p>{{ strtoupper($gadget->category) }}</p>
                                    </div>
                                    <div class="status">
                                        <p>{{ $gadget->status }}</p>
                                    </div>
                                </div>
                                <div class="name">
                                    <p>{{ $gadget->name }}</p>
                                </div>
                                <div class="price">
                                    <p>{{ $gadget->rental_payment !== null ? '₱'.number_format((float) $gadget->rental_payment, 2).' / day' : 'Rate not set' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="modify-remove">
                            <span>{{ $gadget->condition ?? 'Condition not set' }}</span>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <h3>No gadgets listed yet</h3>
                        <p>Your saved and published gadget listings will appear here.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </main>
</body>

</html>
