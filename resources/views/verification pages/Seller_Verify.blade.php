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
    <link rel="stylesheet" href="{{ asset('css/Verifications_Css/sVerify.css') }}">
    <title>Verification</title>
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
</head>

<body>
    <header>
        <div class="logo">
            <img src="{{ asset('images/logo-icon-wrap.png') }}" alt="">
            <h2 class="black">Rent</h2>
            <h2 class="orange">IT</h2>
        </div>
        <div></div>
    </header>
    <main>
        <section class="land">
            <div class="app-stat">
                <div class="status-box">
                    <h1>APPLICATION STATUS</h1>
                    <div class="head">
                        <div class="prof">
                            <p>Seller Profile</p>
                        </div>
                        <div class="status">
                            <ion-icon name="ellipse"></ion-icon>
                            <p>Pending Approval</p>
                        </div>
                    </div>
                    <div class="desc">
                        <p>Our admin team in Bulacan manually reviews all government credentials and residency documents
                            within
                            24â€“48 hours to ensure a secure renting community.</p>
                    </div>
                </div>
                <div class="rules-box">
                    <h1>Bulacan Verification Rules</h1>
                    <div class="rules">
                        <ion-icon name="ellipse"></ion-icon>
                        <p>ID must be clear and not expired.</p>
                    </div>
                    <div class="rules">
                        <ion-icon name="ellipse"></ion-icon>
                        <p>Proof of Residence must match your city dropdown.</p>
                    </div>
                    <div class="rules">
                        <ion-icon name="ellipse"></ion-icon>
                        <p>Barangay Captain Certification speeds up verification.</p>
                    </div>
                </div>
            </div>
            <div class="app">
                <div class="txt">
                    <h1>Apply as a Verified Seller</h1>
                    <p>Fill in your official details to start earning from your listed gadgets.</p>
                </div>
                <div class="form-box">
                    @if ($errors->any())
                        <div class="form-errors" role="alert">
                            <p>Please complete the required information:</p>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('seller.verify.submit') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <label for="official_name">Official Real Name (must match ID)</label>
                        <input type="text" name="official_name" id="official_name" value="{{ old('official_name', $lender->verification_official_name) }}" placeholder="Juan Dela Cruz" maxlength="255" required>
                        <label for="government_id">Government-Issued ID Upload</label>
                        <div class="upload-box">
                            <div class="upload-icon">
                                <ion-icon name="cloud-upload-outline"></ion-icon>
                            </div>
                            <p class="upload-text">Upload a clear UMID, Passport, or Driver's License</p>
                            <p class="upload-subtext">PNG, JPG or PDF up to 10MB.</p>
                            <input type="file" name="government_id" id="government_id" accept=".png,.jpg,.jpeg,.pdf" required>
                        </div>
                        <label>Residential Address</label>
                        <div class="residecy_box">
                            <div class="pro_city">
                                <label for="province">Province</label>
                                <input type="text" name="province" id="province" value="{{ old('province', $lender->verification_province) }}" maxlength="255" required>
                                <label for="city">City / Municipality</label>
                                <input type="text" name="city" id="city" value="{{ old('city', $lender->verification_city) }}" maxlength="255" required>
                            </div>
                            <div class="brgy">
                                <label for="barangay">Barangay</label>
                                <input type="text" name="barangay" id="barangay" value="{{ old('barangay', $lender->verification_barangay) }}" maxlength="255" required>
                                <label for="street_address">Street / House No.</label>
                                <input type="text" name="street_address" id="street_address" value="{{ old('street_address', $lender->verification_street_address) }}" maxlength="500" required>
                            </div>
                        </div>
                        <label for="residence_proof">Proof of Residence</label>
                        <div class="upload-box">
                            <p class="upload-text">Upload a document showing your name and matching address</p>
                            <p class="upload-subtext">PNG, JPG or PDF up to 10MB.</p>
                            <input type="file" name="residence_proof" id="residence_proof" accept=".png,.jpg,.jpeg,.pdf" required>
                        </div>
                        <div class="h_label">
                            <label for="barangay_certification">Barangay Captain Certification</label>
                            <p>Optional</p>
                        </div>
                        <input type="file" name="barangay_certification" id="barangay_certification" accept=".png,.jpg,.jpeg,.pdf">
                        <div class="submit_btn">
                            <button type="submit" class="cancel" form="verification-logout">Log out</button>
                            <button type="submit" class="submit">Submit Application</button>
                        </div>
                    </form>
                    <form id="verification-logout" method="POST" action="{{ route('logout') }}" hidden>
                        @csrf
                    </form>
                </div>

            </div>
        </section>
    </main>
</body>

</html>
