<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/Admin_Page_CSS/sellers-stores.css') }}" />
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Outfit:wght@100..900&display=swap"
        rel="stylesheet" />
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <title>Sellers & Stores</title>
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
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
                    <li class="nav">
                        <a href="{{ route('admin.dashboard')}}" class="links">
                            <ion-icon name="bar-chart-outline"></ion-icon>
                            Dashboard
                        </a>
                    </li>
                    <li class="active">
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
        <section class="main-content">
            <div class="main-header">
                <h2 class="head">Sellers &amp; Stores</h2>
                <p class="intro">Review registered lender profiles and manage their verification status.</p>
            </div>
            <div class="category-btns">
                <a href="{{ route('admin.stores', ['status' => 'all']) }}" class="{{ $status === 'all' ? 'active' : '' }}">All Sellers</a>
                <a href="{{ route('admin.stores', ['status' => 'pending']) }}" class="{{ $status === 'pending' ? 'active' : '' }}">Pending</a>
                <a href="{{ route('admin.stores', ['status' => 'approved']) }}" class="{{ $status === 'approved' ? 'active' : '' }}">Approved</a>
                <a href="{{ route('admin.stores', ['status' => 'rejected']) }}" class="{{ $status === 'rejected' ? 'active' : '' }}">Rejected</a>
            </div>
            @if (session('status'))
                <p role="status">{{ session('status') }}</p>
            @endif
            @if ($errors->any())
                <p role="alert">{{ $errors->first() }}</p>
            @endif
            <div class="all-container">
                <div class="all-header">
                    <h2 class="all-head">{{ $status === 'all' ? 'All Registered Sellers' : ucfirst($status).' Sellers' }}</h2>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Store Name</th>
                            <th>Applicant</th>
                            <th>Email</th>
                            <th>Location</th>
                            <th>Date Applied</th>
                            <th>Status</th>
                            <th>Documents</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sellers as $seller)
                            @php($verificationStatus = $seller->seller_verification_status ?? 'Pending')
                            <tr>
                                <td>{{ $seller->store_name ?: 'Store not provided' }}</td>
                                <td>
                                    {{ $seller->name }}
                                    @if ($seller->verification_official_name)
                                        <small>Official name: {{ $seller->verification_official_name }}</small>
                                    @endif
                                </td>
                                <td>{{ $seller->email }}</td>
                                <td>
                                    {{ $seller->location ?: '—' }}
                                    @if ($seller->verification_street_address)
                                        <small>
                                            Submitted address:
                                            {{ $seller->verification_street_address }},
                                            {{ $seller->verification_barangay }},
                                            {{ $seller->verification_city }},
                                            {{ $seller->verification_province }}
                                        </small>
                                    @endif
                                </td>
                                <td>{{ $seller->created_at?->format('M j, Y') }}</td>
                                <td><span class="status-badge status-{{ strtolower($verificationStatus) }}">{{ $verificationStatus }}</span></td>
                                <td>
                                    @if ($seller->seller_verification_submitted_at)
                                        <div class="seller-actions">
                                            @if ($seller->government_id_path)
                                                <a href="{{ route('admin.stores.documents', [$seller, 'government-id']) }}">Government ID</a>
                                            @endif
                                            @if ($seller->residence_proof_path)
                                                <a href="{{ route('admin.stores.documents', [$seller, 'residence-proof']) }}">Proof of residence</a>
                                            @endif
                                            @if ($seller->barangay_certification_path)
                                                <a href="{{ route('admin.stores.documents', [$seller, 'barangay-certification']) }}">Barangay certification</a>
                                            @endif
                                        </div>
                                    @else
                                        Not submitted
                                    @endif
                                </td>
                                <td>
                                    <div class="seller-actions">
                                        @foreach (['Pending' => 'Mark Pending', 'Approved' => 'Approve', 'Rejected' => 'Reject'] as $nextStatus => $label)
                                            <form method="POST" action="{{ route('admin.stores.verification', $seller) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="verification_status" value="{{ $nextStatus }}">
                                                <button type="submit" @disabled($verificationStatus === $nextStatus)>{{ $label }}</button>
                                            </form>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">No sellers found for this status.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $sellers->links() }}
            </div>
        </section>
    </main>
</body>
</html>