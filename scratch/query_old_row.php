<?php

$pdo = new PDO('mysql:host=127.0.0.1;dbname=avijatri_pos', 'root', '');
$stmt = $pdo->prepare('SELECT * FROM shoes WHERE image = ?');
$stmt->execute(['100a_6823a0f8eefb7.jpg']);
print_r($stmt->fetch(PDO::FETCH_ASSOC));
