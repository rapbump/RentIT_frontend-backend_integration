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
    <link rel="stylesheet" href="../../css/Seller-Page_CSS/myStore.css">
    <title>My Store</title>
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
                    <li class="active">
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

    <main class="left">
        <section>
            <div class="myStore-head">
                <h2>Store Profile</h2>
                <div class="date">
                    <p class="date_p">Today: <span id="dateToday"></span></p>
                </div>

            </div>
        </section>
        <section>
            <div class="store-info">
                <div class="bgpic">
                    <div class="imag">
                        <img id="bg" src="../../images/bg.png" alt="">
                    </div>
                    <div class="choices">
                        <label class="c_bg" for="fileInput">
                            <ion-icon name="image-outline"></ion-icon>Change Background Photo
                        </label>
                        <input type="file" id="fileInput" accept="image/*" hidden>
                        <div class="close">
                            <a class="link" href=""><span><ion-icon name="close-outline"></ion-icon></span> Close</a>
                        </div>
                    </div>
                </div>
                <div class="prof_info">
                    <div class="prof">
                        <div class="prof_pic">
                            <img src="../../images/prof.png" alt="">
                        </div>
                        <div class="business_name">
                            <div class="head">
                                <h2>BanuasGadget Hub</h2>
                                <div class="status">
                                    <ion-icon name="shield-checkmark"></ion-icon>
                                    <p>Verified Lender</p>
                                </div>
                            </div>
                            <div class="rate_date">
                                <div class="review">
                                    <ion-icon name="star"></ion-icon>
                                    <p> 4.0 </p>
                                    <p class="rev">(12 reviews)</p>
                                </div>
                                <ion-icon name="ellipse"></ion-icon>
                                <div class="date">
                                    <p>Joined November 2023</p>
                                </div>
                            </div>
                            <p class="bio">BanuasGadget Hub is your trusted partner for affordable and reliable gadget
                                rentals. We offer a wide range of smartphones, laptops, tablets, cameras, and more
                                perfect for work, school, travel, or special occasions.</p>
                            <div class="services">
                                <div class="container">
                                    <ion-icon name="shield-checkmark"></ion-icon>
                                    <p class="s_p">Well Maintained Devices</p>
                                </div>
                                <div class="container">
                                    <ion-icon name="flash"></ion-icon>
                                    <p class="s_p">Fast & Easy Booking</p>
                                </div>
                                <div class="container">
                                    <ion-icon name="headset"></ion-icon>
                                    <p class="s_p">Customer Support</p>
                                </div>
                            </div>
                        </div>
                        <div class="edit">
                            <button type="submit"><span><ion-icon name="pencil"></ion-icon></span>Edit Profile</button>
                        </div>
                    </div>
                    <div class="store_info">
                        <h2 class="store">Store Information</h2>
                        <div class="info">
                            <div class="values">
                                <div class="infos">
                                    <div class="icons">
                                        <ion-icon name="storefront-outline"></ion-icon>
                                    </div>
                                    <div class="cont">
                                        <label for="">Store Name</label>
                                        <p>BanuasGadget Hub</p>
                                    </div>
                                </div>
                                <div class="infos">
                                    <div class="icons">
                                        <ion-icon name="location-outline"></ion-icon>
                                    </div>
                                    <div class="cont">
                                        <label for="">Location</label>
                                        <p>Location Baliuag, Bulacan</p>
                                    </div>

                                </div>
                                <div class="infos">
                                    <div class="icons">
                                        <ion-icon name="mail-outline"></ion-icon>
                                    </div>
                                    <div class="cont">
                                        <label for="">Email</label>
                                        <p>BanuasGadgetHub@gmail.com</p>
                                    </div>

                                </div>
                                <div class="infos">
                                    <div class="icons">
                                        <ion-icon name="call-outline"></ion-icon>
                                    </div>
                                    <div class="cont">
                                        <label for="">Contact Number</label>
                                        <p>+63 2345678910</p>
                                    </div>

                                </div>
                                <div class="infos">
                                    <div class="icons">
                                        <ion-icon name="time-outline"></ion-icon>
                                    </div>
                                    <div class="cont">
                                        <label for="">Business Hours</label>
                                        <p>Mon - Sun | 8:00 AM - 10:00 PM</p>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
        <section class="featured">
            <div class="featured_head">
                <h2>Featured Gadgets for Rent</h2>
                <a href="">View All <span><ion-icon name="arrow-forward-outline"></ion-icon></span></a>
            </div>
            <div class="cards">
                <div class="card_group">
                    <div class="display_image">
                        <img src="" alt="">
                    </div>
                    <div class="desc">
                        <h2>SMARTPHONES</h2>
                        <h3>iPhone 15 Pro Max (256GB)</h3>
                        <div class="rating">
                            <ion-icon name="star"></ion-icon>
                            <p class="p_rating">4.9</p>
                            <p class="rev_p">(18 reviews)</p>
                        </div>
                        <div class="prices">
                            <div class="price">
                                <p class="p_price">Rate per Day:</p>
                                <p>â‚±350</p>
                            </div>
                            <div class="rent btn">
                                <button type="submit">Rent Now</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card_group">
                    <div class="display_image">
                        <img src="" alt="">
                    </div>
                    <div class="desc">
                        <h2>SMARTPHONES</h2>
                        <h3>iPhone 15 Pro Max (256GB)</h3>
                        <div class="rating">
                            <ion-icon name="star"></ion-icon>
                            <p class="p_rating">4.9</p>
                            <p class="rev_p">(18 reviews)</p>
                        </div>
                        <div class="prices">
                            <div class="price">
                                <p class="p_price">Rate per Day:</p>
                                <p>â‚±350</p>
                            </div>
                            <div class="rent btn">
                                <button type="submit">Rent Now</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card_group">
                    <div class="display_image">
                        <img src="" alt="">
                    </div>
                    <div class="desc">
                        <h2>SMARTPHONES</h2>
                        <h3>iPhone 15 Pro Max (256GB)</h3>
                        <div class="rating">
                            <ion-icon name="star"></ion-icon>
                            <p class="p_rating">4.9</p>
                            <p class="rev_p">(18 reviews)</p>
                        </div>
                        <div class="prices">
                            <div class="price">
                                <p class="p_price">Rate per Day:</p>
                                <p>â‚±350</p>
                            </div>
                            <div class="rent btn">
                                <button type="submit">Rent Now</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card_group">
                    <div class="display_image">
                        <img src="" alt="">
                    </div>
                    <div class="desc">
                        <h2>SMARTPHONES</h2>
                        <h3>iPhone 15 Pro Max (256GB)</h3>
                        <div class="rating">
                            <ion-icon name="star"></ion-icon>
                            <p class="p_rating">4.9</p>
                            <p class="rev_p">(18 reviews)</p>
                        </div>
                        <div class="prices">
                            <div class="price">
                                <p class="p_price">Rate per Day:</p>
                                <p>â‚±350</p>
                            </div>
                            <div class="rent btn">
                                <button type="submit">Rent Now</button>
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


