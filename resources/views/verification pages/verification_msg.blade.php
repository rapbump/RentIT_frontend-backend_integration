<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lender verification | RentIT</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            align-items: center;
            background: #f5f6f8;
            color: #20232a;
            display: flex;
            font-family: Arial, sans-serif;
            justify-content: center;
            margin: 0;
            min-height: 100vh;
            padding: 24px;
        }

        main {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 12px 36px rgb(23 32 49 / 10%);
            max-width: 560px;
            padding: 40px;
            text-align: center;
            width: 100%;
        }

        .icon {
            align-items: center;
            background: #fff3e8;
            border-radius: 50%;
            color: #e87819;
            display: inline-flex;
            font-size: 30px;
            height: 64px;
            justify-content: center;
            margin-bottom: 20px;
            width: 64px;
        }

        h1 {
            font-size: 28px;
            margin: 0 0 12px;
        }

        p {
            color: #5d6470;
            line-height: 1.6;
            margin: 0 0 16px;
        }

        .status {
            background: #fff8ef;
            border-radius: 8px;
            color: #9a4d0b;
            display: inline-block;
            font-weight: 700;
            margin: 4px 0 20px;
            padding: 9px 14px;
        }

        button {
            background: #e87819;
            border: 0;
            border-radius: 8px;
            color: #fff;
            cursor: pointer;
            font-size: 15px;
            font-weight: 700;
            padding: 12px 24px;
        }

        button:hover {
            background: #cf6510;
        }

        @media (max-width: 480px) {
            main {
                padding: 32px 24px;
            }

            h1 {
                font-size: 24px;
            }
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
</head>
<body>
    @php($verificationStatus = auth()->user()->seller_verification_status ?? 'Pending')

    <main>
        <div class="icon" aria-hidden="true">!</div>

        @if (session('status'))
            <p role="status">{{ session('status') }}</p>
        @endif

        @if ($verificationStatus === 'Rejected')
            <h1>Your lender application needs attention</h1>
            <p>Your lender account has not been approved. Please contact RentIT support for guidance on the next steps.</p>
        @else
            <h1>Your lender account is awaiting verification</h1>
            <p>Thanks for applying to become a RentIT lender. Our team is reviewing your account. You can use lender features once your account is approved.</p>
        @endif

        <p class="status">Verification status: {{ $verificationStatus }}</p>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Log out</button>
        </form>
    </main>
</body>
</html>