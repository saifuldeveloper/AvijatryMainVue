<?php

$pdo = new PDO('mysql:host=127.0.0.1;dbname=avijatri_pos', 'root', '');
$stmt = $pdo->query('SELECT id, code, image FROM shoes WHERE id BETWEEN 90 AND 110 OR id BETWEEN 990 AND 1010');
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
