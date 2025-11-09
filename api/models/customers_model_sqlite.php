<?php
require_once __DIR__ . '/../../scripts/qtools/qstr.php';

class customers_model
{
	private static $dbPath = "../data/main.sqlite";
	private static $tableName = "customers";

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
				suuid TEXT PRIMARY KEY,
				firstName TEXT NOT NULL,
				lastName TEXT NOT NULL,
				address TEXT NOT NULL,
				zipcode TEXT NOT NULL,
				city TEXT NOT NULL
			)";
			$db->exec($sql);
		} catch (PDOException $e) {
			error_log("Error ensuring table exists: " . $e->getMessage());
			throw $e;
		}
	}

	public static function get_all_customers(): array
	{
		try {
			self::ensureTableExists();
			$db = self::getConnection();
			$stmt = $db->prepare("SELECT suuid, firstName, lastName, address, zipcode, city FROM " . self::$tableName);
			$stmt->execute();

			$customers = [];
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$customers[] = [
					'suuid' => $row['suuid'],
					'firstName' => $row['firstName'],
					'lastName' => $row['lastName'],
					'address' => $row['address'],
					'zipcode' => $row['zipcode'],
					'city' => $row['city']
				];
			}

			return $customers;
		} catch (PDOException $e) {
			error_log("Error getting all customers: " . $e->getMessage());
			return [];
		}
	}

	public static function get_customer_by_suuid(string $suuid): ?object
	{
		try {
			self::ensureTableExists();
			$db = self::getConnection();
			$stmt = $db->prepare("SELECT suuid, firstName, lastName, address, zipcode, city FROM " . self::$tableName . " WHERE suuid = :suuid");
			$stmt->bindParam(':suuid', $suuid);
			$stmt->execute();

			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($row) {
				return (object) [
					'suuid' => $row['suuid'],
					'firstName' => $row['firstName'],
					'lastName' => $row['lastName'],
					'address' => $row['address'],
					'zipcode' => $row['zipcode'],
					'city' => $row['city']
				];
			}

			return null;
		} catch (PDOException $e) {
			error_log("Error getting customer by suuid: " . $e->getMessage());
			return null;
		}
	}

	public static function create_customer(object $customerWithoutSuuid): ?object
	{
		try {
			self::ensureTableExists();
			$db = self::getConnection();

			$customer = $customerWithoutSuuid;
			$customer->suuid = qstr::generateSuuid();

			$stmt = $db->prepare("INSERT INTO " . self::$tableName . " (suuid, firstName, lastName, address, zipcode, city) VALUES (:suuid, :firstName, :lastName, :address, :zipcode, :city)");
			$stmt->bindParam(':suuid', $customer->suuid);
			$stmt->bindParam(':firstName', $customer->firstName);
			$stmt->bindParam(':lastName', $customer->lastName);
			$stmt->bindParam(':address', $customer->address);
			$stmt->bindParam(':zipcode', $customer->zipcode);
			$stmt->bindParam(':city', $customer->city);

			if ($stmt->execute()) {
				return $customer;
			}

			return null;
		} catch (PDOException $e) {
			error_log("Error creating customer: " . $e->getMessage());
			return null;
		}
	}

	public static function delete_customer_by_suuid(string $suuid): bool
	{
		try {
			self::ensureTableExists();
			$db = self::getConnection();

			$stmt = $db->prepare("DELETE FROM " . self::$tableName . " WHERE suuid = :suuid");
			$stmt->bindParam(':suuid', $suuid);

			$stmt->execute();
			return $stmt->rowCount() > 0;
		} catch (PDOException $e) {
			error_log("Error deleting customer: " . $e->getMessage());
			return false;
		}
	}

	public static function update_customer_by_suuid(object $customerToUpdate): ?object
	{
		try {
			if (!isset($customerToUpdate->suuid)) {
				return null;
			}

			self::ensureTableExists();
			$db = self::getConnection();

			$stmt = $db->prepare("UPDATE " . self::$tableName . " 
				SET firstName = :firstName, lastName = :lastName, address = :address, zipcode = :zipcode, city = :city
				WHERE suuid = :suuid");
			$stmt->bindParam(':suuid', $customerToUpdate->suuid);
			$stmt->bindParam(':firstName', $customerToUpdate->firstName);
			$stmt->bindParam(':lastName', $customerToUpdate->lastName);
			$stmt->bindParam(':address', $customerToUpdate->address);
			$stmt->bindParam(':zipcode', $customerToUpdate->zipcode);
			$stmt->bindParam(':city', $customerToUpdate->city);

			if ($stmt->execute() && $stmt->rowCount() > 0) {
				return $customerToUpdate;
			}

			return null;
		} catch (PDOException $e) {
			error_log("Error updating customer: " . $e->getMessage());
			return null;
		}
	}
}