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
    <link rel="stylesheet" href="../../css/Verifications_Css/sVerify.css">
    <title>Verification</title>
</head>

<body>
    <header>
        <div class="logo">
            <img src="../../images/logo-icon-wrap.png" alt="">
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
                    <form action="">
                        <label for="name">Official Real Name ( Must match ID)</label>
                        <input type="text" placeholder="Exp: Juan Dela Cruz.">
                        <label for="">Government-Issued ID Upload</label>
                        <div class="upload-box">
                            <div class="upload-icon">
                                <ion-icon name="cloud-upload-outline"></ion-icon>
                            </div>
                            <p class="upload-text">Drag & drop UMID, Passport, or Driverâ€™s License</p>
                            <p class="upload-subtext">PNG, JPG or PDF up to 10MB.</p>
                            <input type="file" id="fileUpload" accept=".png,.jpg,.jpeg,.pdf" hidden>
                        </div>
                        <label for="">Residential Address</label>
                        <div class="residecy_box">
                            <div class="pro_city">
                                <label for="">Province</label>
                                <input type="text">
                                <label for="">City/Province</label>
                                <input type="text">
                            </div>
                            <div class="brgy">
                                <label for="">Barangay</label>
                                <input type="text">
                                <label for="">Street / House No.</label>
                                <input type="text">
                            </div>
                        </div>
                        <label for="residenceUpload">Proof of Residence</label>
                        <div class="residence-upload" id="residenceUpload">
                            <ion-icon name="document-text-outline"></ion-icon>
                            <span>Upload (must show matching name/address)</span>
                            <input type="file" accept=".png,.jpg,.jpeg,.pdf" hidden>
                        </div>
                        <div class="h_label">
                            <label for="">Barangay Captain Certification</label>
                            <p>Optional</p>
                        </div>
                        <input type="file">
                        <div class="submit_btn">
                            <button type="reset" class="cancel">Cancel</button>
                            <button type="submit" class="submit">Submit Application</button>
                        </div>
                    </form>
                </div>

            </div>
        </section>
    </main>
</body>

</html>


