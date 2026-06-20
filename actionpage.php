<!DOCTYPE html>
<html lang="en-AU">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background-color: white;
        }

        table {
            width: 100%;
        }
    </style>
</head>

<body>

    <?php
    // Database connection settings
    $servername = "localhost";
    $username = "root";
    $password = "usbw";

    try {

        // Connect to MySQL
        $conn = new PDO("mysql:host=$servername", $username, $password);

        // Set PDO error mode
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create database if not exists
        $conn->exec("CREATE DATABASE IF NOT EXISTS hotel");
        // Connect to mining database
        $conn = new PDO("mysql:host=$servername;dbname=hotel", $username, $password);

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create guestbook table if not exists
        $tableSQL = "CREATE TABLE IF NOT EXISTS guestbook (
        id INT AUTO_INCREMENT PRIMARY KEY,
        fname VARCHAR(50) NOT NULL,
        familyname VARCHAR(50) NOT NULL,
        countrycity VARCHAR(100) NOT NULL,
        comment VARCHAR(255) NOT NULL
        )";
        $conn->exec($tableSQL);


        // create query for inserting into table
        $sql = "INSERT INTO guestbook (fname, familyname, countrycity, comment)
            VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        // INSERT intitial values into guestbook table as required by assessment
        $populateSQL = "SELECT 1 FROM guestbook LIMIT 1";
        $CHECK = $conn->prepare($populateSQL);
        $CHECK->execute();
        if (!$CHECK->fetch()) {
            $stmt->execute(["John", "Williams", "USA, Ulysses, New York", "Great food"]);
            $stmt->execute(["Amanda", "Suarez", "Spain, Oveido, Asturias", "Relaxing atmosphere"]);
        }


        if (isset($_POST["fname"], $_POST["familyname"], $_POST["countrycity"], $_POST["comment"])) {
            // Get values from form
            $fname = $_POST["fname"];
            $familyname = $_POST["familyname"];
            $countrycity = $_POST["countrycity"];
            $comment = $_POST["comment"];
            $stmt->execute([$fname, $familyname, $countrycity, $comment]);
        }
        // Display all records
        $sql = "SELECT * FROM (SELECT * FROM guestbook ORDER BY id DESC LIMIT 10) as last ORDER BY id ASC";

        $stmt = $conn->prepare($sql);

        $stmt->execute();

        echo "<table class='striped'>";
        echo "<tr>
                  <th>ID          </th>
                  <th>First Name  </th>
                  <th>Family Name </th>
                  <th>Country/City</th>
                  <th>Comment     </th>
              </tr>";

        while ($row = $stmt->fetch()) {

            echo "<tr class='data'>
                      <td>" . $row["id"] .          "</td>
                      <td>" . $row["fname"] .       "</td>
                      <td>" . $row["familyname"] .  "</td>
                      <td>" . $row["countrycity"] . "</td>
                      <td>" . $row["comment"] .     "</td>
                  </tr>";
        }

        echo "</table>";
    } catch (PDOException $e) {

        echo "Error: " . $e->getMessage();
    }
    $conn = null;
    ?>
</body>

</html>