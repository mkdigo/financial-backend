<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use App\Models\Entry;
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
    $entries = $this->repository->get();

    return $this->response([
      'entries' => EntryResource::collection($entries),
    ]);
  }

  public function store()
  {
    $entry = $this->repository->store();

    return $this->response([
      'entry' => new EntryResource($entry),
    ], 201);
  }

  public function update(Entry $entry)
  {
    $entry = $this->repository->update($entry);

    return $this->response([
      'entry' => new EntryResource($entry),
    ]);
  }

  public function destroy(Entry $entry)
  {
    $this->repository->delete($entry);

    return $this->response();
  }

  public function getExpenses()
  {
    $entries = $this->repository->getExpenses();

    return $this->response([
      'entries' => EntryResource::collection($entries),
    ]);
  }
}
