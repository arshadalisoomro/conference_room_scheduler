<?php

class Register {
    public $noEmail;
    public $incorrectEmail;
    public $noPassword;
    public $registeredEmail;
    public $noConfirmPassword;
    public $noPasswordMatch;
    public $noAccessCode;
    public $registrationSuccess;
    public $badPassword;
    public $registrationFailure;

    function _construct() {
        $this->noEmail =
            $this->incorrectEmail =
            $this->noPassword =
            $this->registeredEmail =
            $this->noConfirmPassword =
            $this->noPasswordMatch =
            $this->noAccessCode =
            $this->registrationSuccess =
                "";
    }

    function checkEmailExists($email, $db) {
        $query = "
                SELECT *
                FROM user
                WHERE
                    email = :email
            ";

        $query_params = array(
            ':email' => $email
        );

        try {
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
        } catch(PDOException $ex) {
            die("Failed to run query: " . $ex->getMessage());
        }

        if($stmt->rowCount() > 0){
            $this->registeredEmail = "This email address is already registered.";
        }
    }

    function checkNoFormErrors($post, $db) {
        $this->emailError($post['email']);
        $this->passwordError($post['password'], $post['confirmPassword']);

        $accessCode = "10101";
        $this->accessCodeError($post['access_code'], $accessCode);

        return empty($this->noEmail) && empty($this->incorrectEmail) && empty($this->noPassword) &&
                empty($this->noConfirmPassword) && empty($this->noPasswordMatch) &&
                empty($this->noAccessCode) && empty($this->badPassword);
    }

    function emailError($email) {
        if (empty($email)) {
            $this->noEmail = "Please enter an email address.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->incorrectEmail = "Invalid E-Mail Address.";
        }
    }

    function passwordError($password, $confirm) {
        $this->badPassword = PasswordUtils::testPassword($password);

        if (empty($password)) {
            $this->noPassword = "Please enter a password.";
        }
        if (empty($confirm)) {
            $this->noConfirmPassword = "Please confirm your password.";
        }
        if ($password != $confirm && empty($this->noPassword) && empty($this->noConfirmPassword) && empty($this->badPassword)) {
            $this->noPasswordMatch = "Passwords do not match.";
        }
    }

    function accessCodeError($userAccessCode, $dbAccessCode) {
        if (empty($userAccessCode)) {
            $this->noAccessCode = "Enter an access code.";
        }
        if ($dbAccessCode != $userAccessCode) {
            $this->noAccessCode = "Invalid access code";
        }
    }

    function saveRegistration($post, $hash, $db) {
        // Store the results into the users table.
        $query = "
                    INSERT INTO user (
                        email,
                        password,
                        password_salt,
                        first_name,
                        last_name,
                        user_type_id,
                        picture_url
                    ) VALUES (
                        :email,
                        :password,
                        :salt,
                        :first_name,
                        :last_name,
                        :user_type_id,
                        :picture_url
                    )";

        // Security measures
        $salt = PasswordUtils::generatePasswordSalt();
        $password = PasswordUtils::hashPassword($post['password'], $salt);

        $query_params = array(
            ':email' => $post['email'],
            ':password' => $password,
            ':salt' => $salt,
            ':first_name' => $post['first_name'],
            ':last_name' => $post['last_name'],
            ':user_type_id' => '1',
            ':picture_url' => 'http://walphotobucket.s3.amazonaws.com/default.jpg'
        );

        try {
            $stmt = $db->prepare($query);
            $stmt->execute($query_params);
        } catch(PDOException $ex) {
            die("Failed to run query: " . $ex->getMessage());
        }
    }
}
