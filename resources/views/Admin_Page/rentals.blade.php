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
                        <a href="./admin_dashboard.html" class="links">
                            <ion-icon name="bar-chart-outline"></ion-icon>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav">
                        <a href="./seller-and-stores.html" class="links">
                            <ion-icon name="bag-handle-outline"></ion-icon>
                            Sellers & Stores
                        </a>
                    </li>
                    <li class="nav">
                        <a href="./gadgets.html" class="links">
                            <ion-icon name="phone-portrait-outline"></ion-icon>
                            Renters
                        </a>
                    </li>
                    <li class="active">
                        <a href="./rentals.html" class="links">
                            <ion-icon name="people-outline"></ion-icon>
                            Rentals
                        </a>
                    </li>
                    <li class="nav">
                        <a href="./transaction.html" class="links">
                            <ion-icon name="document-outline"></ion-icon>
                            Reports
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
        <section class="main-content">
            <div class="main-header">
                <h2 class="head">Manage Sellers and Verification</h2>
                <p class="intro">
                    Verify ID, Barangay certifications, and storefront requirements for
                    Bulacan lenders.
                </p>
            </div>
            <div class="overview-cont">
                <div class="active">
                    <h2 class="active-head">Active Rentals Overview</h2>
                </div>
                <table>
                    <thead class="table-head">
                        <tr>
                            <th>Renter</th>
                            <th>Lender Store</th>
                            <th>Gadget</th>
                            <th>Rental Term</th>
                            <th>Total Payout</th>
                            <th class="stats">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="datas">
                            <td class="renter-name">Alfredo Manangan</td>
                            <td>Bulacan Gadgets</td>
                            <td>MacBook Pro M3 (14-inch)</td>
                            <td>Oct 20 - Oct 25, 2026</td>
                            <td class="price">₱4,250</td>
                            <td class="status">
                                <div class="data">
                                    <p>Active</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>

</html>