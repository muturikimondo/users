<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';

// Handle GET (fetch user data for modal)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $sql = "SELECT * FROM users WHERE id=$id LIMIT 1";
    $res = $conn->query($sql);
    if ($res && $res->num_rows > 0) {
        $user = $res->fetch_assoc();
        $user['photo_url'] = !empty($user['photo'])
            ? BASE_URL . 'core/uploads/photos/' . $user['photo']
            : BASE_URL . 'core/uploads/photos/default.png';
        echo json_encode($user);
    }
    exit;
}

// Handle POST (update user)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) $_POST['id'];
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $role_id = (int) $_POST['role_id'];
    $department_id = (int) $_POST['department_id'];
    $section_id = (int) $_POST['section_id'];
    $status = (int) $_POST['status'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    // Handle photo upload
    if (!empty($_FILES['photo']['name'])) {
        $fileName = time() . '_' . basename($_FILES['photo']['name']);
        $target = __DIR__ . '/../../core/uploads/photos/' . $fileName;
        move_uploaded_file($_FILES['photo']['tmp_name'], $target);
        $photoSql = ", photo='$fileName'";
    } else {
        $photoSql = "";
    }

    $passwordSql = $password ? ", password='$password'" : "";

    $sql = "UPDATE users SET 
                username='$username',
                email='$email',
                role_id=$role_id,
                department_id=$department_id,
                section_id=$section_id,
                status=$status
                $photoSql
                $passwordSql
            WHERE id=$id";
    $conn->query($sql);
    echo json_encode(['success' => true]);
}
