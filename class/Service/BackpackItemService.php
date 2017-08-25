<?php
namespace CogCrate\Service;

use Doctrine\DBAL\Connection;

class BackpackItemService
{
	protected $db;

	public function __construct(Connection $db)
	{
		$this->db = $db;
	}

	public function getItem(int $itemId) : array
	{
		$result =  $this->db->fetchAssoc(
			"SELECT id, name, weight FROM backpackitem WHERE id = ?",
			[$itemId]
		);
		return empty($result) ? [] : $result;
	}
}