<?php

/**
 * Quick tools for CLI commands
 */
class qcli {
	/**
	 * Prints a CLI message with emoji indicators
	 * 
	 * @param string $line The message to display
	 * @param string $kind The type of message (info, error, success, warning, doing)
	 */
	public static function message(string $line, string $kind = "success"): void {
		switch ($kind) {
			case "info":
				echo "ℹ️  " . $line . PHP_EOL;
				break;
			case "error":
				echo "❌ " . $line . PHP_EOL;
				break;
			case "warning":
				echo "⚠️  " . $line . PHP_EOL;
				break;
			case "doing":
				echo "⏳ " . $line . PHP_EOL;
				break;
			case "data":
				echo "🟠 " . $line . PHP_EOL;
				break;
			default:
				echo "✅ " . $line . PHP_EOL;
				break;
		}
	}
}
