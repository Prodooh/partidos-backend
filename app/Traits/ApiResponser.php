<?php
namespace App\Traits;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;

trait ApiResponser
{
	private function successResponse($data, $code)
	{
		return response()->json([ 'data' => $data ], $code);
	}

	protected function errorResponse($message, $code)
	{
		return response()->json(['error' => $message, 'code' => $code], $code);
	}

	protected function showAll(Collection $collection, $code = 200)
	{	
		return $this->successResponse($collection, $code);
	}

	protected function showOne($instance, $code = 200)
	{
		return $this->successResponse($instance, $code);
	}

	protected function showMessage($message, $code)
	{
		return response()->json(['message' => $message, 'code' => $code]);
	}

	protected function responseUploadFile($data, $code)
	{
		return response()->json(['data' => $data, 'code' => $code]);
	}

}