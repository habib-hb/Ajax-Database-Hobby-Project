<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    // Database credentials
    $servername = "localhost"; // or your database server
    $username = "weaponary";
    $password = "weaponary";
    $dbname = "weaponary";

    try {
        // Create a new PDO instance
        $dsn = "mysql:host=$servername;dbname=$dbname";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $pdo = new PDO($dsn, $username, $password, $options);

        // Get the submitted name
        $name = $_POST["name"];

        // Prepare an insert statement
        $stmt = $pdo->prepare("INSERT INTO name (name) VALUES (:name)");

        // Bind the parameter and execute the statement
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $id = $pdo->lastInsertId();
            header('Content-Type: application/json');
            echo json_encode(['id' => $id, 'name' => $name]);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Error: Could not execute the query']);
        }

    } catch (PDOException $e) {
        header('Content-Type: application/json');
        echo json_encode(['error' => "Error: " . $e->getMessage()]);
    }

    // Close the connection
    $pdo = null;
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Name</title>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            flex-direction: column;
            animation: fadeIn 1s ease-in-out;
            padding-top: 24px;
        }
        .container {
            width: 90%;
            max-width: 600px;
        }
        .form-container, .table-container {
            background-color: #1e1e1e;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            text-align: center;
            margin-bottom: 20px;
            animation: fadeIn 2s ease-in-out;
        }
        h1 {
            color: #ffffff;
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-size: 18px;
            color: #bbbbbb;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #333333;
            border-radius: 5px;
            font-size: 16px;
            background-color: #262626;
            color: #ffffff;
        }
        button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #45a049;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #333333;
            text-align: center;
        }
        th {
            background-color: #2a2a2a;
        }
        td {
            background-color: #1e1e1e;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h1>Enter a Name</h1>
            <form id="nameForm">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
                <button type="submit">Submit</button>
            </form>
        </div>
        <div class="table-container">
            <h1>Names List</h1>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                    </tr>
                </thead>
                <tbody id="namesList">
                    <?php
                    // Database credentials
                    $servername = "localhost"; // or your database server
                    $username = "weaponary";
                    $password = "weaponary";
                    $dbname = "weaponary";

                    try {
                        // Create a new PDO instance
                        $dsn = "mysql:host=$servername;dbname=$dbname";
                        $options = [
                            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                            PDO::ATTR_EMULATE_PREPARES   => false,
                        ];
                        $pdo = new PDO($dsn, $username, $password, $options);

                        // Fetch all records
                        $stmt = $pdo->query("SELECT id, name FROM name");
                        while ($row = $stmt->fetch()) {
                            echo "<tr><td>{$row['id']}</td><td>{$row['name']}</td></tr>";
                        }

                    } catch (PDOException $e) {
                        echo "Error: " . $e->getMessage();
                    }

                    // Close the connection
                    $pdo = null;
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.getElementById('nameForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            const nameInput = document.getElementById('name').value;

            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams({
                    'name': nameInput
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error('Error:', data.error);
                } else {
                    // Update the names list
                    document.getElementById('namesList').innerHTML += `<tr><td>${data.id}</td><td>${data.name}</td></tr>`;
                    document.getElementById('name').value = ''; // Clear the input field
                }
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
</body>
</html>