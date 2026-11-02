<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/Customer_Page_CSS/customer.css') }}">
    <title>{{ $lender->store_name ?: $lender->name }} | RentIT</title>
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
</head>

<body>
    <header>
        <div class="logo">
            <img src="{{ asset('images/logo-icon-wrap.png') }}" alt="">
            <div class="name">
                <h2 class="black">Rent</h2>
                <h2 class="orange">IT</h2>
            </div>
        </div>
        <div class="navs">
            <a class="{{ $viewerIsLender ? 'active' : '' }}" href="{{ $viewerIsLender ? route('lender.store') : route('customer.browse') }}">
                {{ $viewerIsLender ? 'My Store' : 'Browse Gadgets' }}
            </a>
        </div>
    </header>

    <main>
        <section class="Browse-gadgets">
            <div class="browse-heading">
                <h1>{{ $lender->store_name ?: $lender->name }}</h1>
                <p>{{ $lender->store_about ?: 'Browse available gadgets from this lender.' }}</p>
                @if ($lender->location)
                    <p>{{ $lender->location }}</p>
                @endif
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
                            </div>
                            <div class="product-name">
                                <p class="product">{{ $gadget->name }}</p>
                                @if ($gadget->brand || $gadget->model)
                                    <p class="product-meta">{{ trim(($gadget->brand ?? '').' '.($gadget->model ?? '')) }}</p>
                                @endif
                            </div>
                            <div class="price">
                                <div class="rate-per-day">
                                    <p class="txt">Rate per day</p>
                                    <p class="rate-price">{{ $gadget->rental_payment !== null ? '₱'.number_format((float) $gadget->rental_payment, 2) : 'Price not set' }}</p>
                                </div>
                                <div class="details">
                                    <a href="{{ route('details', $gadget) }}">View Details</a>
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <p class="empty-state">No available gadgets yet.</p>
                @endforelse
            </div>

            @if ($gadgets->hasPages())
                <div class="browse-pagination">{{ $gadgets->links() }}</div>
            @endif
        </section>
    </main>
</body>

</html>
