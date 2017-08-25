<?php
namespace CogCrate\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

use CogCrate\Service\BackpackService;

class BackpackController
{
	protected $backpackService;

	public function __construct(BackpackService $backpackService)
	{
		$this->backpackService = $backpackService;
	}

	public function createNew() : JsonResponse
	{
		return new JsonResponse($this->backpackService->createBackpack());
	}

	public function get(int $backpackId) : JsonResponse
	{
		return new JsonResponse(
			$this->backpackService->getBackpack($backpackId)
		);
	}

	public function contents(int $backpackId) : JsonResponse
	{
		return new JsonResponse(
			$this->backpackService->getContents($backpackId)
		);
	}

	public function addItem(int $itemId, int $backpackId) : JsonResponse
	{
		$itemAdded = $this->backpackService->addItemToBackpack(
			$itemId,
			$backpackId
		);

		return new JsonResponse([
			"success" => $itemAdded
		]);
	}
}