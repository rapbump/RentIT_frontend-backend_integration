<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/Customer_Page_CSS/my_rentals.css') }}">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <title>My Rentals | RentIT</title>
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
            <a href="{{ route('myrentals') }}" class="active">My Rentals</a>
        </div>
        <div class="profile">
            <div class="pic"><img src="{{ $avatarUrl }}" alt=""></div>
            <div class="name-stats">
                <p class="Uname">{{ $customer->name }}</p>
                <p class="status">{{ $customer->email_verified_at ? 'Verified Customer' : 'Customer Account' }}</p>
            </div>
        </div>
    </header>

    <main>
        <section class="profile-sidebar">
            <div class="upper">
                <div class="profile-pic"><img src="{{ $avatarUrl }}" alt="{{ $customer->name }}"></div>
                <div class="own-name-hist">
                    <h2 class="Oname">{{ $customer->name }}</h2>
                    <p class="hist">Renter since {{ $customer->created_at->format('F Y') }}</p>
                </div>
                <div class="your-stats">
                    <p class="your-status">{{ $customer->email_verified_at ? 'VERIFIED ACCOUNT' : 'RENTER ACCOUNT' }}</p>
                </div>
            </div>
            <div class="prof-details">
                <div class="detail-field">
                    <p class="label">EMAIL ADDRESS</p>
                    <p class="value">{{ $customer->email }}</p>
                </div>
                <div class="detail-field">
                    <p class="label">CONTACT NUMBER</p>
                    <p class="value">{{ $customer->phone ?: 'Not provided' }}</p>
                </div>
                <div class="detail-field">
                    <p class="label">HOME LOCATION</p>
                    <p class="value">{{ $customer->location ?: 'Not provided' }}</p>
                </div>
            </div>
            <div class="prof-stats">
                <div class="prof-stats-container">
                    <p class="numbers-rented">{{ $rentalCount }}</p>
                    <p class="kind">Rental Records</p>
                </div>
            </div>
        </section>

        <section class="rental-hist-cont">
            <div class="history-header">
                <h2 class="head">My Rentals</h2>
                @if (session('status'))
                    <p role="status">{{ session('status') }}</p>
                @endif
                <div class="tabs-bar">
                    @foreach (['all' => 'All', 'pending' => 'Pending', 'active' => 'Active', 'completed' => 'Completed', 'disputed' => 'Disputed', 'rejected' => 'Rejected'] as $value => $label)
                        <a href="{{ route('myrentals', ['status' => $value]) }}" class="{{ $status === $value ? 'rental-filter-active' : '' }}">{{ $label }}</a>
                    @endforeach
                </div>
                <div class="rental-list">
                    @forelse ($rentals as $rental)
                        <article class="rental-card">
                            <div class="product-pic">
                                <img
                                    src="{{ $rental->gadget->photo_path ? \Illuminate\Support\Facades\Storage::disk('public')->url($rental->gadget->photo_path) : asset('images/cp.png') }}"
                                    alt="{{ $rental->gadget->name }}">
                            </div>
                            <div class="rental-info">
                                <div class="rental-status">
                                    <div class="rental-status-cont"><p class="rental-stats">{{ $rental->status }}</p></div>
                                    <p class="price">Rental total: ₱{{ number_format((float) $rental->total_amount, 2) }}</p>
                                </div>
                                <div class="text">
                                    <p class="product-name">{{ $rental->gadget->name }}</p>
                                    <div class="duration-lender">
                                        <p class="duration">Duration: {{ $rental->start_date->format('M j, Y') }} - {{ $rental->end_date->format('M j, Y') }}</p>
                                        <p class="lender">Lender: {{ $rental->lender->store_name ?: $rental->lender->name }}</p>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @empty
                        <p class="empty-state">No {{ $status === 'all' ? '' : strtolower($status).' ' }}rental records found.</p>
                    @endforelse
                </div>
                @if ($rentals->hasPages())
                    {{ $rentals->links() }}
                @endif
            </div>
        </section>
    </main>
</body>

</html>