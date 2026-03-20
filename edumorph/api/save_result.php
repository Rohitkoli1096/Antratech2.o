<?php
include "config.php";

$data = json_decode(file_get_contents("php://input"));

$user_id = $_SESSION['user_id'];
$branch = $data->branch;
$score = $data->score;
$percentage = $data->percentage;

$conn->query("INSERT INTO quiz_results (user_id, branch, score, percentage)
VALUES ('$user_id','$branch','$score','$percentage')");

$xpEarned = $percentage;
$conn->query("UPDATE users SET xp = xp + $xpEarned WHERE id = $user_id");

echo json_encode(["status"=>"saved"]);
?>