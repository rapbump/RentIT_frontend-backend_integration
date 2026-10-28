<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Outfit:wght@100..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/Opening_Display_CSS/reg.css') }}">
    <title>Registration</title>
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
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
            <a href="{{ route('homepage')}}#Browse">Browse</a>
            <a href="{{ route('homepage')}}#HIW">How It Works</a>
            <a href="#About">About</a>
        </div>
        <div class="inter-btns">
            <a href=" {{ url('/login') }} " class="log">Login</a>
        </div>
    </header>
    <main>
        <section>
            <div class="opening">
                <div class="txt">
                    <div class="text">
                        <h1>Access any technology without buying it. </h3>
                        <p>Join the RentIT community in Bulacan today. Rent tested and verified tech, or start listing your own gadgets to earn passive income safely.</p>
                    </div>
                    <div class="txt-cont">
                        <div class="txt-check">
                            <p><img src="{{ asset('images/check.png') }}" alt="">Payment escrow guarantee and damage coverage</p>
                        </div>
                        <div class="txt-check">
                            <p><img src="{{ asset('images/check.png') }}" alt="">Convenient designated meet-up locations</p>
                        </div>
                        <div class="txt-check">
                            
                            <p><img src="{{ asset('images/check.png') }}" alt="">Verified local Bulacan renters and sellers</p>
                        </div>
                    </div>
                </div>
                <div class="wrapper registration-form {{ $errors->default->any() ? 'show-signup' : '' }}">
                    <div class="box-btn">
                        <div class="pBox1">
                            <a class="logIn-Link">Log In</a>
                        </div>
                        <div class="pBox2">
                            <a class="signUp-Link">Sign Up</a>
                        </div>
                    </div>
                    <div class="form-box-signup">
                        <form action="{{ route('users') }}" method="POST">
                            @csrf
                            @if ($errors->default->any())
                                <p class="form-alert" role="alert">Please correct the highlighted fields and try again.</p>
                            @endif
                            <div class="form-name">
                                <label for="name">Full Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Juan Dela Cruz" maxlength="255" required>
                                @error('name')
                                    <p class="form-error-message" role="alert">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="form-eTel">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder=".....@gmail.com" maxlength="255" required>
                                    @error('email')
                                        <p class="form-error-message" role="alert">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" placeholder="09123456789" maxlength="20" required>
                                    @error('phone')
                                        <p class="form-error-message" role="alert">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-pass">
                                <label for="signup-password">Password</label>
                                <input type="password" name="password" id="signup-password" placeholder="At least 8 characters" minlength="8" required>
                                @error('password')
                                    <p class="form-error-message" role="alert">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-radio">
                                <div class="form-box1">
                                    <input type="radio" id="renter" name="role" value="user" @checked(old('role') === 'user') required>
                                    <label for="renter">Renter</label>
                                </div>
                                <div class="form-box2" >
                                    <input type="radio" id="lender" name="role" value="lender" @checked(old('role') === 'lender')>
                                    <label for="lender">Lender</label>
                                </div>
                            </div>

                            <div class="form-terms">
                                <input type="checkbox" name="terms" id="terms" value="1" @checked(old('terms')) required>
                                <p><label for="terms">I agree to the Terms of Service &amp; Privacy Policy</label></p>
                            </div>
                            @error('terms')
                                <p class="form-error-message" role="alert">{{ $message }}</p>
                            @enderror
                            <div class="swap">
                                <button type="submit">Create an Account</button>
                            </div>
                        </form>
                    </div>
                    <div class="form-box-login">
                        <h1>Log In</h1>
                        <form action="{{ route('login')}}" method="POST">
                            @csrf
                            @if (session('success'))
                                <p class="form-success" role="status">{{ session('success') }}</p>
                            @endif
                            @if ($errors->login->any())
                                <p class="form-alert" role="alert">{{ $errors->login->first() }}</p>
                            @endif
                            <div class="form-name">
                                <label for="login-email">Email</label>
                                <input type="email" name="email" id="login-email" value="{{ old('email') }}" placeholder=".....@gmail.com" required>
                            </div>
                            <div class="form-name">
                                <label for="login-password">Password</label>
                                <input type="password" name="password" id="login-password" placeholder="********" required>
                            </div>
                            <div class="rememForgot">
                                <div class="rem">
                                    <input type="checkbox" name="remember"> Remember Me?
                                </div>
                                <div class="forgot">
                                    <a href="">Forgot Password</a>
                                </div>
                            </div>
                            <div class="swap">
                                <button type="submit">Log In</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <script src="{{ asset('js/Registration/reg.js')}}"></script>
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
                    <p>The leading peer-to-peer gadget rental marketplace in Bulacan, Philippines. Elevating access to
                        technology with zero commitment.</p>
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
