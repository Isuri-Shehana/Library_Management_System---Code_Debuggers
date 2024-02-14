<?php
require_once "../config.php";

if(isset($_POST['add_member'])){
    $member_id = $_POST['member_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $birthday = $_POST['birthday'];
    $email = $_POST['email'];

    $regex = '/^M\d{3}$/';

    if (empty($member_id)) {
        $em = "Member ID is required";
        header("Location: ../member.php?error=$em");
        exit;
    } else if (!preg_match($regex, $member_id)) {
        $em = "Invalid member_id format. Please use M001, M002 Format";
        header("Location: ../member.php?error=$em");
        exit;
    } else if (empty($first_name)) {
        $em = "First name is required";
        header("Location: ../member.php?error=$em");
        exit;
    } else if (empty($last_name)) {
        $em = "Last name is required";
        header("Location: ../member.php?error=$em");
        exit;
    } else if (empty($birthday)) {
        $em = "Birthday is required";
        header("Location: ../member.php?error=$em");
        exit;
    } else if (empty($email)) {
        $em = "Email is required";
        header("Location: ../member.php?error=$em");
        exit;
    } else {
        $sql = "INSERT INTO member(member_id, first_name, last_name, birthday, email) 
            VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $member_id, $first_name, $last_name, $birthday, $email);
        $stmt->execute();
        
        header("Location: ../member.php?success=Member has been registered successfully");
        exit;
    }
} else {
    header("Location: ../member.php");
    exit;
}

$conn->close();
?>
