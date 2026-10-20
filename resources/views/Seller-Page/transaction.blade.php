<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Outfit:wght@100..900&display=swap"
        rel="stylesheet" />
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="{{ asset('css/Seller-Page_CSS/transaction.css') }}" />
    <title>Rental Transactions | RentIT</title>
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
                    <li class="nav">
                        <a href="{{ route('lender.renters')}}" class="links">
                            <ion-icon name="people-outline"></ion-icon>
                            Renters
                        </a>
                    </li>
                    <li class="active">
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
            <div class="transaction-head">
                <div class="welcome-text">
                    <h2>Rental Transactions</h2>
                    <p>Track active agreements, earnings payouts, and history.</p>
                </div>
                <form class="month-box" action="{{ route('lender.transaction') }}" method="GET">
                    <ion-icon name="calendar-outline"></ion-icon>
                    <input type="month" id="monthPicker" name="month" value="{{ $month }}" aria-label="Filter transactions by month" />
                    <span id="rangeDisplay"></span>
                    <button type="submit">Filter</button>
                </form>
            </div>
            @if (session('status'))
                <p role="status">{{ session('status') }}</p>
            @endif
            @if ($errors->any())
                <p role="alert">{{ $errors->first() }}</p>
            @endif
            <div class="wrapper">
                <div class="container-group">
                    <div class="active-rentals">
                        <div class="active-rentals-head">
                            <h2>Pending Rental Requests</h2>
                        </div>
                        <div class="table-container">
                            <table class="transaction-table">
                                <thead>
                                    <tr>
                                        <th>Renter</th>
                                        <th>Start</th>
                                        <th>End</th>
                                        <th>Gadget</th>
                                        <th>Rate</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Review</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pendingRequests as $rentalRequest)
                                        <tr>
                                            <td class="renter">{{ $rentalRequest->renter?->name ?? 'Deleted renter' }}</td>
                                            <td>{{ $rentalRequest->start_date->format('M j, Y') }}</td>
                                            <td>{{ $rentalRequest->end_date->format('M j, Y') }}</td>
                                            <td>{{ $rentalRequest->gadget?->name ?? 'Deleted gadget' }}</td>
                                            <td>&#8369;{{ number_format((float) $rentalRequest->daily_rate, 2) }}</td>
                                            <td class="total">&#8369;{{ number_format((float) $rentalRequest->total_amount, 2) }}</td>
                                            <td class="status">{{ $rentalRequest->status }}</td>
                                            <td>
                                                <form action="{{ route('lender.transaction.approve', $rentalRequest) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button class="request-action approve-action" type="submit">Approve</button>
                                                </form>
                                                <form action="{{ route('lender.transaction.reject', $rentalRequest) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button class="request-action reject-action" type="submit">Reject</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="empty-transactions" colspan="8">No pending rental requests.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            {{ $pendingRequests->links() }}
                        </div>
                    </div>
                    <div class="active-rentals">
                        <div class="active-rentals-head">
                            <h2>Current Rentals</h2>
                        </div>
                        <div class="table-container">
                            <table class="transaction-table">
                                <thead>
                                    <tr>
                                        <th>Renter</th>
                                        <th>Start</th>
                                        <th>End</th>
                                        <th>Gadget</th>
                                        <th>Rate</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($activeRentals as $rentalTransaction)
                                        <tr>
                                            <td class="renter">{{ $rentalTransaction->renter?->name ?? 'Deleted renter' }}</td>
                                            <td>{{ $rentalTransaction->start_date->format('M j, Y') }}</td>
                                            <td>{{ $rentalTransaction->end_date->format('M j, Y') }}</td>
                                            <td>{{ $rentalTransaction->gadget?->name ?? 'Deleted gadget' }}</td>
                                            <td>&#8369;{{ number_format((float) $rentalTransaction->daily_rate, 2) }}</td>
                                            <td class="total">&#8369;{{ number_format((float) $rentalTransaction->total_amount, 2) }}</td>
                                            <td class="status">{{ $rentalTransaction->status }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="empty-transactions" colspan="7">No active rentals found for this month.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            {{ $activeRentals->links() }}
                        </div>
                    </div>
                    <div class="payment-transac-hist">
                        <div class="payment-calcu">
                            <div class="payment-calcu-head">
                                <h2>Payment Calculation</h2>
                                <ion-icon name="document-outline"></ion-icon>
                            </div>
                            <div class="computation">
                                <div class="revenue">
                                    <p class="rental-rev">Active Rental Revenue</p>
                                    <p class="estimate">&#8369;{{ number_format((float) $activeRentalRevenue, 2) }}</p>
                                </div>
                                <div class="platform-fee">
                                    <p class="fee">Platform Fee</p>
                                    <p class="f-estimate">-&#8369;{{ number_format((float) $platformFee, 2) }}</p>
                                </div>
                            </div>
                            <div class="total-est">
                                <p class="payout">Your Est. Payout</p>
                                <p class="number-est">&#8369;{{ number_format((float) $estimatedPayout, 2) }}</p>
                            </div>
                            <div class="desc">
                                <p class="descs">
                                    Payouts are released securely via Gcash, Maya, or bank
                                    transfer within 24 hours of successful meetup completion.
                                </p>
                            </div>
                        </div>
                        <div class="transac-hist">
                            <div class="transac-head">
                                <p>Transaction History</p>
                            </div>
                            <div class="completed">
                                <p class="deals">Completed Deals</p>
                                <p class="deals-price">{{ $completedDealsCount }} {{ $completedDealsCount === 1 ? 'rental' : 'rentals' }}</p>
                            </div>
                            <div class="disputed">
                                <p class="transactions">Disputed Transaction</p>
                                <p class="numbers-disputed">{{ $disputedTransactionsCount }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <script src="{{ asset('js/Seller-Page/seller.js') }}" defer></script>
</body>

</html>
