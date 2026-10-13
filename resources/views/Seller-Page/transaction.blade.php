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
    <link rel="stylesheet" href="../../css/Seller-Page_CSS/transaction.css" />
    <title>Document</title>
</head>

<body>
    <header>
        <nav class="nav-bar">
            <div class="logo">
                <img src="../../images/logo-icon-wrap.png" alt="" />
                <div class="name">
                    <h2 class="black">Rent</h2>
                    <h2 class="orange">IT</h2>
                </div>
            </div>
            <div class="nav-cont">
                <ul>
                    <li class="nav">
                        <a href="./Dashboard.html" class="links">
                            <ion-icon name="bar-chart-outline"></ion-icon>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav">
                        <a href="./myStore.html" class="links">
                            <ion-icon name="bag-handle-outline"></ion-icon>
                            My Store
                        </a>
                    </li>
                    <li class="nav">
                        <a href="./gadgets.html" class="links">
                            <ion-icon name="phone-portrait-outline"></ion-icon>
                            Gadgets
                        </a>
                    </li>
                    <li class="nav">
                        <a href="renters.php" class="links">
                            <ion-icon name="people-outline"></ion-icon>
                            Renters
                        </a>
                    </li>
                    <li class="active">
                        <a href="./transaction.html" class="links">
                            <ion-icon name="document-outline"></ion-icon>
                            Transactions
                        </a>
                    </li>
                    <li class="nav">
                        <a href="settings.php" class="links">
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
                <div class="month-box">
                    <ion-icon name="calendar-outline"></ion-icon>
                    <input type="month" id="monthPicker" name="month" />
                    <span id="rangeDisplay"></span>
                </div>
            </div>
            <div class="wrapper">
                <div class="container-group">
                    <div class="active-rentals">
                        <div class="active-rentals-head">
                            <h2>Current Rentals</h2>
                        </div>
                        <div class="table-container">
                            <table class="transaction-table">
                                <tr>
                                    <th>Renter</th>
                                    <th>Start</th>
                                    <th>End</th>
                                    <th>Gadget</th>
                                    <th>Rate</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                                <tbody>
                                    <tr>
                                        <td class="renter">Raphael F. Valmocina</td>
                                        <td>Oct 20</td>
                                        <td>Oct 23</td>
                                        <td>IPhone 15</td>
                                        <td>â‚±350</td>
                                        <td class="total">â‚±1,050</td>
                                        <td class="status">Active</td>
                                    </tr>
                                </tbody>
                            </table>
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
                                    <p class="estimate">â‚±4,600</p>
                                </div>
                                <div class="platform-fee">
                                    <p class="fee">Platform Fee</p>
                                    <p class="f-estimate">-â‚±230</p>
                                </div>
                            </div>
                            <div class="total-est">
                                <p class="payout">Your Est. Payout</p>
                                <p class="number-est">â‚±4,370</p>
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
                                <p class="deals-price">38 rentals</p>
                            </div>
                            <div class="disputed">
                                <p class="transactions">Disputed Transaction</p>
                                <p class="numbers-disputed">0 (Perfect Score)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <script src="../../js/Seller-Page/seller.js"></script>
</body>

</html>



