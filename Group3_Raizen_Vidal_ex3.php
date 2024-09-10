<?php
$filename = 'attendance.txt';

$members = [
    'Raizen Vidal',
    'Joerieleto Locrita jr.',
    'Paul Norbert Pasia',
    'Jeryll Miguel Sepina',
    'Kaycelyn Fesarit'
];

$attendance = [];

if (file_exists($filename)) {
    $data = file_get_contents($filename);
    $attendance = unserialize($data) ?: [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'] ?? date('Y-m-d');

    if (!isset($attendance[$date])) {
        $attendance[$date] = array_fill(0, count($members), false);
    }

    foreach ($members as $index => $member) {
        $attendance[$date][$index] = isset($_POST["attendance_$index"]);
    }

    file_put_contents($filename, serialize($attendance));

    header("Location: " . $_SERVER['PHP_SELF'] . "?date=" . urlencode($date));
    exit;
}

$date = $_GET['date'] ?? date('Y-m-d');

if (!isset($attendance[$date])) {
    $attendance[$date] = array_fill(0, count($members), false);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        input[type="checkbox"] {
            margin-right: 10px;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Group Attendance</h1>

        <form method="get" action="">
            <label for="date">Select Date:</label>
            <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($date); ?>">
            <button type="submit">Select Date</button>
        </form>

        <form method="post" action="">
            <input type="hidden" name="date" value="<?php echo htmlspecialchars($date); ?>">
            <?php foreach ($members as $index => $member): ?>
                <label>
                    <input type="checkbox" name="attendance_<?php echo $index; ?>" <?php echo $attendance[$date][$index] ? 'checked' : ''; ?>>
                    <?php echo htmlspecialchars($member); ?>
                </label><br>
            <?php endforeach; ?>
            <button type="submit">Save Attendance</button>
        </form>

        <h2>Attendance Record for <?php echo htmlspecialchars($date); ?></h2>
        <table>
            <thead>
                <tr>
                    <th>Member</th>
                    <th>Present</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($members as $index => $member): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($member); ?></td>
                        <td><?php echo $attendance[$date][$index] ? 'Present' : 'Absent'; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
