<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'config.php';

$bookingId = filter_input(INPUT_GET, 'booking_id', FILTER_VALIDATE_INT);
$booking = null;
$error = '';

if (!$bookingId) {
    $error = 'No booking was selected for payment.';
} else {
    $userId = ctype_digit((string) $_SESSION['user_id']) ? (int) $_SESSION['user_id'] : 0;
    $lookup = $conn->prepare(
        'SELECT booking_id, booking_type, travel_date, passengers, total_amount FROM bookings WHERE booking_id = ? AND user_id = ?'
    );
    $lookup->bind_param('ii', $bookingId, $userId);
    $lookup->execute();
    $booking = $lookup->get_result()->fetch_assoc();

    if (!$booking) {
        $error = 'This booking could not be found.';
    }
}

$total = $booking ? (float) $booking['total_amount'] : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment | Razz Travels</title>
    <style>
        :root {
            --navy-900: #071d35;
            --navy-800: #0b2d4f;
            --navy-700: #124d7b;
            --sky-500: #2d9cdb;
            --orange-500: #f59e0b;
            --orange-600: #df7d16;
            --ink: #17324d;
            --muted: #62748a;
            --line: #dfeaf4;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            color: var(--ink);
            background:
                linear-gradient(135deg, rgba(7,29,53,.97), rgba(18,77,123,.88)),
                radial-gradient(circle at 85% 10%, rgba(245,158,11,.7), transparent 28%),
                var(--navy-900);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            width: min(1000px, 92%);
            margin: 0 auto;
            padding: 28px 0 12px;
            color: white;
        }

        header a { color: white; text-decoration: none; font-weight: 700; }

        main {
            width: min(1000px, 92%);
            margin: 24px auto 60px;
        }

        .panel {
            display: grid;
            grid-template-columns: 1fr 1.1fr;
            gap: 34px;
            padding: clamp(24px, 5vw, 48px);
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 28px 70px rgba(0,0,0,.22);
        }

        h1 {
            margin: 0 0 8px;
            font: 800 clamp(2rem, 5vw, 3.2rem) "Segoe UI", sans-serif;
            letter-spacing: -0.04em;
            color: var(--navy-800);
        }

        .subtitle { margin: 0 0 30px; color: var(--muted); }

        .summary {
            padding: 22px;
            border-radius: 16px;
            color: white;
            background: linear-gradient(135deg, var(--navy-800), var(--navy-700));
        }

        .summary h2 { margin-top: 0; font-size: 1.1rem; }

        .summary-row {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            padding: 12px 0;
            border-bottom: 1px solid rgba(255,255,255,.18);
        }

        .summary-row:last-child { border: 0; }

        .total {
            margin-top: 18px;
            font-size: 1.8rem;
            font-weight: 800;
            color: #ffc477;
        }

        label {
            display: block;
            margin-bottom: 16px;
            color: var(--ink);
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .05em;
            text-transform: uppercase;
        }

        input {
            width: 100%;
            margin-top: 8px;
            padding: 13px 14px;
            border: 1px solid var(--line);
            border-radius: 12px;
            color: var(--ink);
            background: #f8fafc;
            font: 1rem "Segoe UI", sans-serif;
        }

        input:focus {
            outline: none;
            border-color: var(--sky-500);
            box-shadow: 0 0 0 4px rgba(45, 156, 219, 0.12);
        }

        .two-column { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

        button {
            width: 100%;
            padding: 14px;
            border: 0;
            border-radius: 12px;
            color: white;
            background: linear-gradient(135deg, var(--orange-500), var(--orange-600));
            cursor: pointer;
            font-size: 1rem;
            font-weight: 800;
            box-shadow: 0 16px 28px rgba(245, 158, 11, 0.22);
        }

        button:disabled { cursor: default; opacity: .75; }

        .notice {
            padding: 14px;
            border-radius: 10px;
            color: #a12620;
            background: #fff0ef;
            font-weight: 700;
        }

        .success {
            display: none;
            padding: 14px;
            border-radius: 10px;
            color: #17603a;
            background: #e9f8ef;
            font-weight: 700;
        }

        .fine-print {
            margin: 14px 0 0;
            color: var(--muted);
            font-size: .85rem;
            text-align: center;
        }

        @media (max-width: 720px) {
            header { align-items: flex-start; flex-direction: column; }
            .panel { grid-template-columns: 1fr; }
            .two-column { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <header><strong>RAZZ TRAVELS</strong><a href="dashboard.php">Back to dashboard</a></header>
    <main>
        <?php if ($error !== ''): ?>
            <section class="panel"><div><h1>Payment unavailable</h1><p class="notice"><?= htmlspecialchars($error) ?></p><p><a href="trip.php">Return to trips</a></p></div></section>
        <?php else: ?>
            <section class="panel">
                <div>
                    <h1>Complete your payment.</h1>
                    <p class="subtitle">Review your booking and pay securely with this demo checkout.</p>
                    <div class="summary">
                        <h2>Booking summary</h2>
                        <div class="summary-row"><span>Booking</span><strong>#<?= (int) $booking['booking_id'] ?></strong></div>
                        <div class="summary-row"><span>Service</span><strong><?= htmlspecialchars(ucfirst($booking['booking_type'])) ?></strong></div>
                        <div class="summary-row"><span>Travel date</span><strong><?= htmlspecialchars($booking['travel_date']) ?></strong></div>
                        <div class="summary-row"><span>Passengers / rooms</span><strong><?= (int) $booking['passengers'] ?></strong></div>
                        <div class="total">₹<?= number_format($total, 2) ?></div>
                    </div>
                </div>
                <form id="payment-form">
                    <label for="card-name">Name on card<input id="card-name" type="text" autocomplete="cc-name" required></label>
                    <label for="card-number">Card number<input id="card-number" type="text" inputmode="numeric" autocomplete="cc-number" placeholder="1234 5678 9012 3456" minlength="12" required></label>
                    <div class="two-column">
                        <label for="expiry">Expiry date<input id="expiry" type="text" autocomplete="cc-exp" placeholder="MM/YY" required></label>
                        <label for="cvv">CVV<input id="cvv" type="password" inputmode="numeric" autocomplete="cc-csc" maxlength="4" required></label>
                    </div>
                    <button type="submit">Pay ₹<?= number_format($total, 2) ?></button>
                    <p class="fine-print">Demo payment only. No real money will be charged.</p>
                    <p id="success-message" class="success" role="status">Payment successful! Your booking is confirmed for this demo.</p>
                </form>
            </section>
        <?php endif; ?>
    </main>
    <script>
        const paymentForm = document.getElementById('payment-form');
        if (paymentForm) {
            paymentForm.addEventListener('submit', (event) => {
                event.preventDefault();
                paymentForm.querySelector('button').disabled = true;
                paymentForm.querySelector('button').textContent = 'Payment complete';
                document.getElementById('success-message').style.display = 'block';
            });
        }
    </script>
</body>
</html>
