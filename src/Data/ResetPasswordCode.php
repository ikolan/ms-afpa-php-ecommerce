<?php

namespace App\Data;

class ResetPasswordCode
{
    private string $ALPHABET = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    private string $code = "";

    public function __construct()
    {
        for ($i = 0; $i < 10; $i++) {
            $this->code .= $this->ALPHABET[rand(0, strlen($this->ALPHABET) - 1)];
        }
    }

    public function __toString()
    {
        return $this->code;
    }
}