<?php include 'db.php'; ?>

<?php
$msg = "";

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];

    $query = "INSERT INTO leads (name, email, phone, message) 
              VALUES ('$name', '$email', '$phone', '$message')";

    if(mysqli_query($conn, $query)){
        $msg = "Saved ✅";
    }
}
?>

<form method="POST">
  <input type="text" name="name" placeholder="Name" required><br>
  <input type="email" name="email" placeholder="Email" required><br>
  <input type="text" name="phone" placeholder="Phone" required><br>
  <textarea name="message" placeholder="Message" required></textarea><br>
  <button type="submit" name="submit">Submit</button>
</form>

<h3>Data:</h3>

<?php
$result = mysqli_query($conn, "SELECT * FROM leads ORDER BY id DESC");

while($row = mysqli_fetch_assoc($result)){
  echo $row['name'] . " - " . $row['email'] . "<br>";
}
?>