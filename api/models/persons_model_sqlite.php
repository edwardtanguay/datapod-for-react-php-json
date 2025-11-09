<?php
require_once __DIR__ . '/../../scripts/qtools/qstr.php';

class persons_model
{
	private static $dbPath = "../data/main.sqlite";
	private static $tableName = "persons";

	private static function getConnection(): PDO
	{
		try {
			$db = new PDO("sqlite:" . self::$dbPath);
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $db;
		} catch (PDOException $e) {
			error_log("Database connection error: " . $e->getMessage());
			throw $e;
		}
	}

	private static function ensureTableExists(): void
	{
		try {
			$db = self::getConnection();
			$sql = "CREATE TABLE IF NOT EXISTS " . self::$tableName . " (
                id TEXT PRIMARY KEY,
                firstName TEXT NOT NULL,
                lastName TEXT NOT NULL,
                age INTEGER
            )";
			$db->exec($sql);
		} catch (PDOException $e) {
			error_log("Error ensuring table exists: " . $e->getMessage());
			throw $e;
		}
	}

	public static function get_all_persons(): array
	{
		try {
			self::ensureTableExists();
			$db = self::getConnection();
			$stmt = $db->prepare("SELECT id, firstName, lastName, age FROM " . self::$tableName);
			$stmt->execute();

			$persons = [];
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$persons[] = [
					'id' => $row['id'],
					'firstName' => $row['firstName'],
					'lastName' => $row['lastName'],
					'age' => $row['age']
				];
			}

			return $persons;
		} catch (PDOException $e) {
			error_log("Error getting all persons: " . $e->getMessage());
			return [];
		}
	}

	public static function get_person_by_id(string $id): ?object
	{
		try {
			self::ensureTableExists();
			$db = self::getConnection();
			$stmt = $db->prepare("SELECT id, firstName, lastName, age FROM " . self::$tableName . " WHERE id = :id");
			$stmt->bindParam(':id', $id);
			$stmt->execute();

			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($row) {
				return (object) [
					'id' => $row['id'],
					'firstName' => $row['firstName'],
					'lastName' => $row['lastName'],
					'age' => $row['age']
				];
			}

			return null;
		} catch (PDOException $e) {
			error_log("Error getting person by ID: " . $e->getMessage());
			return null;
		}
	}

	public static function create_person(object $personWithoutId): ?object
	{
		try {
			self::ensureTableExists();
			$db = self::getConnection();

			$person = $personWithoutId;
			$person->id = qstr::generateSuuid();

			$stmt = $db->prepare("INSERT INTO " . self::$tableName . " (id, firstName, lastName, age) VALUES (:id, :firstName, :lastName, :age)");
			$stmt->bindParam(':id', $person->id);
			$stmt->bindParam(':firstName', $person->firstName);
			$stmt->bindParam(':lastName', $person->lastName);
			$stmt->bindParam(':age', $person->age);

			if ($stmt->execute()) {
				return $person;
			}

			return null;
		} catch (PDOException $e) {
			error_log("Error creating person: " . $e->getMessage());
			return null;
		}
	}

	public static function delete_person_by_id(string $id): bool
	{
		try {
			self::ensureTableExists();
			$db = self::getConnection();

			$stmt = $db->prepare("DELETE FROM " . self::$tableName . " WHERE id = :id");
			$stmt->bindParam(':id', $id);

			$stmt->execute();
			return $stmt->rowCount() > 0;
		} catch (PDOException $e) {
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

			self::ensureTableExists();
			$db = self::getConnection();

			$stmt = $db->prepare("UPDATE " . self::$tableName . " SET firstName = :firstName, lastName = :lastName, age = :age WHERE id = :id");
			$stmt->bindParam(':id', $personToUpdate->id);
			$stmt->bindParam(':firstName', $personToUpdate->firstName);
			$stmt->bindParam(':lastName', $personToUpdate->lastName);
			$stmt->bindParam(':age', $personToUpdate->age);

			if ($stmt->execute() && $stmt->rowCount() > 0) {
				return $personToUpdate;
			}

			return null;
		} catch (PDOException $e) {
			error_log("Error updating person: " . $e->getMessage());
			return null;
		}
	}
}