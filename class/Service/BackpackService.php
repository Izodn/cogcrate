<?php
namespace CogCrate\Service;

use Doctrine\DBAL\Connection;

class BackpackService
{
	protected $db;
	protected $itemService;

	public function __construct(
		Connection $db,
		BackpackItemService $itemService
	) {
		$this->db = $db;
		$this->itemService = $itemService;
	}

	public function getBackpack(int $backpackId) : array
	{
		$result = $this->db->fetchAssoc(
			"SELECT id, capacity FROM playerbackpack WHERE id = ?",
			[$backpackId]
		);
		return empty($result) ? [] : $result;
	}

	public function createBackpack() : array
	{
		$newBackpack = $this->db->fetchAssoc(
			"INSERT INTO playerbackpack DEFAULT VALUES RETURNING id"
		);
		return $this->getBackpack($newBackpack["id"]);
	}

	public function getContents(int $backpackId) : array
	{
		$sql = <<<SQL
			SELECT bi.*
			FROM backpackitem AS bi
			INNER JOIN backpackitemlink AS bil ON bil.item = bi.id
			WHERE bil.backpack = ?
SQL;
		$newBackpack = $this->db->fetchAll($sql, [$backpackId]);
		return $newBackpack;
	}

	public function addItemToBackpack(int $itemId, int $backpackId) : bool
	{
		$item = $this->itemService->getItem($itemId);
		$backpack = $this->getBackpack($backpackId);
		if (empty($item) || empty($backpack)) {
			return false;
		}

		if ($item["weight"] !== 0.0) {
			$contents = $this->getContents($backpackId);
			$currentWeight = 0;
			foreach ($contents as $item) {
				$currentWeight += $item["weight"];
			}
			if ($currentWeight + $item["weight"] > $backpack["capacity"]) {
				return false;
			}
		}

		$sql = "INSERT INTO backpackitemlink (item, backpack) VALUES (?,?)";
		$query = $this->db->prepare($sql);
		$query->bindValue(1, $itemId, \PDO::PARAM_INT);
		$query->bindValue(2, $backpackId, \PDO::PARAM_INT);
		$query->execute();

		return true;
	}
}