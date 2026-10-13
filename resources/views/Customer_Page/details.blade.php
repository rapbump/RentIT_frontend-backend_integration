<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/Customer_Page_CSS/details.css">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Outfit:wght@100..900&display=swap"
        rel="stylesheet">
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <title>Details</title>
</head>

<body>
    <header>
        <div class="logo">
            <img src="../../images/logo-icon-wrap.png" alt="" />
            <div class="name">
                <h2 class="black">Rent</h2>
                <h2 class="orange">IT</h2>
            </div>
        </div>
        <div class="navs">
            <a href="./customer_browse.html">Browse Gadgets</a>
            <a href="./my_rentals.html">My Rentals</a>
        </div>
        <div class="profile">
            <div class="pic">
                <img src="../../images/76e11a76-2275-47ba-83c3-40969564e34d.jpg" alt="">
            </div>
            <div class="name-stats">
                <p class="Uname">Raphael Valmocina</p>
                <p class="status">Verified Customer</p>
            </div>
        </div>
    </header>
    <main>
        <section>
            <div class="left-content">
                <div class="gallery-card">
                    <div class="main-image">
                        <img src="" alt="">
                    </div>
                    <!--Loop this-->
                    <div class="smaller-images">
                        <img src="" alt="">
                    </div>
                </div>
                <div class="details-card">
                    <div class="details-desc">
                        <div class="details-header">
                            <h2>Product Details</h2>
                        </div>
                        <div class="product-desc">
                            <p class="description">The Sony Alpha 7 IV sets the standard for modern full-frame cameras.
                                Combining hybrid photo and video excellence, it features a newly developed 33MP sensor,
                                advanced AI autofocus, and 4K 60p video capabilities. Ideal for event coverage, high-end
                                content creation, and cinematic Bulacan outdoor shoots.</p>
                        </div>
                    </div>
                    <div class="specs-container">
                        <div class="spec-heading">
                            <h3>Technical Specifications</h3>
                        </div>
                        <div class="specs">
                            <div class="spec-name">
                                <p class="s-name">Sensor Size</p>
                            </div>
                            <div class="spec-value">
                                <p class="v-spec">Full-Frame (35.9 x 23.9 mm)</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="reviews-card">
                    <div class="rating-header">
                        <h2>Renter Reviews</h2>
                    </div>
                    <div class="reviews-scrollable">
                        <div class="stmt-cont">
                            <div class="reviewer-name-date">
                                <div class="name-status">
                                    <p class="rev-name">Sarah G.</p>
                                    <div class="p-container">
                                        <p class="status">Verified Renter</p>
                                    </div>
                                </div>
                                <div class="date">
                                    <p class="dates">Oct 12, 2026</p>
                                </div>
                            </div>
                            <div class="review-statement">
                                <p class="rev-stmt">Used this camera body for a wedding coverage in Malolos. It was
                                    perfectly calibrated, sensory clean, and the owner was very easy to negotiate with!
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="right-content">
                <div class="booking-card">
                    <div class="computation-cont">
                        <div class="price-display">
                            <p class="peso">â‚±1,200 </p>
                            <p class="per-day">/ day</p>
                        </div>
                        <div class="condition">
                            <div class="condition-cont">
                                <p class="txt">Condition: </p>
                                <p class="like">Like New</p>
                            </div>
                            <div class="dev-type">
                                <p class="type">Sony Alpha 7 IV</p>
                            </div>
                        </div>
                    </div>
                    <div class="booking-form">
                        <form action="">
                            <div class="start">
                                <label for="">RENTAL START DATE</label>
                                <input type="date">
                            </div>
                            <div class="end">
                                <label for="">RENTAL END DATE</label>
                                <input type="date">
                            </div>
                        </form>
                    </div>
                    <div class="price-computation">
                        <div class="comp">
                            <div>
                                <p class="computation">â‚±1,200 x 3 days</p>
                            </div>
                            <div>
                                <p class="total">â‚±3,600</p>
                            </div>
                        </div>
                        <div class="est-total">
                            <div class="est-txt">
                                <p class="est-text">Estimated Total</p>
                            </div>
                            <div class="overall-total">
                                <p class="overall-t">â‚±3,750</p>
                            </div>
                        </div>
                    </div>
                    <div class="book-button">
                        <button>Book Rental</button>
                    </div>
                </div>
                <div class="meet-up-card">
                    <div class="meet-header">
                        <h4>Lender & Meet-up Location</h4>
                    </div>
                    <div class="meet-prof">
                        <div class="pic">
                            <img src="" alt="">
                        </div>
                        <div class="name-level">
                            <p class="seller-name">Clarity Studios</p>
                            <p class="level">Lender Level 3 - Malolos </p>
                        </div>
                    </div>
                    <div class="designated-loc">
                        <div class="loc-header">
                            <h2>DESIGNATED MEET-UP ZONE</h2>
                        </div>
                        <div class="loc">
                            <p class="location">Kambingan, Malolos, Bulacan</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <footer id="About">
        <div class="whole">
            <div class="f-g">
                <div class="f-logo">
                    <img src="../../images/logo-icon-wrap.png" alt="">
                    <h4 class="puti">Rent</h4>
                    <h4 class="it">IT</h4>
                </div>
                <div>
                    <p>The leading peer-to-peer gadget rental marketplace in Bulacan, Philippines. Elevating access to
                        technology
                        with zero commitment.</p>
                </div>
            </div>
            <div class="creds">
                <div class="b">
                    <h5>Browse</h5>
                    <p>Smartphones</p>
                    <p>Laptops</p>
                    <p>Tablets</p>
                    <p>Cameras</p>
                </div>
                <div class="b">
                    <h5>Community</h5>
                    <p>How It Works</p>
                    <p>Apply as Seller</p>
                    <p>Safety Guidelines</p>
                </div>
            </div>
        </div>
        <div class="statement">
            <div class="statements">
                <p>Â© 2026 RentIT Philippines. All rights reserved.</p>
            </div>
            <div class="fstate">
                <p>Privacy Policy</p>
                <p>Terms of Service</p>
            </div>
        </div>
    </footer>
</body>

</html>


