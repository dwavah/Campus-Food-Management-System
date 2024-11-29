''
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "campus_food";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if data is received via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uid = $_POST['uid'];
    $name = $_POST['name'];

    // Insert data into the database
    $sql = "INSERT INTO users (uid, name) VALUES ('$uid', '$name')";

    if ($conn->query($sql) === TRUE) {
        echo "Data inserted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "No data received";
}

$conn->close();
?>