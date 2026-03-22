<?php include 'db.php'; ?>

<?php
// Auto-migration: Create columns if they don't exist
$migrations = [
    'rm' => "ALTER TABLE leads ADD COLUMN rm VARCHAR(255) AFTER message",
    'project' => "ALTER TABLE leads ADD COLUMN project VARCHAR(255) AFTER rm"
];

foreach ($migrations as $column => $sql) {
    $check = mysqli_query($conn, "SHOW COLUMNS FROM leads LIKE '$column'");
    if (mysqli_num_rows($check) == 0) {
        mysqli_query($conn, $sql);
    }
}

$msg = "";

if(isset($_POST['submit'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $rm = mysqli_real_escape_string($conn, $_POST['rm']);
    $project = mysqli_real_escape_string($conn, $_POST['project']);

    $query = "INSERT INTO leads (name, email, phone, message, rm, project) 
              VALUES ('$name', '$email', '$phone', '$message', '$rm', '$project')";

    if(mysqli_query($conn, $query)){
        $msg = "Saved ✅";
    }
}
?>

<form method="POST">
  <input type="text" name="name" placeholder="Name" required><br>
  <input type="email" name="email" placeholder="Email" required><br>
  <input type="text" name="phone" placeholder="Phone" required><br>
  <input type="text" name="project" placeholder="Project Name" required><br>
  <input type="text" name="rm" placeholder="Relationship Manager (RM)" required><br>
  <textarea name="message" placeholder="Message" required></textarea><br>
  <button type="submit" name="submit">Submit</button>
</form>

<?php if($msg) echo "<p>$msg</p>"; ?>

<h3>Data:</h3>

<?php
$result = mysqli_query($conn, "SELECT * FROM leads ORDER BY id DESC");

while($row = mysqli_fetch_assoc($result)){
  echo "<b>" . $row['name'] . "</b> (" . $row['project'] . ") - " . $row['email'] . " | <b>RM:</b> " . $row['rm'] . "<br>";
}
?>

<?php echo "Version 5 🚀"; ?>


<?php

echo "Version 3 🚀";

?>