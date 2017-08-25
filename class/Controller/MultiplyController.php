<?php
namespace CogCrate\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class MultiplyController
{
	public static function multiply(
		float $base,
		float $current = 0,
		int $iteration = 321
	) : float {
		if (
			($base === 0.0 && $current === 0.0)
			|| $iteration === 0
		) {
			return $current;
		}
		return Self::multiply($base, $current + $base, $iteration - 1);
	}

	public function multiplyBy321(Request $request) : JsonResponse
	{
		$input = $request->get("input");

		// Check if string can represent a float
		// http://php.net/manual/en/function.is-float.php#85848
		if ($input != (string)(float)$input) {
			return new JsonResponse([
				"error" => "$input is not a valid number"
			]);
		}

		return new JsonResponse([
			"equation" => "$input * 321",
			"result" => Self::multiply($input, 0, 321)
		]);
	}
}