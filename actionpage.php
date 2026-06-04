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
    $conn->exec("CREATE DATABASE IF NOT EXISTS mining");

    // Connect to mining database
    $conn = new PDO("mysql:host=$servername;dbname=mining", $username, $password);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create guestbook table if not exists
    $tableSQL = "
        CREATE TABLE IF NOT EXISTS guestbook (
            id INT AUTO_INCREMENT PRIMARY KEY,
            fname VARCHAR(50) NOT NULL,
            familyname VARCHAR(50) NOT NULL,
            countrycity VARCHAR(100) NOT NULL,
            comment VARCHAR(255) NOT NULL
        )
    ";

    $conn->exec($tableSQL);

    // Get values from form
    $fname = $_POST["fname"];
    $familyname = $_POST["familyname"];
    $countrycity = $_POST["countrycity"];
    $comment = $_POST["comment"];

    // Insert values into table
    $sql = "INSERT INTO guestbook (fname, familyname, countrycity, comment)
            VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    $stmt->execute([$fname, $familyname, $countrycity, $comment]);

    echo "<h2>Guestbook Entry Submitted Successfully</h2>";

    // Display all records
    $sql = "SELECT * FROM guestbook";

    $stmt = $conn->prepare($sql);

    $stmt->execute();

    echo "<table border='1' cellpadding='10'>";
    echo "<tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Family Name</th>
            <th>Country/City</th>
            <th>Comment</th>
          </tr>";

    while($row = $stmt->fetch()) {

        echo "<tr>
                <td>" . $row["id"] . "</td>
                <td>" . $row["fname"] . "</td>
                <td>" . $row["familyname"] . "</td>
                <td>" . $row["countrycity"] . "</td>
                <td>" . $row["comment"] . "</td>
              </tr>";
    }

    echo "</table>";

}
catch(PDOException $e) {

    echo "Error: " . $e->getMessage();

}

$conn = null;
?>