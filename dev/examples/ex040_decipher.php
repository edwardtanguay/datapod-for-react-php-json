<?php
function decipherData($message) {
	$result = '';
	$offset = 2;

	for ($i = 0; $i < strlen($message); $i++) {

		$char = $message[$i];
		$charNum = ord($char);

		if(preg_match('/[a-z]/', $char)) {
			$newCharNum = (($charNum - 97 - $offset + 26) % 26) + 97;
			$newChar = chr($newCharNum);
		} elseif(preg_match('/[A-Z]/', $char)) {
			$newCharNum = (($charNum - 65 - $offset + 26) % 26) + 65;
			$newChar = chr($newCharNum);
		} else {
			$newChar = $char;
		}

		$result .= $newChar;
	}

	return $result;
}

$test1 = decipherData("Bqq");
$test2 = decipherData("Ecv");

echo $test1 . "\n"; 
echo $test2 . "\n";