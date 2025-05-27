<?php

namespace App\Repositories;

use App\Repositories\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        try {
            return $this->model->all();
        } catch (\Exception $e) {
            Log::error('Error in BaseRepository::all(): ' . $e->getMessage());
            throw $e;
        }
    }

    public function find(int $id)
    {
        try {
            return $this->model->withTrashed()->findOrFail($id);
        } catch (\Exception $e) {
            Log::error('Error in BaseRepository::find(): ' . $e->getMessage());
            throw $e;
        }
    }

    public function create(array $data)
    {
        try {
            return $this->model->create($data);
        } catch (\Exception $e) {
            Log::error('Error in BaseRepository::create(): ' . $e->getMessage());
            throw $e;
        }
    }

    public function update(int $id, array $data)
    {
        try {
            $record = $this->find($id);
            $record->update($data);
            return $record;
        } catch (\Exception $e) {
            Log::error('Error in BaseRepository::update(): ' . $e->getMessage());
            throw $e;
        }
    }

    public function delete(int $id)
    {
        try {
            $record = $this->find($id);
            return $record->delete();
        } catch (\Exception $e) {
            Log::error('Error in BaseRepository::delete(): ' . $e->getMessage());
            throw $e;
        }
    }

    public function findBy(string $field, $value)
    {
        try {
            return $this->model->where($field, $value)->first();
        } catch (\Exception $e) {
            Log::error('Error in BaseRepository::findBy(): ' . $e->getMessage());
            throw $e;
        }
    }
}
