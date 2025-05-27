<?php

namespace App\Repositories\Interfaces;

interface BaseRepositoryInterface
{
    /**
     * Get all records
     *
     * @return mixed
     */
    public function all();

    /**
     * Get record by ID
     *
     * @param int $id
     * @return mixed
     */
    public function find(int $id);

    /**
     * Create new record
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * Update record
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(int $id, array $data);

    /**
     * Delete record
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id);

    /**
     * Find record by specific field
     *
     * @param string $field
     * @param mixed $value
     * @return mixed
     */
    public function findBy(string $field, $value);
}
