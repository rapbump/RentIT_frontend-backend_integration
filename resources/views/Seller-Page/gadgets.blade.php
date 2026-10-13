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
    <link rel="stylesheet" href="../../css/Seller-Page_CSS/gadgets.css">
    <title>Gadgets</title>
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
                    <li class="active">
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
    <main class="left">
        <section>
            <div class="gadgets_head">
                <div class="txt">
                    <h2>My Gadgets</h2>
                    <p>Manage and edit your listed technology equipment.</p>
                </div>
                <div class="txt_btn">
                    <button type="submit">
                        <ion-icon name="add-outline"></ion-icon>
                        Add Gadget</button>
                </div>
            </div>
            <div class="search-box">
                <ion-icon name="search-outline"></ion-icon>
                <input type="search" placeholder="Search products...">
            </div>
            <div class="container">
                <div class="box">
                    <div class="name-price-pic">
                        <div class="pic-box">
                            <img src="../../images/cp.png" alt="">
                        </div>
                        <div class="name-price">
                            <div class="category-status">
                                <div class="category">
                                    <p>SMARTPHONE</p>
                                </div>
                                <div class="status">
                                    <p>Available</p>
                                </div>
                            </div>
                            <div class="name">
                                <p>iPhone 15 Pro Max (256GB)</p>
                            </div>
                            <div class="price">
                                <p>â‚±350 / day</p>
                            </div>
                        </div>
                    </div>
                    <div class="modify-remove">
                        <div class="setting">
                            <button>
                                <ion-icon name="settings-outline"></ion-icon>
                            </button>
                        </div>
                        <div class="remove">
                            <button>
                                <ion-icon name="close-circle-outline"></ion-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>

</html>


