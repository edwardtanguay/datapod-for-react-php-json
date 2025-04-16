<?php

/**
 * String utility methods
 */
class qstr {
    /**
     * Generates a 6-character unique identifier (suuid) consisting of random
     * upper and lower case letters and numbers.
     * Example: "nO57aL"
     * 
     * @return string The generated suuid
     */
    public static function generateSuuid(): string {
        $characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        $suuid = "";
        for ($i = 0; $i < 6; $i++) {
            $randomIndex = rand(0, strlen($characters) - 1);
            $suuid .= $characters[$randomIndex];
        }
        return $suuid;
    }
} 