<?php

class ChangePassword {
    
    public $errorMessage;
    public $success;

    function checkFieldsCorrect($post) {
        if (empty($post['current_password']) || empty($post['new_password']) || empty($post['confirm_password'])) {
            $this->errorMessage = "Please fill all fields.";
            return false;
        } elseif (!PasswordUtils::checkMatchingPasswords($post['new_password'], $post['confirm_password'])) {
            $this->errorMessage = "Passwords don't match.";
            return false;
        } else {
            return true;
        }
    }

    function makePasswordChange($db, $newPassword, $salt, $id) {
        $query = "
            UPDATE users
            SET
                password = :password
            WHERE
                id = :id
        ";

        $query_params = array(
            ':password' => PasswordUtils::hashPassword($newPassword, $salt),
            ':id' => $id
        );

        try {
            $stmt = $db->prepare($query);
            $stmt->execute($query_params);
        } catch(PDOException $ex) {
            die("Failed to run query: " . $ex->getMessage());
        }
    }
}