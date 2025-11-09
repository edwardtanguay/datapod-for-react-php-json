<?php
require_once __DIR__ . '/../../scripts/qtools/qstr.php';

class articles_model
{
	private static $dbPath = "../data/main.sqlite";
	private static $tableName = "articles";

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
				description TEXT NOT NULL,
				price REAL NOT NULL
			)";
			$db->exec($sql);
		} catch (PDOException $e) {
			error_log("Error ensuring table exists: " . $e->getMessage());
			throw $e;
		}
	}

	public static function get_all_articles(): array
	{
		try {
			self::ensureTableExists();
			$db = self::getConnection();
			$stmt = $db->prepare("SELECT suuid, description, price FROM " . self::$tableName);
			$stmt->execute();

			$articles = [];
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$articles[] = [
					'suuid' => $row['suuid'],
					'description' => $row['description'],
					'price' => $row['price']
				];
			}

			return $articles;
		} catch (PDOException $e) {
			error_log("Error getting all articles: " . $e->getMessage());
			return [];
		}
	}

	public static function get_article_by_suuid(string $suuid): ?object
	{
		try {
			self::ensureTableExists();
			$db = self::getConnection();
			$stmt = $db->prepare("SELECT suuid, description, price FROM " . self::$tableName . " WHERE suuid = :suuid");
			$stmt->bindParam(':suuid', $suuid);
			$stmt->execute();

			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($row) {
				return (object) [
					'suuid' => $row['suuid'],
					'description' => $row['description'],
					'price' => $row['price']
				];
			}

			return null;
		} catch (PDOException $e) {
			error_log("Error getting article by suuid: " . $e->getMessage());
			return null;
		}
	}

	public static function create_article(object $articleWithoutSuuid): ?object
	{
		try {
			self::ensureTableExists();
			$db = self::getConnection();

			$article = $articleWithoutSuuid;
			$article->suuid = qstr::generateSuuid();

			$stmt = $db->prepare("INSERT INTO " . self::$tableName . " (suuid, description, price) VALUES (:suuid, :description, :price)");
			$stmt->bindParam(':suuid', $article->suuid);
			$stmt->bindParam(':description', $article->description);
			$stmt->bindParam(':price', $article->price);

			if ($stmt->execute()) {
				return $article;
			}

			return null;
		} catch (PDOException $e) {
			error_log("Error creating article: " . $e->getMessage());
			return null;
		}
	}

	public static function delete_article_by_suuid(string $suuid): bool
	{
		try {
			self::ensureTableExists();
			$db = self::getConnection();

			$stmt = $db->prepare("DELETE FROM " . self::$tableName . " WHERE suuid = :suuid");
			$stmt->bindParam(':suuid', $suuid);

			$stmt->execute();
			return $stmt->rowCount() > 0;
		} catch (PDOException $e) {
			error_log("Error deleting article: " . $e->getMessage());
			return false;
		}
	}

	public static function update_article_by_suuid(object $articleToUpdate): ?object
	{
		try {
			if (!isset($articleToUpdate->suuid)) {
				return null;
			}

			self::ensureTableExists();
			$db = self::getConnection();

			$stmt = $db->prepare("UPDATE " . self::$tableName . " 
				SET description = :description, price = :price 
				WHERE suuid = :suuid");
			$stmt->bindParam(':suuid', $articleToUpdate->suuid);
			$stmt->bindParam(':description', $articleToUpdate->description);
			$stmt->bindParam(':price', $articleToUpdate->price);

			if ($stmt->execute() && $stmt->rowCount() > 0) {
				return $articleToUpdate;
			}

			return null;
		} catch (PDOException $e) {
			error_log("Error updating article: " . $e->getMessage());
			return null;
		}
	}
}