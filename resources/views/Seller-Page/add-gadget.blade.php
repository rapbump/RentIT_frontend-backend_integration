<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Outfit:wght@100..900&display=swap"
        rel="stylesheet">
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="{{ asset('css/Seller-Page_CSS/add_gadget.css') }}">
    <title>Add New Gadget</title>
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
    <main class="main-content">
        <section class="add-gadget-page">
            <div class="page-topbar">
                <div class="title-block">
                    <p class="eyebrow">Add New Gadget</p>
                    <h1>New listing</h1>
                </div>
                <a href="{{ route('lender.gadgets') }}" class="back-btn">
                    <ion-icon name="arrow-back-outline"></ion-icon>
                    Back to Gadgets
                </a>
            </div>

            <div class="content-grid">
                <form class="gadget-form" method="POST" action="{{ route('lender.add-gadget.store') }}" enctype="multipart/form-data">
                    @csrf
                    @if ($errors->any())
                        <div class="form-card form-errors" role="alert">
                            <strong>Please correct the highlighted fields.</strong>
                        </div>
                    @endif

                    <div class="form-card">
                        <h2>Gadget Information</h2>

                        <div class="two-col">
                            <label>
                                <span>Gadget name</span>
                                <input type="text" name="name" value="{{ old('name') }}" required maxlength="255" />
                                @error('name')<small class="field-error">{{ $message }}</small>@enderror
                            </label>
                            <label>
                                <span>Brand</span>
                                <input type="text" name="brand" value="{{ old('brand') }}" maxlength="255" />
                                @error('brand')<small class="field-error">{{ $message }}</small>@enderror
                            </label>
                        </div>

                        <div class="two-col">
                            <label>
                                <span>Model</span>
                                <input type="text" name="model" value="{{ old('model') }}" maxlength="255" />
                                @error('model')<small class="field-error">{{ $message }}</small>@enderror
                            </label>
                            <label>
                                <span>Category</span>
                                <select name="category" required>
                                    <option value="">Select a category</option>
                                    @foreach (['Smartphone', 'Tablet', 'Laptop', 'Camera'] as $category)
                                        <option value="{{ $category }}" @selected(old('category') === $category)>{{ $category }}</option>
                                    @endforeach
                                </select>
                                @error('category')<small class="field-error">{{ $message }}</small>@enderror
                            </label>
                        </div>

                        <label>
                            <span>Condition</span>
                            <select name="condition">
                                <option value="">Select condition</option>
                                @foreach (['Excellent', 'Very Good', 'Good', 'Fair'] as $condition)
                                    <option value="{{ $condition }}" @selected(old('condition') === $condition)>{{ $condition }}</option>
                                @endforeach
                            </select>
                            @error('condition')<small class="field-error">{{ $message }}</small>@enderror
                        </label>

                        <label>
                            <span>Description</span>
                            <textarea name="description" rows="4" maxlength="5000">{{ old('description') }}</textarea>
                            @error('description')<small class="field-error">{{ $message }}</small>@enderror
                        </label>
                    </div>

                    <div class="form-card">
                        <h2>Images</h2>
                        <div class="upload-grid">
                            <label class="upload-box">
                                <ion-icon name="cloud-upload-outline"></ion-icon>
                                <span>Upload main image (optional, up to 5 MB)</span>
                                <input type="file" name="photo" accept="image/jpeg,image/png,image/webp" />
                                @error('photo')<small class="field-error">{{ $message }}</small>@enderror
                            </label>
                            <label class="upload-box small">
                                <ion-icon name="camera-outline"></ion-icon>
                                <span>Add photo (up to 5 MB)</span>
                                <input type="file" name="additional_photos[]" accept="image/jpeg,image/png,image/webp" />
                            </label>
                            <label class="upload-box small">
                                <ion-icon name="camera-outline"></ion-icon>
                                <span>Add photo (up to 5 MB)</span>
                                <input type="file" name="additional_photos[]" accept="image/jpeg,image/png,image/webp" />
                            </label>
                            <label class="upload-box small">
                                <ion-icon name="camera-outline"></ion-icon>
                                <span>Add photo (up to 5 MB)</span>
                                <input type="file" name="additional_photos[]" accept="image/jpeg,image/png,image/webp" />
                            </label>
                        </div>
                        @error('additional_photos')<small class="field-error">{{ $message }}</small>@enderror
                        @error('additional_photos.*')<small class="field-error">{{ $message }}</small>@enderror
                    </div>

                    <div class="form-card">
                        <h2>Pricing & Rental Terms</h2>

                        <div class="two-col">
                            <label>
                                <span>Daily rate</span>
                                <input type="number" name="daily_rate" value="{{ old('daily_rate') }}" min="0.01" step="0.01" />
                                @error('daily_rate')<small class="field-error">{{ $message }}</small>@enderror
                            </label>
                            <label>
                                <span>Weekly rate</span>
                                <input type="number" name="weekly_rate" value="{{ old('weekly_rate') }}" min="0" step="0.01" />
                                @error('weekly_rate')<small class="field-error">{{ $message }}</small>@enderror
                            </label>
                        </div>

                        <div class="two-col">
                            <label>
                                <span>Monthly rate</span>
                                <input type="number" name="monthly_rate" value="{{ old('monthly_rate') }}" min="0" step="0.01" />
                                @error('monthly_rate')<small class="field-error">{{ $message }}</small>@enderror
                            </label>
                            <label>
                                <span>Security deposit</span>
                                <input type="number" name="security_deposit" value="{{ old('security_deposit') }}" min="0" step="0.01" />
                                @error('security_deposit')<small class="field-error">{{ $message }}</small>@enderror
                            </label>
                        </div>

                        <div class="two-col">
                            <label>
                                <span>Minimum rental</span>
                                <select name="rental_duration">
                                    <option value="">Select minimum rental period</option>
                                    <option value="1" @selected(old('rental_duration') == 1)>1 day</option>
                                    <option value="2" @selected(old('rental_duration') == 2)>2 days</option>
                                    <option value="3" @selected(old('rental_duration') == 3)>3 days</option>
                                    <option value="7" @selected(old('rental_duration') == 7)>1 week</option>
                                </select>
                                @error('rental_duration')<small class="field-error">{{ $message }}</small>@enderror
                            </label>
                            <label>
                                <span>Cancellation policy</span>
                                <select name="cancellation_policy">
                                    <option value="">Select a policy</option>
                                    @foreach (['Flexible', 'Moderate', 'Strict'] as $policy)
                                        <option value="{{ $policy }}" @selected(old('cancellation_policy') === $policy)>{{ $policy }}</option>
                                    @endforeach
                                </select>
                                @error('cancellation_policy')<small class="field-error">{{ $message }}</small>@enderror
                            </label>
                        </div>
                    </div>

                    <div class="form-card">
                        <h2>Availability & Pickup</h2>

                        <div class="two-col">
                            <label>
                                <span>Pickup mode</span>
                                <select name="pickup_mode">
                                    <option value="">Select pickup mode</option>
                                    @foreach (['Meetup', 'Doorstep delivery', 'Pickup only'] as $pickupMode)
                                        <option value="{{ $pickupMode }}" @selected(old('pickup_mode') === $pickupMode)>{{ $pickupMode }}</option>
                                    @endforeach
                                </select>
                                @error('pickup_mode')<small class="field-error">{{ $message }}</small>@enderror
                            </label>
                            <label>
                                <span>Delivery fee</span>
                                <input type="number" name="delivery_fee" value="{{ old('delivery_fee') }}" min="0" step="0.01" />
                                @error('delivery_fee')<small class="field-error">{{ $message }}</small>@enderror
                            </label>
                        </div>

                        <div class="two-col">
                            <label>
                                <span>Available from</span>
                                <input type="date" name="availability_date" value="{{ old('availability_date') }}" />
                                @error('availability_date')<small class="field-error">{{ $message }}</small>@enderror
                            </label>
                            <label>
                                <span>Available to</span>
                                <input type="date" name="availability_end_date" value="{{ old('availability_end_date') }}" />
                                @error('availability_end_date')<small class="field-error">{{ $message }}</small>@enderror
                            </label>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" name="action" value="draft" class="secondary-btn">Save Draft</button>
                        <button type="submit" name="action" value="publish" class="primary-btn">Publish Gadget</button>
                    </div>
                </form>

                <aside class="preview-panel">
                    <div class="preview-card">
                        <div class="preview-header">
                            <div class="status-badge">Listing Preview</div>
                            <button type="button" class="mini-btn">Edit</button>
                        </div>

                        <div class="device-visual">
                            <div class="phone-shell">
                                <div class="phone-screen"></div>
                            </div>
                        </div>

                        <div class="preview-body">
                            <div class="preview-title-row">
                                <h3>{{ old('name', 'Your gadget name') }}</h3>
                                <span class="price">{{ old('daily_rate') ? '₱'.number_format((float) old('daily_rate'), 2).'/day' : 'Set daily rate' }}</span>
                            </div>
                            <p class="meta">{{ old('brand', 'Brand') }} • {{ old('model', 'Model') }} • {{ old('condition', 'Condition') }}</p>
                            <p class="preview-description">{{ old('description', 'Your gadget description will appear here.') }}</p>

                            <div class="detail-list">
                                <div>
                                    <span>Pickup</span>
                                    <strong>{{ old('pickup_mode', 'Not selected') }}</strong>
                                </div>
                                <div>
                                    <span>Deposit</span>
                                    <strong>{{ old('security_deposit') ? '₱'.number_format((float) old('security_deposit'), 2) : 'Not set' }}</strong>
                                </div>
                                <div>
                                    <span>Availability</span>
                                    <strong>{{ old('availability_date', 'Not set') }}</strong>
                                </div>
                            </div>

                            <div class="tag-row">
                                <span>{{ old('category', 'Gadget category') }}</span>
                                <span>{{ old('cancellation_policy', 'Cancellation policy') }}</span>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </section>
    </main>
</body>
</html>