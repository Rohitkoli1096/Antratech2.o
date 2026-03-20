<?php
include "config.php";

$result = $conn->query("SELECT name, branch, xp FROM users ORDER BY xp DESC LIMIT 10");

$users = [];

while($row = $result->fetch_assoc()){
    $users[] = $row;
}

echo json_encode($users);
?>