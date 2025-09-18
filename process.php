<?php
header('Content-Type: application/json; charset=utf-8');

$errors = [];
$result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $age = isset($_POST['age']) ? (int)$_POST['age'] : 0;
    $hobby = isset($_POST['hobby']) ? $_POST['hobby'] : [];
    $country = isset($_POST['country']) ? trim($_POST['country']) : '';

    // validate
    if ($username === '') $errors[] = "Username không được để trống.";
    if (strlen($password) < 6) $errors[] = "Password phải có ít nhất 6 kí tự.";
    if ($age < 0 || $age > 100) $errors[] = "Age phải trong khoảng 0-100.";
    if (!in_array($country, ['Viet Nam', 'Hoa Ky', 'Trung Quoc'])) $errors[] = "Country không hợp lệ.";

    if (empty($errors)) {
        $result = [
            'username' => htmlspecialchars($username, ENT_QUOTES, 'UTF-8'),
            'password_mask' => str_repeat('*', min(20, strlen($password))),
            'age' => $age,
            'hobby' => array_map(function($h){ return htmlspecialchars($h, ENT_QUOTES, 'UTF-8'); }, (array)$hobby),
            'country' => htmlspecialchars($country, ENT_QUOTES, 'UTF-8'),
        ];
        echo json_encode($result);
        exit;
    } else {
        echo json_encode(['errors' => $errors]);
        exit;
    }
}
echo json_encode(['errors' => ['Yêu cầu không hợp lệ.']]);
