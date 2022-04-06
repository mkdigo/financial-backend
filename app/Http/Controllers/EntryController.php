<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use App\Exceptions\ExceptionHandler;
use App\Http\Resources\EntryResource;
use App\Repositories\EntryRepositoryInterface;

class EntryController extends Controller
{
  private $repository;

  public function __construct(EntryRepositoryInterface $repository)
  {
    $this->repository = $repository;
  }

  public function index()
  {
    try{
      $response = $this->repository->get();

      return response()->json([
        'success' => true,
        'entries' => EntryResource::collection($response),
      ]);
    } catch (ExceptionHandler $e) {
      return $this->errorHandler($e);
    }
  }

  public function store()
  {
    try{
      $response = $this->repository->store();

      return response()->json([
        'success' => true,
        'entry' => new EntryResource($response),
      ], 201);
    } catch (ExceptionHandler $e) {
      return $this->errorHandler($e);
    }
  }

  public function update($id)
  {
    try{
      $response = $this->repository->update((int) $id);

      return response()->json([
        'success' => true,
        'entry' => new EntryResource($response),
      ]);
    } catch (ExceptionHandler $e) {
      return $this->errorHandler($e);
    }
  }

  public function destroy($id)
  {
    try{
      $this->repository->delete((int) $id);

      return response()->json([
        'success' => true,
      ]);
    } catch (ExceptionHandler $e) {
      return $this->errorHandler($e);
    }
  }

  public function getExpenses()
  {
    try{
      $response = $this->repository->getExpenses();

      return response()->json([
        'success' => true,
        'entries' => EntryResource::collection($response),
      ]);
    } catch (ExceptionHandler $e) {
      return $this->errorHandler($e);
    }
  }
}
