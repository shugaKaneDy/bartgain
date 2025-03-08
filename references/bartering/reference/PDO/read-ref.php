<?php
require 'db.php';

$sql = "SELECT * FROM users";
$stmt = $pdo->query($sql);
$users = $stmt->fetchAll();

foreach ($users as $user) {
    echo $user['name'] . " - " . $user['email'] . "<br>";
}
?>


<?php
$id = $_GET['id'] ?? null;
if ($id) {
    $sql = "SELECT * FROM users WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    $user = $stmt->fetch();
}
?>