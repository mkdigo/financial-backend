<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Services\EntryServices;
use App\Http\Resources\EntryResource;

class EntryController extends Controller
{
  public function index(Request $request)
  {
    try{
      $response = EntryServices::list($request);

      if($response instanceof \Illuminate\Http\JsonResponse) return $response;

      return response()->json([
        'success' => true,
        'entries' => EntryResource::collection($response),
      ]);
    } catch (Exception $e) {
      return response()->json([
        'success' => false,
        'message' => $e->getMessage(),
      ], 500);
    }
  }

  public function store(Request $request)
  {
    try{
      $response = EntryServices::store($request);

      if($response instanceof \Illuminate\Http\JsonResponse) return $response;

      return response()->json([
        'success' => true,
        'entry' => new EntryResource($response),
      ], 201);
    } catch (Exception $e) {
      return response()->json([
        'success' => false,
        'message' => $e->getMessage(),
      ], 500);
    }
  }

  public function update(Request $request, $id)
  {
    try{
      $entry = Entry::find($id);

      if(!$entry) return ResponseHelper::notFound('Entry not found.');

      $response = EntryServices::update($request, $entry);

      if($response instanceof \Illuminate\Http\JsonResponse) return $response;

      return response()->json([
        'success' => true,
        'entry' => new EntryResource($response),
      ]);
    } catch (Exception $e) {
      return response()->json([
        'success' => false,
        'message' => $e->getMessage(),
      ], 500);
    }
  }

  public function destroy(Request $request, $id)
  {
    try{
      $entry = Entry::find($id);

      if(!$entry) return ResponseHelper::notFound('Entry not found.');

      if(!EntryServices::delete($entry)) throw new Exception('Entry delete error.');

      return response()->json([
        'success' => true,
      ]);
    } catch (Exception $e) {
      return response()->json([
        'success' => false,
        'message' => $e->getMessage(),
      ], 500);
    }
  }
}
