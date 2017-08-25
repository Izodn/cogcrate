<?php
namespace CogCrate\Controller;

use Doctrine\DBAL\Connection;

use Symfony\Component\HttpFoundation\JsonResponse;

class ItemController
{
	protected $db;

	public function __construct(Connection $db)
	{
		$this->db = $db;
	}

	public function afterId(int $id) : array
	{
		/* Efficient random: https://stackoverflow.com/a/19422 */
		$sql = <<<SQL
			SELECT i.*
			FROM item AS i
			WHERE id >= ?
			ORDER BY id ASC
			LIMIT 1
SQL;
		return $this->db->fetchAll($sql, [$id]);
	}

	public function getRandom() : JsonResponse
	{
		/*
			Here's where I would add caching for the last-inserted item in
			table. I didn't do that here for the sake of development time.
		*/
		$maxId = $this->db->fetchAssoc(
			"SELECT MAX(id) AS max FROM item"
		)["max"];
		$desired = \rand(0, $maxId);

		$results = $this->afterid($desired);

		if (count($results) === 0) {
			return new JsonResponse([
				"error" => "Could not locate random item"
			]);
		}

		return new JsonResponse($results[0]);
	}
}