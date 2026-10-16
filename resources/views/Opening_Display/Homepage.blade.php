<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/Opening_Display_CSS/style.css') }}">
  <title>Homepage</title>
</head>

<body>
  <header>
    <div class="logo">
      <img src="{{ asset('images/logo-icon-wrap.png') }}" alt="">
      <h2 class="black">Rent</h2>
      <h2 class="orange">IT</h2>
    </div>
    <div class="navs">
      <a href="{{ route('homepage')}}">Home</a>
      <a href="#Browse">Browse</a>
      <a href="#HIW">How It Works</a>
      <a href="#About">About</a>
    </div>
    <div class="inter-btns">
      <a href="{{ url('login') }}" class="log">Login</a>
    </div>
  </header>
  <main>
    <section class="land">
      <div class="land-cont">
        <div class="notif">
          <img src="{{ asset('images/Ellipse.png') }}" alt="">
          <p>NOW AVAILABLE IN BULACAN</p>
        </div>
        <div class="slogan">
          <h1>Rent Gadgets</h1>
          <h1>Anytime, Anywhere</h1>
        </div>
        <div class="desc">
          <p>High-quality, affordable gadget rentals in Bulacan province. Get access to the tech you need without
            purchasing it. Fast meetups and tested quality.</p>
        </div>
        <div class="land-btns">
          <div class="bg-pic">
            <a class="BG">Browse Gadgets</a>
            <img src="{{ asset('images/arrow-right.png') }}" alt="">
          </div>
          <div class="as">
            <a class="AS">Apply as Seller</a>
          </div>
        </div>
      </div>
      <div class="pic-cont">
        <img src="{{ asset('images/comp.png') }}" alt="">
      </div>
    </section>

    <section class="Browse" id="Browse">
      <div class="search-bar">
        <div class="search-cont">
          <img src="{{ asset('images/search.png') }}" alt="">
          <input type="text" placeholder="Search for laptops, iPhones, cameras...">
          <button>Search</button>
        </div>
      </div>
      <div class="categories">
        <div class="cat-title">
          <p>Popular Categories</p>
        </div>
        <div class="cat-btns">
          <button>Smartphones</button>
          <button>Laptops</button>
          <button>Tablets</button>
          <button>Cameras</button>
        </div>
      </div>
      <div class="feat">
        <p>Featured Gadgets for Rent</p>
        <a href="">View All</a>
      </div>
      <div class="cards">
        <div class="card-cont">
          <div class="card">
            <img src="{{ asset('images/cp.png') }}" alt="">
            <div class="card-info">
              <h5>Smartphone</h5>
              <h4>Iphone 2021</h4>
              <div class="rate">
                <img src="{{ asset('images/star.png') }}" alt="">
                <p>4.5</p>
                <p>(10 reviews)</p>
              </div>
              <div class="rent">
                <div class="rpd">
                  <p>Rate per day:</p>
                  <h5>â‚±800/day</h5>
                </div>
                <button class="rent-btn">Rent Now</button>
              </div>
            </div>
          </div>
          <div class="card">
            <img src="{{ asset('images/laptop.png') }}" alt="">
            <div class="card-info">
              <h5>Laptop</h5>
              <h4>Mac Book 2022</h4>
              <div class="rate">
                <img src="{{ asset('images/star.png') }}" alt="">
                <p>4.5</p>
                <p>(10 reviews)</p>
              </div>
              <div class="rent">
                <div class="rpd">
                  <p>Rate per day:</p>
                  <h5>â‚±800/day</h5>
                </div>
                <button class="rent-btn">Rent Now</button>
              </div>
            </div>
          </div>
          <div class="card">
            <img src="{{ asset('images/cam.png') }}" alt="">
            <div class="card-info">
              <h5>Camera</h5>
              <h4>Canon EOS R5</h4>
              <div class="rate">
                <img src="{{ asset('images/star.png') }}" alt="">
                <p>4.5</p>
                <p>(10 reviews)</p>
              </div>
              <div class="rent">
                <div class="rpd">
                  <p>Rate per day:</p>
                  <h5>â‚±800/day</h5>
                </div>
                <button class="rent-btn">Rent Now</button>
              </div>
            </div>
          </div>
          <div class="card">
            <img src="{{ asset('images/tablet.png') }}" alt="">
            <div class="card-info">
              <h5>Tablet</h5>
              <h4>iPad Pro 2022</h4>
              <div class="rate">
                <img src="{{ asset('images/star.png') }}" alt="">
                <p>4.5</p>
                <p>(10 reviews)</p>
              </div>
              <div class="rent">
                <div class="rpd">
                  <p>Rate per day:</p>
                  <h5>â‚±800/day</h5>
                </div>
                <button class="rent-btn">Rent Now</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="HIW" id="HIW">
      <div class="intro">
        <h3>How it Works</h3>
        <p>Renting high-quality gadgets is simple and safe.</p>
      </div>
      <div class="steps">
        <div class="step1">
          <div class="step-pic">
            <img src="{{ asset('images/blue.png') }}" alt="">
            <h2>01</h2>
          </div>
          <h5>Browse & Choose</h5>
          <p>Filter through verified gadgets in Bulacan based on your needs and budget.</p>
        </div>
        <div class="step1">
          <div class="step-pic">
            <img src="{{ asset('images/note.png') }}" alt="">
            <h2>02</h2>
          </div>
          <h5>Book & Pay Safely</h5>
          <p>Request booking dates and submit payment securely through RentIT's platform.</p>
        </div>
        <div class="step1">
          <div class="step-pic">
            <img src="{{ asset('images/agree.png') }}" alt="">
            <h2>03</h2>
          </div>
          <h5>Meet Up & Rent</h5>
          <p>Coordinate a secure meet-up at safe designated spots within Bulacan to receive the item.</p>
        </div>
      </div>
    </section>
  </main>
  <footer id="About">
    <div class="whole">
      <div class="f-g">
        <div class="f-logo">
          <img src="{{ asset('images/logo-icon-wrap.png') }}" alt="">
          <h4 class="puti">Rent</h4>
          <h4 class="it">IT</h4>
        </div>
        <div>
          <p>The leading peer-to-peer gadget rental marketplace in Bulacan, Philippines. Elevating access to technology
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

