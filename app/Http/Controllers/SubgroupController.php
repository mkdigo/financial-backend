<?php

namespace App\Http\Controllers;

use App\Models\Subgroup;
use Illuminate\Http\Request;
use App\Http\Resources\SubgroupResource;

class SubgroupController extends Controller
{
  public function index()
  {
    try {
      $subgroups = Subgroup::get();

      return response()->json([
        'success' => true,
        'subgroups' => SubgroupResource::collection($subgroups),
      ]);
    } catch (Exception $e) {
      return response()->json([
        'success' => false,
        'message' => $e->getMessage(),
      ], 500);
    }
  }
}
