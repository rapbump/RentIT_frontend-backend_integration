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
    <link rel="stylesheet" href="{{ asset('css/Seller-Page_CSS/myStore.css') }}">
    <title>My Store</title>
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
                    <li class="active">
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
            <div class="myStore-head">
                <h2>Store Profile</h2>
                <div class="date">
                    <p class="date_p">Today: <span id="dateToday"></span></p>
                </div>
            </div>
            @if (session('status'))
                <p class="store-form-success" role="status">{{ session('status') }}</p>
            @endif
            @if ($errors->any())
                <div class="store-form-errors" role="alert">
                    <p>Please correct the highlighted fields and try again.</p>
                </div>
            @endif
        </section>
        <section>
            <div class="store-info">
                <div class="bgpic">
                    <div class="imag">
                        <img id="bg" src="{{ $backgroundUrl }}" alt="Store background">
                    </div>
                </div>
                <div class="prof_info">
                    <div class="prof">
                        <div class="prof_pic">
                            <img id="avatar" src="{{ $avatarUrl }}" alt="Store profile">
                        </div>
                        <div class="business_name">
                            <div class="head">
                                <h2>{{ $lender->store_name ?: $lender->name }}</h2>
                                <div class="status">
                                    <ion-icon name="shield-checkmark"></ion-icon>
                                    <p>Verified Lender</p>
                                </div>
                            </div>
                            <div class="rate_date">
                                <div class="date">
                                    <p>Joined {{ $lender->created_at?->format('F Y') ?? 'RentIT' }}</p>
                                </div>
                            </div>
                            <p class="bio">{{ $lender->store_about ?: 'Store information has not been added yet.' }}</p>
                            <div class="services">
                                <div class="container">
                                    <ion-icon name="shield-checkmark"></ion-icon>
                                    <p class="s_p">Well Maintained Devices</p>
                                </div>
                                <div class="container">
                                    <ion-icon name="flash"></ion-icon>
                                    <p class="s_p">Fast & Easy Booking</p>
                                </div>
                                <div class="container">
                                    <ion-icon name="headset"></ion-icon>
                                    <p class="s_p">Customer Support</p>
                                </div>
                            </div>
                        </div>
                        <details class="store-edit" @if ($errors->any()) open @endif>
                            <summary><ion-icon name="pencil"></ion-icon> Edit Profile &amp; Store Info</summary>
                            <form action="{{ route('lender.store.profile') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="store-edit-grid">
                                    <div class="store-edit-field">
                                        <label for="store-profile-avatar">Profile Photo</label>
                                        <input id="store-profile-avatar" name="avatar" type="file" accept="image/jpeg,image/png,image/webp">
                                        <small class="store-edit-hint">JPEG, PNG, or WebP; maximum 5 MB.</small>
                                        @error('avatar')
                                            <small>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="store-edit-field">
                                        <label for="store-profile-background">Store Background Photo</label>
                                        <input id="store-profile-background" name="background" type="file" accept="image/jpeg,image/png,image/webp">
                                        <small class="store-edit-hint">JPEG, PNG, or WebP; maximum 5 MB.</small>
                                        @error('background')
                                            <small>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="store-edit-field">
                                        <label for="store-profile-name">Your Name</label>
                                        <input id="store-profile-name" name="name" type="text" value="{{ old('name', $lender->name) }}" maxlength="255" required>
                                        @error('name')
                                            <small>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="store-edit-field">
                                        <label for="store-profile-email">Email</label>
                                        <input id="store-profile-email" name="email" type="email" value="{{ old('email', $lender->email) }}" maxlength="255" required>
                                        @error('email')
                                            <small>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="store-edit-field">
                                        <label for="store-profile-phone">Contact Number</label>
                                        <input id="store-profile-phone" name="phone" type="tel" value="{{ old('phone', $lender->phone) }}" maxlength="20">
                                        @error('phone')
                                            <small>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="store-edit-field">
                                        <label for="store-profile-location">Location</label>
                                        <input id="store-profile-location" name="location" type="text" value="{{ old('location', $lender->location) }}" maxlength="255">
                                        @error('location')
                                            <small>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="store-edit-field">
                                        <label for="store-profile-store-name">Store Name</label>
                                        <input id="store-profile-store-name" name="store_name" type="text" value="{{ old('store_name', $lender->store_name) }}" maxlength="255">
                                        @error('store_name')
                                            <small>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="store-edit-field">
                                        <label for="store-profile-operating-days">Operating Days</label>
                                        <input id="store-profile-operating-days" name="operating_days" type="text" value="{{ old('operating_days', $lender->operating_days) }}" maxlength="255" placeholder="e.g. Monday to Saturday">
                                        @error('operating_days')
                                            <small>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="store-edit-field">
                                        <label for="store-profile-business-hours">Business Hours</label>
                                        <input id="store-profile-business-hours" name="business_hours" type="text" value="{{ old('business_hours', $lender->business_hours) }}" maxlength="255" placeholder="e.g. 8:00 AM - 6:00 PM">
                                        @error('business_hours')
                                            <small>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="store-edit-field store-edit-field-wide">
                                        <label for="store-profile-about">About Your Store</label>
                                        <textarea id="store-profile-about" name="store_about" rows="4" maxlength="2000">{{ old('store_about', $lender->store_about) }}</textarea>
                                        @error('store_about')
                                            <small>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="store-edit-field store-edit-field-wide">
                                        <label for="store-profile-meetup-locations">Meet-up Locations</label>
                                        <textarea id="store-profile-meetup-locations" name="meetup_locations" rows="3" maxlength="2000">{{ old('meetup_locations', $lender->meetup_locations) }}</textarea>
                                        @error('meetup_locations')
                                            <small>{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <button class="store-edit-save" type="submit">Save Profile &amp; Store Info</button>
                            </form>
                        </details>
                    </div>
                    <div class="store_info">
                        <h2 class="store">Store Information</h2>
                        <div class="info">
                            <div class="values">
                                <div class="infos">
                                    <div class="icons">
                                        <ion-icon name="storefront-outline"></ion-icon>
                                    </div>
                                    <div class="cont">
                                        <label>Store Name</label>
                                        <p>{{ $lender->store_name ?: $lender->name }}</p>
                                    </div>
                                </div>
                                <div class="infos">
                                    <div class="icons">
                                        <ion-icon name="location-outline"></ion-icon>
                                    </div>
                                    <div class="cont">
                                        <label>Location</label>
                                        <p>{{ $lender->location ?: 'Not provided' }}</p>
                                    </div>

                                </div>
                                <div class="infos">
                                    <div class="icons">
                                        <ion-icon name="mail-outline"></ion-icon>
                                    </div>
                                    <div class="cont">
                                        <label>Email</label>
                                        <p>{{ $lender->email }}</p>
                                    </div>

                                </div>
                                <div class="infos">
                                    <div class="icons">
                                        <ion-icon name="call-outline"></ion-icon>
                                    </div>
                                    <div class="cont">
                                        <label>Contact Number</label>
                                        <p>{{ $lender->phone ?: 'Not provided' }}</p>
                                    </div>

                                </div>
                                <div class="infos">
                                    <div class="icons">
                                        <ion-icon name="time-outline"></ion-icon>
                                    </div>
                                    <div class="cont">
                                        <label>Operating Days &amp; Hours</label>
                                        <p>{{ $lender->operating_days ?: 'Not provided' }}{{ $lender->business_hours ? ' | '.$lender->business_hours : '' }}</p>
                                    </div>
                                </div>
                                <div class="infos">
                                    <div class="icons">
                                        <ion-icon name="navigate-outline"></ion-icon>
                                    </div>
                                    <div class="cont">
                                        <label>Meet-up Locations</label>
                                        <p>{{ $lender->meetup_locations ?: 'Not provided' }}</p>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
        <section class="featured">
            <div class="featured_head">
                <h2>Featured Gadgets for Rent</h2>
                <a href="{{ route('lender.gadgets') }}">View All <span><ion-icon name="arrow-forward-outline"></ion-icon></span></a>
            </div>
            <div class="cards">
                @forelse ($featuredGadgets as $gadget)
                    <article class="card_group">
                        <div class="display_image">
                            <img src="{{ $gadget->image_url }}" alt="{{ $gadget->name }}">
                        </div>
                        <div class="desc">
                            <h2>{{ strtoupper($gadget->category) }}</h2>
                            <h3 title="{{ $gadget->name }}">{{ $gadget->name }}</h3>
                            <div class="gadget-condition">
                                <span>{{ $gadget->condition ?? 'Condition not specified' }}</span>
                            </div>
                            <div class="prices">
                                <div class="price">
                                    <p class="p_price">Rate per Day</p>
                                    <p>{{ $gadget->rental_payment !== null ? '₱'.number_format((float) $gadget->rental_payment, 2) : 'Rate not set' }}</p>
                                </div>
                                <a class="rent btn" href="{{ route('lender.gadgets') }}">View Listing</a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="featured-empty">
                        <h3>No available gadgets yet</h3>
                        <p>Publish a gadget listing and it will appear here.</p>
                        <a href="{{ route('lender.add-gadget') }}">Add a Gadget</a>
                    </div>
                @endforelse
            </div>
        </section>
    </main>
    <script src="{{ asset('js/Seller-Page/seller.js') }}" defer></script>

</body>

</html>
