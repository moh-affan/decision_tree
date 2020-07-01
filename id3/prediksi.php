<?php
include 'predict.php';

// print_r($_POST);

$prediksi = predict($_POST, $tree);
print_r($prediksi);

echo "Hasil prediksi berdasarkan gejala yang anda input adalah <h1>" . $prediksi . "</h1>";
