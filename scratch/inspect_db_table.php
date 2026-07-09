<?php

$pdo = new PDO('mysql:host=127.0.0.1;dbname=avijatri_pos', 'root', '');
$stmt = $pdo->query('DESCRIBE shoes');
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
