<?php

class OTP {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function generate($userId) {

        $otp = random_int(100000, 999999);
        $expiry = date("Y-m-d H:i:s", strtotime("+5 minutes"));

        $stmt = $this->pdo->prepare(
            "INSERT INTO otps (user_id, otp_code, expires_at) VALUES (?, ?, ?)"
        );

        $stmt->execute([$userId, $otp, $expiry]);

        return $otp;
    }

    public function verify($userId, $inputOtp) {

        $stmt = $this->pdo->prepare(
            "SELECT * FROM otps 
             WHERE user_id = ? 
             AND otp_code = ? 
             AND expires_at >= NOW()
             ORDER BY id DESC LIMIT 1"
        );

        $stmt->execute([$userId, $inputOtp]);
        return $stmt->fetch();
    }
}
