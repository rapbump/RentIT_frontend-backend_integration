<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../../css/Admin_Page_CSS/rental.css" />
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Outfit:wght@100..900&display=swap"
        rel="stylesheet" />
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <title>Document</title>
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
                    <li class="nav">
                        <a href="{{ route('admin.stores')}}" class="links">
                            <ion-icon name="bag-handle-outline"></ion-icon>
                            Sellers & Stores
                        </a>
                    </li>
                    <li class="active">
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
                <h2 class="head">Rental Transactions</h2>
                <p class="intro">Review rental activity across renters, stores, and listed gadgets.</p>
            </div>
            <div class="overview-cont">
                <div class="active">
                    <h2 class="active-head">{{ $status === 'all' ? 'All Rentals' : ucfirst($status).' Rentals' }}</h2>
                </div>
                <form method="GET" action="{{ route('admin.rentals') }}">
                    <label for="rental-status">Filter by status</label>
                    <select id="rental-status" name="status">
                        @foreach (['all' => 'All statuses', 'pending' => 'Pending', 'active' => 'Active', 'completed' => 'Completed', 'disputed' => 'Disputed', 'rejected' => 'Rejected'] as $value => $label)
                            <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button type="submit">Filter</button>
                </form>
                @if ($errors->any())
                    <p role="alert">{{ $errors->first() }}</p>
                @endif
                <table>
                    <thead class="table-head">
                        <tr>
                            <th>Renter</th>
                            <th>Lender Store</th>
                            <th>Gadget</th>
                            <th>Rental Term</th>
                            <th>Total Amount</th>
                            <th class="stats">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rentals as $rental)
                            <tr class="datas">
                                <td class="renter-name">{{ $rental->renter->name }}</td>
                                <td>{{ $rental->lender->store_name ?: $rental->lender->name }}</td>
                                <td>{{ $rental->gadget->name }}</td>
                                <td>{{ $rental->start_date->format('M j, Y') }} - {{ $rental->end_date->format('M j, Y') }}</td>
                                <td class="price">₱{{ number_format((float) $rental->total_amount, 2) }}</td>
                                <td class="status"><div class="data"><p>{{ $rental->status }}</p></div></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">No rental transactions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $rentals->links() }}
            </div>
        </section>
    </main>
</body>

</html>