<?php
require_once 'qcli.php';

/**
 * File utility methods
 */
class qfil
{
	/**
	 * Reads a file and returns its lines as an array of strings.
	 * 
	 * Example:
	 * $lines = qfil::getLinesFromFile('../dataraw/flashcards.txt');
	 * 
	 * @param string $filePath Path to the file to read
	 * @return array<string> Array of lines from the file, or empty array if file cannot be read
	 */
	public static function getLinesFromFile(string $filePath): array
	{
		try {
			$fileContent = file_get_contents($filePath);
			if ($fileContent === false) {
				qcli::message("Error reading file at {$filePath}", "error");
				return [];
			}
			return explode(PHP_EOL, $fileContent);
		} catch (Exception $error) {
			qcli::message("Error reading file at {$filePath}: " . $error->getMessage(), "error");
			return [];
		}
	}

	/**
	 * Enhanced version that can return array or object
	 */
	public static function readJsonFile(string $filename, bool $associative = false)
	{
		if (!file_exists($filename)) {
			throw new Exception("File '$filename' does not exist");
		}

		$jsonContent = file_get_contents($filename);
		if ($jsonContent === false) {
			throw new Exception("Failed to read file '$filename'");
		}

		if (empty(trim($jsonContent))) {
			return $associative ? [] : new stdClass();
		}

		$data = json_decode($jsonContent, $associative);

		if (json_last_error() !== JSON_ERROR_NONE) {
			throw new Exception("JSON decode error: " . json_last_error_msg());
		}

		return $data;
	}

	/**
	 * Enhanced version that accepts both arrays and objects
	 */
	public static function saveToJsonFile($data, string $filename, bool $prettyPrint = true): bool
	{
		$options = $prettyPrint ? JSON_PRETTY_PRINT : 0;
		$jsonContent = json_encode($data, $options);

		if (json_last_error() !== JSON_ERROR_NONE) {
			throw new Exception("JSON encode error: " . json_last_error_msg());
		}

		// Convert groups of 4 leading spaces into tabs for pretty output
		if ($prettyPrint) {
			$jsonContent = preg_replace_callback('/^( +)/m', function ($m) {
				$spaces = strlen($m[1]);
				$tabs = intdiv($spaces, 4);
				$rest = $spaces % 4;
				return str_repeat("\t", $tabs) . str_repeat(' ', $rest);
			}, $jsonContent);
		}

		$directory = dirname($filename);
		if (!is_dir($directory) && !mkdir($directory, 0755, true)) {
			throw new Exception("Failed to create directory '$directory'");
		}

		$result = file_put_contents($filename, $jsonContent);
		return $result !== false;
	}

}