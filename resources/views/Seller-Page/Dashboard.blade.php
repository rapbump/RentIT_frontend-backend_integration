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
    <link rel="stylesheet" href="../../css/Seller-Page_CSS/dashboard.css">
    <title>Dashboard</title>
</head>

<body>
    <header>
        <nav class="nav-bar">
            <div class="logo">
                <img src="../../images/logo-icon-wrap.png" alt="">
                <div class="name">
                    <h2 class="black">Rent</h2>
                    <h2 class="orange">IT</h2>
                </div>
            </div>
            <div class="nav-cont">
                <ul>
                    <li class="active">
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
                    <li class="nav">
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
    <main>
        <section class="dashboard">
            <div class="top">
                <div class="txt">
                    <h3>Welcome!</h3>
                    <p>Here's what's happening with your gadget rentals today in Bulacan.</p>
                </div>
                <div class="date">
                    <p>Today: <span id="dateToday"></span></p>
                </div>
            </div>
            <div class="boxes">
                <div class="box">
                    <div class="head">
                        <h4>Active Gadgets</h4>
                        <div class="icon1">
                            <ion-icon name="phone-portrait-outline"></ion-icon>
                        </div>
                    </div>
                    <div class="value_stat">
                        <p>0</p>
                        <div class="stat">
                            <p>0 listed this week</p>
                        </div>
                    </div>
                </div>
                <div class="box">
                    <div class="head">
                        <h4>Active Rentals</h4>
                        <div class="icon2">
                            <ion-icon name="calendar-outline"></ion-icon>
                        </div>
                    </div>
                    <div class="value_stat">
                        <p>0</p>
                        <div class="stat">
                            <p>0 listed this week</p>
                        </div>
                    </div>
                </div>
                <div class="box">
                    <div class="head">
                        <h4>Total Earnings</h4>
                        <div class="icon3">
                            <ion-icon name="stats-chart-outline"></ion-icon>
                        </div>
                    </div>
                    <div class="value_stat">
                        <p>0</p>
                        <div class="stat">
                            <p>0 listed this week</p>
                        </div>
                    </div>
                </div>
                <div class="box">
                    <div class="head">
                        <h4>Pending Bookings</h4>
                        <div class="icon4">
                            <ion-icon name="notifications-outline"></ion-icon>
                        </div>
                    </div>
                    <div class="value_stat">
                        <p>0</p>
                        <div class="stat">
                            <p>0 listed this week</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="act">
                <div class="long-box">
                    <div class="heading">
                        <h3>Recent Rental Activity</h3>
                        <a href="">View All Transaction</a>
                    </div>
                    <div class="title">
                        <p>Renter</p>
                        <p>Gadget</p>
                        <p>Start Date</p>
                        <p>End Date</p>
                        <p>Status</p>
                        <p>Amount</p>
                    </div>
                    <div class="values">

                    </div>
                </div>
                <div class="short-box">
                    <div class="heading">
                        <h3>Quick Actions</h3>
                    </div>
                    <div class="btns">
                        <div class="btn-group">
                            <button>
                                <ion-icon name="add-outline"></ion-icon>
                                Add New Gadget
                            </button>
                        </div>
                        <div class="btn-group">
                            <button>
                                <ion-icon name="bag-handle-outline"></ion-icon>
                                View Public Store
                            </button>
                        </div>
                        <div class="btn-group">
                            <button>
                                <ion-icon name="document-outline"></ion-icon>
                                Export Earnings Report
                            </button>
                        </div>
                    </div>
                    <div class="partitions">
                        <h3>VERIFIED LOCATIONS</h3>
                        <p>Your listed items are currently available for meetup rentals in Malolos, Meycauayan, and
                            Plaridel.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

</body>
<script src="../../js/Seller-Page/seller.js"></script>

</html>

