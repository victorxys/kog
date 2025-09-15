<span style="font-size:14px;"><?php
$servername = "127.0.0.1";
$port=3306;
$username = "root";
$password = "xys131313";
$dbname = "kog";
$socket="mysqli.default_socket";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname,$port,$socket);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT id, firstname, lastname FROM MyGuests";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
    }
} else {
    echo "0 results";
}
$conn->close();
?>
