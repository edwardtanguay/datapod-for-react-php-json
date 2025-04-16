<?php

/**
 * Utility methods for development
 */
class qdev {
    /**
     * Prints a debug line with timestamp
     *
     * Example output:
     * 2025-04-14 18:18:06 ### was here ##############
     */
    public static function debug($obj): void {
        $timestamp = date('Y-m-d H:i:s');

        if (is_string($obj)) {
            echo "🛠️  " . $timestamp . " - " . $obj . PHP_EOL;
        } else {
            var_dump($obj);
        }
    }
} 