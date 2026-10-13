<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/Admin_Page_CSS/sellers-stores.css" />
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Outfit:wght@100..900&display=swap"
        rel="stylesheet" />
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <title>Sellers & Stores</title>
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
                    <li class="active">
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
                    <li class="nav">
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
                <p class="intro">Verify ID, Barangay certifications, and storefront requirements for Bulacan lenders.</p>
            </div>
            <div class="category-btns">
                <button data-target="all">All Sellers</button>
                <button data-target="pending">Pending Applications</button>
                <button data-target="approved">Approved</button>
                <button data-target="rejected">Rejected</button>
            </div>
            <div class="categories-wrapper">
                <div class="category-content all">
                    <div class="all-container">
                        <div class="all-header">
                            <h2 class="all-head">
                                Pending Registration Approvals
                            </h2>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th>Store Name</th>
                                    <th>Applicant</th>
                                    <th>Date Applied</th>
                                    <th class="verify-btn">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Bulacan Gadgets</td>
                                    <td>Mateo Domingo</td>
                                    <td>Oct 22, 2026</td>
                                    <td class="verify-btn"><button>Verify</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="category-content pending">
                    <div class="pending-container">
                        <div class="pending-header">
                            <h2 class="pending-head">
                                Pending Registration Approvals
                            </h2>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th>Store Name</th>
                                    <th>Applicant</th>
                                    <th>Date Applied</th>
                                    <th class="verify-btn">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Bulacan Gadgets</td>
                                    <td>Mateo Domingo</td>
                                    <td>Oct 22, 2026</td>
                                    <td class="verify-btn"><button>Verify</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="category-content approved">
                    <div class="approved-container">
                        <div class="approved-header">
                            <h2 class="approved-head">
                                Approved Registration
                            </h2>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th>Store Name</th>
                                    <th>Applicant</th>
                                    <th>Date Applied</th>
                                    <th class="verify-btn">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Bulacan Gadgets</td>
                                    <td>Mateo Domingo</td>
                                    <td>Oct 22, 2026</td>
                                    <td class="approved_cont"><div class="stat-approved">Approved</div></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="category-content rejected">
                    <div class="reject-container">
                        <div class="reject-header">
                            <h2 class="reject-head">
                                Approved Registration
                            </h2>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th>Store Name</th>
                                    <th>Applicant</th>
                                    <th>Date Applied</th>
                                    <th class="verify-btn">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Bulacan Gadgets</td>
                                    <td>Mateo Domingo</td>
                                    <td>Oct 22, 2026</td>
                                    <td class="reject_cont"><div class="stat-reject">Approved</div></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>
<script src="/js/Admin-Page/sellers-stores.js"></script>
</html>