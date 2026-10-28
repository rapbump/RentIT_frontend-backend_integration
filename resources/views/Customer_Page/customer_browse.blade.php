<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/Customer_Page_CSS/customer.css') }}">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Outfit:wght@100..900&display=swap"
        rel="stylesheet">
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <title>Browse</title>
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
</head>

<body>
    <header>
        <div class="logo">
            <img src="{{ asset('images/logo-icon-wrap.png') }}" alt="" />
            <div class="name">
                <h2 class="black">Rent</h2>
                <h2 class="orange">IT</h2>
            </div>
        </div>
        <div class="navs">
            <a href="{{ route('customer.browse') }} " class="active">Browse Gadgets</a>
            @if ($customer?->role === 'user')
                <a href="{{ route('customer.cart') }}">Cart ({{ $cartCount }})</a>
            @endif
            <a href="{{ url('rentals')}}">My Rentals</a>
        </div>
        <div class="profile">
            <div class="pic">
                <img src="{{ $customer?->avatar_path ? \Illuminate\Support\Facades\Storage::disk('public')->url($customer->avatar_path) : asset('images/76e11a76-2275-47ba-83c3-40969564e34d.jpg') }}" alt="">
            </div>
            <div class="name-stats">
                <p class="Uname">{{ $customer?->name ?? 'Welcome' }}</p>
                <p class="status">{{ $customer?->email_verified_at ? 'Verified Customer' : ($customer ? 'Customer Account' : 'Guest') }}</p>
            </div>
            <button
                class="menu-icon"
                id="menuIcon"
                type="button"
                aria-label="Open account menu"
                aria-expanded="false"
                aria-controls="dropdownMenu">
                <ion-icon name="menu-outline" aria-hidden="true"></ion-icon>
            </button>
            <div class="dropdown" id="dropdownMenu">
                @if ($customer)
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit">Log Out</button>
                    </form>
                @else
                    <a href="{{ route('login.form') }}">Log In</a>
                @endif
            </div>
        </div>
    </header>

    <main>
        <section class="Browse-gadgets">
            <form class="search-box" method="GET" action="{{ route('customer.browse') }}">
                <label class="visually-hidden" for="gadget-search">Search products</label>
                <ion-icon name="search-outline"></ion-icon>
                <input id="gadget-search" type="search" name="search" value="{{ $search }}" placeholder="Search products...">
                <label class="visually-hidden" for="gadget-category">Filter by category</label>
                <select id="gadget-category" name="category">
                    <option value="">All categories</option>
                    @foreach (['Smartphone', 'Tablet', 'Laptop', 'Camera'] as $availableCategory)
                        <option value="{{ $availableCategory }}" @selected($category === $availableCategory)>
                            {{ $availableCategory }}
                        </option>
                    @endforeach
                </select>
                <button type="submit">Search</button>
                @if ($search !== '' || $category !== '')
                    <a class="clear-search" href="{{ route('customer.browse') }}">Clear</a>
                @endif
            </form>
            <div class="browse-heading">
                <h3>Available Gadgets</h3>
            </div>
            <div class="featured-gadgets">
                @forelse ($gadgets as $gadget)
                    <article class="product-box">
                        <div class="image">
                            <img
                                src="{{ $gadget->photo_path ? \Illuminate\Support\Facades\Storage::disk('public')->url($gadget->photo_path) : asset('images/cp.png') }}"
                                alt="{{ $gadget->name }}">
                        </div>
                        <div class="description">
                            <div class="category">
                                <div class="c-product">
                                    <p class="categ">{{ $gadget->category }}</p>
                                </div>
                                <div class="name-seller">
                                    <p class="owner">Listed by {{ $gadget->lender?->store_name ?: ($gadget->lender?->name ?? 'Lender') }}</p>
                                </div>
                            </div>
                            <div class="product-name">
                                <p class="product">{{ $gadget->name }}</p>
                                @if ($gadget->brand || $gadget->model)
                                    <p class="product-meta">{{ trim(($gadget->brand ?? '') . ' ' . ($gadget->model ?? '')) }}</p>
                                @endif
                            </div>
                            <div class="price">
                                <div class="rate-per-day">
                                    <p class="txt">Rate per day</p>
                                    <p class="rate-price">
                                        {{ $gadget->rental_payment !== null ? '₱' . number_format((float) $gadget->rental_payment, 2) : 'Price not set' }}
                                    </p>
                                </div>
                                <div class="details">
                                    <a href="{{ route('details', $gadget) }}">View Details</a>
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <p class="empty-state">
                        @if ($search !== '' || $category !== '')
                            No available gadgets match your search.
                        @else
                            No gadgets are available for rent right now.
                        @endif
                    </p>
                @endforelse
            </div>
            @if ($gadgets->hasPages())
                <div class="browse-pagination">
                    {{ $gadgets->links() }}
                </div>
            @endif
        </section>
    </main>
    <footer id="About">
        <div class="whole">
            <div class="f-g">
                <div class="f-logo">
                    <img src="{{ asset('images/logo-icon-wrap.png') }}" alt="">
                    <h4 class="puti">Rent</h4>
                    <h4 class="it">IT</h4>
                </div>
                <div>
                    <p>The leading peer-to-peer gadget rental marketplace in Bulacan, Philippines. Elevating access to
                        technology
                        with zero commitment.</p>
                </div>
            </div>
            <div class="creds">
                <div class="b">
                    <h5>Browse</h5>
                    <p>Smartphones</p>
                    <p>Laptops</p>
                    <p>Tablets</p>
                    <p>Cameras</p>
                </div>
                <div class="b">
                    <h5>Community</h5>
                    <p>How It Works</p>
                    <p>Apply as Seller</p>
                    <p>Safety Guidelines</p>
                </div>
            </div>
        </div>
        <div class="statement">
            <div class="statements">
                <p>Â© 2026 RentIT Philippines. All rights reserved.</p>
            </div>
            <div class="fstate">
                <p>Privacy Policy</p>
                <p>Terms of Service</p>
            </div>
        </div>
    </footer>
</body>
<script src="{{ asset('js/Customer-Page/customer-page.js') }}"></script>

</html>