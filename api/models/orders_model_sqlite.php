<?php
require_once __DIR__ . '/../../scripts/qtools/qstr.php';

class orders_model
{
	private static $dbPath = "../data/main.sqlite";
	private static $tableName = "orders";

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
				customer_suuid TEXT NOT NULL,
				article_suuid TEXT NOT NULL,
				amount INTEGER NOT NULL
			)";
			$db->exec($sql);
		} catch (PDOException $e) {
			error_log("Error ensuring table exists: " . $e->getMessage());
			throw $e;
		}
	}

	public static function get_all_orders(): array
	{
		try {
			self::ensureTableExists();
			$db = self::getConnection();
			$stmt = $db->prepare("SELECT suuid, customer_suuid, article_suuid, amount FROM " . self::$tableName);
			$stmt->execute();

			$orders = [];
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$orders[] = [
					'suuid' => $row['suuid'],
					'customer_suuid' => $row['customer_suuid'],
					'article_suuid' => $row['article_suuid'],
					'amount' => $row['amount']
				];
			}

			return $orders;
		} catch (PDOException $e) {
			error_log("Error getting all orders: " . $e->getMessage());
			return [];
		}
	}

	public static function get_order_by_suuid(string $suuid): ?object
	{
		try {
			self::ensureTableExists();
			$db = self::getConnection();
			$stmt = $db->prepare("SELECT suuid, customer_suuid, article_suuid, amount FROM " . self::$tableName . " WHERE suuid = :suuid");
			$stmt->bindParam(':suuid', $suuid);
			$stmt->execute();

			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($row) {
				return (object) [
					'suuid' => $row['suuid'],
					'customer_suuid' => $row['customer_suuid'],
					'article_suuid' => $row['article_suuid'],
					'amount' => $row['amount']
				];
			}

			return null;
		} catch (PDOException $e) {
			error_log("Error getting order by suuid: " . $e->getMessage());
			return null;
		}
	}

	public static function create_order(object $orderWithoutSuuid): ?object
	{
		try {
			self::ensureTableExists();
			$db = self::getConnection();

			$order = $orderWithoutSuuid;
			$order->suuid = qstr::generateSuuid();

			$stmt = $db->prepare("INSERT INTO " . self::$tableName . " (suuid, customer_suuid, article_suuid, amount) VALUES (:suuid, :customer_suuid, :article_suuid, :amount)");
			$stmt->bindParam(':suuid', $order->suuid);
			$stmt->bindParam(':customer_suuid', $order->customer_suuid);
			$stmt->bindParam(':article_suuid', $order->article_suuid);
			$stmt->bindParam(':amount', $order->amount);

			if ($stmt->execute()) {
				return $order;
			}

			return null;
		} catch (PDOException $e) {
			error_log("Error creating order: " . $e->getMessage());
			return null;
		}
	}

	public static function delete_order_by_suuid(string $suuid): bool
	{
		try {
			self::ensureTableExists();
			$db = self::getConnection();

			$stmt = $db->prepare("DELETE FROM " . self::$tableName . " WHERE suuid = :suuid");
			$stmt->bindParam(':suuid', $suuid);

			$stmt->execute();
			return $stmt->rowCount() > 0;
		} catch (PDOException $e) {
			error_log("Error deleting order: " . $e->getMessage());
			return false;
		}
	}

	public static function update_order_by_suuid(object $orderToUpdate): ?object
	{
		try {
			if (!isset($orderToUpdate->suuid)) {
				return null;
			}

			self::ensureTableExists();
			$db = self::getConnection();

			$stmt = $db->prepare("UPDATE " . self::$tableName . " 
				SET customer_suuid = :customer_suuid, article_suuid = :article_suuid, amount = :amount 
				WHERE suuid = :suuid");
			$stmt->bindParam(':suuid', $orderToUpdate->suuid);
			$stmt->bindParam(':customer_suuid', $orderToUpdate->customer_suuid);
			$stmt->bindParam(':article_suuid', $orderToUpdate->article_suuid);
			$stmt->bindParam(':amount', $orderToUpdate->amount);

			if ($stmt->execute() && $stmt->rowCount() > 0) {
				return $orderToUpdate;
			}

			return null;
		} catch (PDOException $e) {
			error_log("Error updating order: " . $e->getMessage());
			return null;
		}
	}
}