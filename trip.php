<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'config.php';

$message = '';
$messageType = '';
$selectedType = $_POST['booking_type'] ?? $_GET['type'] ?? 'flight';

function cleanDate(string $date): string
{
    $parsed = DateTime::createFromFormat('Y-m-d', $date);
    return $parsed && $parsed->format('Y-m-d') === $date ? $date : '';
}

function getOptions(mysqli $conn, string $type): array
{
    $queries = [
        'flight' => "SELECT flight_id AS item_id, CONCAT(airline, ' ', flight_number, ' - ', from_city, ' to ', to_city) AS label, price, seats FROM flights ORDER BY from_city, to_city",
        'train' => "SELECT train_id AS item_id, CONCAT(train_name, ' ', train_number, ' - ', from_city, ' to ', to_city) AS label, price, seats FROM trains ORDER BY from_city, to_city",
        'hotel' => "SELECT hotel_id AS item_id, CONCAT(hotel_name, ' - ', city, ' (', room_type, ')') AS label, price_per_night AS price, rooms_available AS seats FROM hotels ORDER BY city, hotel_name"
    ];

    if (!isset($queries[$type])) {
        return [];
    }

    $result = $conn->query($queries[$type]);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_now'])) {
    $selectedType = $_POST['booking_type'] ?? '';
    $itemId = filter_input(INPUT_POST, 'item_id', FILTER_VALIDATE_INT);
    $travelDate = cleanDate(trim($_POST['travel_date'] ?? ''));
    $passengers = filter_input(INPUT_POST, 'passengers', FILTER_VALIDATE_INT);

    $table = ['flight' => 'flights', 'train' => 'trains', 'hotel' => 'hotels'][$selectedType] ?? null;
    $idColumn = ['flight' => 'flight_id', 'train' => 'train_id', 'hotel' => 'hotel_id'][$selectedType] ?? null;
    $priceColumn = $selectedType === 'hotel' ? 'price_per_night' : 'price';
    $availabilityColumn = $selectedType === 'hotel' ? 'rooms_available' : 'seats';

    if (!$table || !$itemId || !$travelDate || !$passengers || $passengers < 1) {
        $message = 'Choose a service, travel date, and at least one passenger.';
        $messageType = 'error';
    } elseif ($travelDate < date('Y-m-d')) {
        $message = 'Travel date must be today or later.';
        $messageType = 'error';
    } else {
        $conn->begin_transaction();
        try {
            $lookup = $conn->prepare("SELECT `$priceColumn` AS price, `$availabilityColumn` AS available FROM `$table` WHERE `$idColumn` = ? FOR UPDATE");
            $lookup->bind_param('i', $itemId);
            $lookup->execute();
            $service = $lookup->get_result()->fetch_assoc();

            if (!$service) {
                throw new RuntimeException('The selected service is no longer available.');
            }
            if ((int) $service['available'] < $passengers) {
                throw new RuntimeException('There are not enough seats or rooms available.');
            }

            $total = (float) $service['price'] * $passengers;
            $userId = ctype_digit((string) $_SESSION['user_id']) ? (int) $_SESSION['user_id'] : 0;
            $bookingIdResult = $conn->query('SELECT COALESCE(MAX(booking_id), 0) + 1 AS next_id FROM bookings FOR UPDATE');
            $bookingId = (int) $bookingIdResult->fetch_assoc()['next_id'];

            $insert = $conn->prepare('INSERT INTO bookings (booking_id, user_id, booking_type, item_id, booking_date, travel_date, passengers, total_amount, status) VALUES (?, ?, ?, ?, CURDATE(), ?, ?, ?, ?)');
            $status = 'Pending';
            $insert->bind_param('iisisids', $bookingId, $userId, $selectedType, $itemId, $travelDate, $passengers, $total, $status);
            $insert->execute();

            $update = $conn->prepare("UPDATE `$table` SET `$availabilityColumn` = `$availabilityColumn` - ? WHERE `$idColumn` = ?");
            $update->bind_param('ii', $passengers, $itemId);
            $update->execute();
            $conn->commit();
            header("Location: payment.php?booking_id={$bookingId}");
            exit;
        } catch (Throwable $error) {
            $conn->rollback();
            $message = $error->getMessage();
            $messageType = 'error';
        }
    }
}

$options = getOptions($conn, $selectedType);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a trip | Razz Travels</title>
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
            --paper: #ffffff;
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
            width: min(1100px, 92%);
            margin: 0 auto;
            padding: 28px 0 12px;
            color: white;
            font-weight: 700;
        }

        header a {
            color: white;
            text-decoration: none;
            font-weight: 700;
        }

        main {
            width: min(1100px, 92%);
            margin: 18px auto 60px;
        }

        .intro {
            color: white;
            margin-bottom: 26px;
        }

        h1 {
            margin: 0 0 8px;
            font: 800 clamp(2rem, 5vw, 3.5rem) "Segoe UI", sans-serif;
            letter-spacing: -0.04em;
        }

        .intro p {
            margin: 0;
            opacity: 0.85;
            font-size: 1.02rem;
        }

        .panel {
            padding: clamp(24px, 5vw, 48px);
            border-radius: 24px;
            background: #fff;
            box-shadow: 0 28px 70px rgba(0,0,0,.22);
        }

        .tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 28px;
        }

        .tabs a {
            padding: 11px 18px;
            border-radius: 999px;
            color: var(--navy-700);
            background: #edf5fb;
            text-decoration: none;
            font-weight: 700;
            transition: background 160ms ease, color 160ms ease, transform 160ms ease;
        }

        .tabs a:hover {
            transform: translateY(-1px);
        }

        .tabs a.active {
            color: white;
            background: linear-gradient(135deg, var(--sky-500), var(--navy-700));
        }

        .form-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr auto;
            gap: 18px;
            align-items: end;
        }

        label {
            display: block;
            color: var(--ink);
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .05em;
            text-transform: uppercase;
        }

        select, input {
            width: 100%;
            margin-top: 8px;
            padding: 13px 14px;
            border: 1px solid var(--line);
            border-radius: 12px;
            color: var(--ink);
            background: #f8fafc;
            font: 1rem "Segoe UI", sans-serif;
        }

        select:focus, input:focus {
            outline: none;
            border-color: var(--sky-500);
            box-shadow: 0 0 0 4px rgba(45, 156, 219, 0.12);
        }

        button {
            width: 100%;
            padding: 14px 18px;
            border: 0;
            border-radius: 12px;
            color: white;
            background: linear-gradient(135deg, var(--orange-500), var(--orange-600));
            cursor: pointer;
            font-weight: 800;
            font-size: 1rem;
            box-shadow: 0 15px 28px rgba(245, 158, 11, 0.24);
        }

        .notice {
            margin-bottom: 20px;
            padding: 13px 15px;
            border-radius: 10px;
            font-weight: 700;
        }

        .success { color: #17603a; background: #e9f8ef; }
        .error { color: #a12620; background: #fff0ef; }

        .available {
            margin-top: 30px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: 14px;
        }

        .option {
            padding: 18px;
            border: 1px solid var(--line);
            border-radius: 14px;
            background: linear-gradient(180deg, #fbfdff, #f3f8fc);
        }

        .option strong {
            display: block;
            margin-bottom: 8px;
            color: var(--navy-800);
        }

        .price {
            color: var(--orange-600);
            font-weight: 700;
        }

        .empty {
            color: var(--muted);
        }

        @media (max-width: 720px) {
            header { align-items: flex-start; flex-direction: column; }
            .form-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <header><strong>RAZZ TRAVELS</strong><a href="dashboard.php">Back to dashboard</a></header>
    <main>
        <section class="intro"><h1>Book your next journey.</h1><p>Choose a service, select a date, and confirm your seats or room.</p></section>
        <section class="panel">
            <?php if ($message !== ''): ?><div class="notice <?= htmlspecialchars($messageType) ?>"><?= htmlspecialchars($message) ?></div><?php endif; ?>
            <nav class="tabs" aria-label="Booking type">
                <?php foreach (['flight' => 'Flights', 'train' => 'Trains', 'hotel' => 'Hotels'] as $type => $label): ?><a class="<?= $selectedType === $type ? 'active' : '' ?>" href="trip.php?type=<?= $type ?>"><?= $label ?></a><?php endforeach; ?>
            </nav>
            <form method="post">
                <input type="hidden" name="booking_type" value="<?= htmlspecialchars($selectedType) ?>">
                <div class="form-grid">
                    <label for="item_id">Choose <?= htmlspecialchars($selectedType) ?><select id="item_id" name="item_id" required><option value="">Select an option</option><?php foreach ($options as $option): ?><option value="<?= (int) $option['item_id'] ?>"><?= htmlspecialchars($option['label']) ?> - ₹<?= number_format((float) $option['price'], 2) ?></option><?php endforeach; ?></select></label>
                    <label for="travel_date">Travel date<input id="travel_date" type="date" name="travel_date" min="<?= date('Y-m-d') ?>" required></label>
                    <label for="passengers"><?= $selectedType === 'hotel' ? 'Rooms' : 'Passengers' ?><input id="passengers" type="number" name="passengers" min="1" max="10" value="1" required></label>
                    <button type="submit" name="book_now">Confirm booking</button>
                </div>
            </form>
            <div class="available"><?php if (!$options): ?><p class="empty">No options are available right now.</p><?php else: foreach ($options as $option): ?><article class="option"><strong><?= htmlspecialchars($option['label']) ?></strong><span class="price">₹<?= number_format((float) $option['price'], 2) ?> per <?= $selectedType === 'hotel' ? 'room/night' : 'person' ?></span><br><small><?= (int) $option['seats'] ?> available</small></article><?php endforeach; endif; ?></div>
        </section>
    </main>
</body>
</html>