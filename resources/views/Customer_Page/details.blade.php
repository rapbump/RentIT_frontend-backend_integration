<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/Customer_Page_CSS/details.css') }}">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <title>{{ $gadget->name }} | RentIT</title>
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
</head>

<body>
    <header>
        <div class="logo">
            <img src="{{ asset('images/logo-icon-wrap.png') }}" alt="">
            <div class="name"><h2 class="black">Rent</h2><h2 class="orange">IT</h2></div>
        </div>
        <div class="navs">
            <a href="{{ route('customer.browse') }}">Browse Gadgets</a>
            @if ($customer?->role === 'user')
                <a href="{{ route('customer.cart') }}">Cart ({{ $cartCount }})</a>
            @endif
            <a href="{{ route('myrentals') }}">My Rentals</a>
        </div>
        <div class="profile">
            <div class="name-stats">
                <p class="Uname">{{ $customer?->name ?? 'Guest' }}</p>
                <p class="status">{{ $customer?->email_verified_at ? 'Verified Customer' : ($customer ? 'Customer Account' : 'Guest') }}</p>
            </div>
        </div>
    </header>

    <main>
        <section>
            <div class="left-content">
                <div class="gallery-card">
                    <div class="main-image">
                        <img src="{{ $mainImage }}" alt="{{ $gadget->name }}">
                    </div>
                    @if ($galleryImages->count() > 1)
                        <div class="smaller-images">
                            @foreach ($galleryImages->skip(1) as $image)
                                <img src="{{ $image }}" alt="{{ $gadget->name }} photo">
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="details-card">
                    <div class="details-desc">
                        <div class="details-header"><h2>{{ $gadget->name }}</h2></div>
                        <div class="product-desc">
                            <p class="description">{{ $gadget->description ?: 'No additional description has been provided.' }}</p>
                        </div>
                    </div>
                    <div class="specs-container">
                        <div class="spec-heading"><h3>Listing Details</h3></div>
                        @foreach ([
                            'Category' => $gadget->category,
                            'Brand' => $gadget->brand,
                            'Model' => $gadget->model,
                            'Condition' => $gadget->condition,
                            'Security deposit' => $gadget->security_deposit !== null ? '₱'.number_format((float) $gadget->security_deposit, 2) : null,
                            'Minimum rental duration' => $gadget->rental_duration ? $gadget->rental_duration.' day(s)' : null,
                            'Pickup option' => $gadget->pickup_mode,
                        ] as $label => $value)
                            @if ($value)
                                <div class="specs">
                                    <div class="spec-name"><p class="s-name">{{ $label }}</p></div>
                                    <div class="spec-value"><p class="v-spec">{{ $value }}</p></div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="reviews-card">
                    <div class="rating-header"><h2>Reviews</h2></div>
                    <div class="reviews-scrollable">
                        <p class="rev-stmt">Reviews are not available for this listing yet.</p>
                    </div>
                </div>
            </div>

            <div class="right-content">
                <div class="booking-card">
                    <div class="computation-cont">
                        <div class="price-display">
                            <p class="peso">{{ $gadget->rental_payment !== null ? '₱'.number_format((float) $gadget->rental_payment, 2) : 'Price not set' }}</p>
                            @if ($gadget->rental_payment !== null)
                                <p class="per-day">/ day</p>
                            @endif
                        </div>
                        <div class="condition">
                            <div class="condition-cont">
                                <p class="txt">Status:</p>
                                <p class="like">{{ $gadget->status }}</p>
                            </div>
                        </div>
                    </div>
                    @if ($customer?->role === 'user')
                        @if ($isInCart)
                            <a class="cart-button" href="{{ route('customer.cart') }}">View Cart ({{ $cartCount }})</a>
                        @else
                            <form action="{{ route('customer.cart.store', $gadget) }}" method="POST">
                                @csrf
                                <button class="cart-button" type="submit">Add to Cart</button>
                            </form>
                        @endif
                        <p>Adding this gadget to your cart does not create a rental request or charge a payment.</p>
                    @elseif ($customer)
                        <p>Only customer accounts can add gadgets to a cart.</p>
                    @else
                        <p><a href="{{ route('login.form') }}">Sign in</a> to add this gadget to your cart.</p>
                    @endif
                    @if ($gadget->availability_date)
                        <p>Available from {{ $gadget->availability_date->format('M j, Y') }}@if ($gadget->availability_end_date) to {{ $gadget->availability_end_date->format('M j, Y') }}@endif</p>
                    @endif
                    <a href="{{ route('customer.browse') }}">Back to Browse</a>
                </div>
                <div class="meet-up-card">
                    <div class="meet-header"><h4>Lender &amp; Meet-up Location</h4></div>
                    <div class="meet-prof">
                        <div class="name-level">
                            <p class="seller-name">{{ $gadget->lender->store_name ?: $gadget->lender->name }}</p>
                            <p class="level">{{ $gadget->lender->location ?: 'Location not provided' }}</p>
                        </div>
                    </div>
                    <div class="designated-loc">
                        <div class="loc-header"><h2>MEET-UP OPTIONS</h2></div>
                        <div class="loc">
                            <p class="location">{{ $gadget->lender->meetup_locations ?: 'Meet-up details not provided.' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>

</html>
