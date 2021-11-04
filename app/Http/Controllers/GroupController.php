<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use App\Http\Resources\GroupResource;

class GroupController extends Controller
{
  public function index(Request $request)
  {
    try {
      $groups = Group::get();

      return response()->json([
        'success' => true,
        'groups' => GroupResource::collection($groups),
      ]);
    } catch (Exception $e) {
      return response()->json([
        'success' => false,
        'message' => $e->getMessage(),
      ], 500);
    }
  }
}
