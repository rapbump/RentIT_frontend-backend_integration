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
    <link rel="stylesheet" href="{{ asset('css/Seller-Page_CSS/renters.css') }}">
    <title>Renters</title>
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
                    <li class="nav">
                        <a href="{{ route('lender.gadgets')}}" class="links">
                            <ion-icon name="phone-portrait-outline"></ion-icon>
                            Gadgets
                        </a>
                    </li>
                    <li class="active">
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
    <main>
        <section class="renters-page">
            @php
                $renterName = $selectedRenter?->name ?? 'No renter selected';
                $renterLocation = $selectedRenter?->location ?: 'Location not provided';
                $renterRentalCount = $selectedRenter?->rental_count ?? 0;
                $renterDisputes = $selectedRenter?->disputes_count ?? 0;
                $renterHistory = $selectedRenter?->rental_history ?? collect();
            @endphp

            <div class="page-header">
                <div>
                    <h1>Renters</h1>
                    <p>Review your renters, active bookings, and rental history.</p>
                </div>
                <div class="location-pill">
                    <ion-icon name="location-outline"></ion-icon>
                    All locations
                </div>
            </div>

            <div class="summary-grid">
                <div class="summary-card">
                    <span class="summary-label">Total Renters</span>
                    <div class="summary-value-row">
                        <strong>{{ $totalRentersCount }}</strong>
                        <span>Across your store</span>
                    </div>
                </div>
                <div class="summary-card">
                    <span class="summary-label">Currently Renting</span>
                    <div class="summary-value-row">
                        <strong>{{ $activeRentersCount }}</strong>
                        <span>Live rental activity</span>
                    </div>
                </div>
                <div class="summary-card">
                    <span class="summary-label">Returning Renters</span>
                    <div class="summary-value-row">
                        <strong>{{ $returningRentersCount }}</strong>
                        <span>With completed rentals</span>
                    </div>
                </div>
            </div>

            <form class="toolbar" action="{{ route('lender.renters') }}" method="GET">
                <label class="search-box" aria-label="Search renter">
                    <ion-icon name="search-outline"></ion-icon>
                    <input type="search" name="search" value="{{ $search }}" placeholder="Search renter name or location..." />
                </label>
                <label class="status-select">
                    <span>Status:</span>
                    <select name="status">
                        @foreach (['all' => 'All', 'active' => 'Active', 'pending' => 'Pending', 'completed' => 'Completed', 'disputed' => 'Disputed', 'rejected' => 'Rejected'] as $value => $label)
                            <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <button class="filter-button" type="submit">Apply</button>
            </form>

            <div class="content-grid">
                <div class="table-panel">
                    <div class="panel-header">
                        <h2>All Renters</h2>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th>Renter</th>
                                <th>Rentals</th>
                                <th>Rental Value</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($renters as $renter)
                                @php
                                    $initials = collect(explode(' ', $renter->name))->take(2)->map(fn ($part) => strtoupper(substr($part, 0, 1)))->implode('');
                                    $statusClass = match ($renter->status) {
                                        'active' => 'success',
                                        'pending' => 'warning',
                                        default => 'neutral',
                                    };
                                @endphp

                                <tr @class(['selected-renter' => $selectedRenter?->id === $renter->id])>
                                    <td class="renter-cell">
                                        <div class="avatar avatar-orange">{{ $initials }}</div>
                                        <div class="name-block">
                                            <a href="{{ route('lender.renters', array_filter(['search' => $search ?: null, 'status' => $status !== 'all' ? $status : null, 'renter' => $renter->id])) }}">
                                                <strong>{{ $renter->name }}</strong>
                                            </a>
                                            <span>{{ $renter->location ?: 'Location not provided' }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $renter->rental_count }} {{ $renter->rental_count === 1 ? 'rental' : 'rentals' }}</td>
                                    <td>₱{{ number_format((float) $renter->rental_value, 2) }}</td>
                                    <td><span class="tag {{ $statusClass }}">{{ ucfirst($renter->status) }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="empty-row">No renters found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="table-footer">
                        <span>Showing {{ $renters->count() }} of {{ $renters->total() }} renter{{ $renters->total() === 1 ? '' : 's' }}</span>
                        <div class="pagination-controls">{{ $renters->onEachSide(1)->links() }}</div>
                    </div>
                </div>

                <aside class="details-panel">
                    <div class="details-header">
                        <div class="avatar avatar-orange large">{{ $selectedRenter ? collect(explode(' ', $renterName))->take(2)->map(fn ($part) => strtoupper(substr($part, 0, 1)))->implode('') : '?' }}</div>
                        <div>
                            <h3>{{ $renterName }}</h3>
                            @if ($selectedRenter?->email_verified_at)
                                <span class="verified-badge">Verified Renter</span>
                            @endif
                        </div>
                    </div>

                    <div class="detail-list">
                        <div class="detail-row">
                            <span>Location</span>
                            <strong>{{ $renterLocation }}</strong>
                        </div>
                        <div class="detail-row">
                            <span>Member since</span>
                            <strong>{{ $selectedRenter?->created_at?->format('M Y') ?? 'N/A' }}</strong>
                        </div>
                        <div class="detail-row">
                            <span>Rental record</span>
                            <strong>{{ $renterRentalCount }} rentals • {{ $renterDisputes }} dispute{{ $renterDisputes === 1 ? '' : 's' }}</strong>
                        </div>
                    </div>

                    @if ($selectedRenter?->email)
                        <a class="message-btn" href="mailto:{{ $selectedRenter->email }}?subject={{ rawurlencode('Message from '.($lender->store_name ?: $lender->name)) }}">
                            <ion-icon name="chatbubble-ellipses-outline"></ion-icon>
                            Message Renter
                        </a>
                    @else
                        <button type="button" class="message-btn" disabled>No renter email available</button>
                    @endif

                    <div class="history-panel">
                        <h4>Rental History</h4>

                        @forelse ($renterHistory as $historyItem)
                            @php
                                $historyName = $historyItem->gadget?->name ?? 'Deleted gadget';
                                $historyDate = $historyItem->start_date->format('M j, Y');
                                $historyStatus = strtolower($historyItem->status);
                                $historyAmount = $historyItem->total_amount;
                            @endphp

                            <div class="history-item">
                                <div class="history-text">
                                    <strong>{{ $historyName }}</strong>
                                    <span>{{ $historyDate }}</span>
                                </div>
                                <div class="history-meta">
                                    <span class="history-status {{ $historyStatus === 'active' ? 'active' : 'done' }}">{{ ucfirst($historyStatus) }}</span>
                                    <strong>₱{{ number_format($historyAmount, 0) }}</strong>
                                </div>
                            </div>
                        @empty
                            <div class="history-item empty-history">
                                <div class="history-text">
                                    <strong>No rental history available.</strong>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </aside>
            </div>
        </section>
    </main>
</body>
</html>