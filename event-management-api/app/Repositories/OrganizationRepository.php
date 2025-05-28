<?php

namespace App\Repositories;

use App\Models\Organization;
use App\Repositories\Interfaces\OrganizationRepositoryInterface;

class OrganizationRepository extends BaseRepository implements OrganizationRepositoryInterface
{
    public function __construct(Organization $model)
    {
        parent::__construct($model);
    }

    public function findBySlug(string $slug)
    {
        return $this->model->where('slug', $slug)->first();
    }

    public function getWithEvents(int $id)
    {
        return $this->model->with('events')->findOrFail($id);
    }

    public function getWithMembers(int $id)
    {
        return $this->model->with('members')->findOrFail($id);
    }

    public function getByUserId(int $userId)
    {
        return $this->model->join('organization_users', 'organizations.id', '=', 'organization_users.organization_id')
            ->where('organization_users.user_id', $userId)
            ->get();
    }
}
