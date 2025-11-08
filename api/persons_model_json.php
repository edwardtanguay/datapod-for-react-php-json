<?php
require_once '../scripts/qtools/qfil.php';
require_once '../scripts/qtools/qstr.php';

class persons_model
{
	private static $dataSourcePathAndFileName = "../parseddata/persons.json";

	public static function get_all_persons(): array
	{
		$persons = qfil::readJsonFile(self::$dataSourcePathAndFileName, true);
		return $persons;
	}

	public static function get_person_by_id(string $id): ?object
	{
		$persons = self::get_all_persons();
		foreach ($persons as $person) {
			if (isset($person['id']) && $person['id'] === $id) {
				return (object) $person;
			}
		}
		return null;
	}

	public static function create_person(object $personWithoutId): ?object
	{
		try {
			$persons = self::get_all_persons();
			$person = $personWithoutId;
			$person->id = qstr::generateSuuid();
			$persons[] = $person;

			if (!method_exists('qfil', 'saveToJsonFile')) {
				throw new Exception("Method saveToJsonFile2 does not exist in qfil class");
			}

			qfil::saveToJsonFile($persons, self::$dataSourcePathAndFileName);

			return $person;

		} catch (Exception $e) {
			error_log("Error creating person: " . $e->getMessage());
			return null;
		}
	}

	public static function delete_person_by_id(string $id): bool
	{
		try {
			$persons = self::get_all_persons();
			$initialCount = count($persons);

			$persons = array_filter($persons, function ($person) use ($id) {
				return !isset($person['id']) || $person['id'] !== $id;
			});

			if (count($persons) === $initialCount) {
				return false;
			}

			qfil::saveToJsonFile($persons, self::$dataSourcePathAndFileName);

			return true;

		} catch (Exception $e) {
			error_log("Error deleting person: " . $e->getMessage());
			return false;
		}
	}

	public static function update_person_by_id(object $personToUpdate): ?object
	{
		try {
			if (!isset($personToUpdate->id)) {
				return null;
			}

			$persons = self::get_all_persons();
			$personUpdated = false;

			foreach ($persons as &$person) {
				if (isset($person['id']) && $person['id'] === $personToUpdate->id) {
					$person = (array) $personToUpdate;
					$personUpdated = true;
					break;
				}
			}

			if (!$personUpdated) {
				return null;
			}

			qfil::saveToJsonFile($persons, self::$dataSourcePathAndFileName);

			return $personToUpdate;

		} catch (Exception $e) {
			error_log("Error updating person: " . $e->getMessage());
			return null;
		}
	}
}