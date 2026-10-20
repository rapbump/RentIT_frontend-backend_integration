<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/Customer_Page_CSS/cart.css') }}">
    <title>My Cart | RentIT</title>
</head>

<body>
    <header>
        <a class="logo" href="{{ route('customer.browse') }}">Rent<span>IT</span></a>
        <nav>
            <a href="{{ route('customer.browse') }}">Browse Gadgets</a>
            <a href="{{ route('myrentals') }}">My Rentals</a>
        </nav>
        <p class="customer-name">{{ $customer->name }}</p>
    </header>

    <main>
        <div class="cart-heading">
            <div>
                <h1>My Cart</h1>
                <p>{{ $cartItems->count() }} {{ \Illuminate\Support\Str::plural('gadget', $cartItems->count()) }} saved for later</p>
            </div>
            <a class="browse-link" href="{{ route('customer.browse') }}">Continue browsing</a>
        </div>

        @if (session('status'))
            <p class="status-message" role="status">{{ session('status') }}</p>
        @endif
        @if ($errors->any())
            <p class="status-message error-message" role="alert">{{ $errors->first() }}</p>
        @endif

        @forelse ($cartItems as $cartItem)
            <article class="cart-item">
                <img
                    class="gadget-image"
                    src="{{ $cartItem->gadget->photo_path ? \Illuminate\Support\Facades\Storage::disk('public')->url($cartItem->gadget->photo_path) : asset('images/cp.png') }}"
                    alt="{{ $cartItem->gadget->name }}">
                <div class="gadget-details">
                    <h2>{{ $cartItem->gadget->name }}</h2>
                    <p>Listed by {{ $cartItem->gadget->lender->store_name ?: $cartItem->gadget->lender->name }}</p>
                    <p class="gadget-price">
                        {{ $cartItem->gadget->rental_payment !== null ? '₱'.number_format((float) $cartItem->gadget->rental_payment, 2).' / day' : 'Price not set' }}
                    </p>
                    @if ($cartItem->gadget->status !== 'Available')
                        <p class="unavailable-message">This gadget is no longer available. Remove it from your cart to continue.</p>
                    @endif
                </div>
                <form action="{{ route('customer.cart.destroy', $cartItem) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="remove-button" type="submit">Remove</button>
                </form>
            </article>
        @empty
            <div class="empty-cart">
                <h2>Your cart is empty</h2>
                <p>Browse available gadgets and add one to your cart.</p>
                <a class="browse-link" href="{{ route('customer.browse') }}">Browse Gadgets</a>
            </div>
        @endforelse

        @if ($cartItems->isNotEmpty())
            <section class="request-section">
                <h2>Request these rentals</h2>
                <p>Choose one rental period for all gadgets in your cart. The end date is the return date. Each request is sent to its lender for approval.</p>
                <form action="{{ route('customer.cart.request') }}" method="POST">
                    @csrf
                    <div class="date-fields">
                        <label for="start_date">
                            Rental start date
                            <input id="start_date" name="start_date" type="date" min="{{ now()->toDateString() }}" value="{{ old('start_date') }}" required>
                            @error('start_date')<span class="field-error">{{ $message }}</span>@enderror
                        </label>
                        <label for="end_date">
                            Return date
                            <input id="end_date" name="end_date" type="date" min="{{ old('start_date', now()->toDateString()) }}" value="{{ old('end_date') }}" required>
                            @error('end_date')<span class="field-error">{{ $message }}</span>@enderror
                        </label>
                    </div>
                    @error('cart')<p class="field-error">{{ $message }}</p>@enderror
                    <button class="request-button" type="submit">Submit Rental Requests</button>
                </form>
                <p class="cart-notice">Requests remain pending until approved. Submitting a request does not reserve gadgets or charge payment.</p>
            </section>
        @endif
    </main>
</body>

</html>
