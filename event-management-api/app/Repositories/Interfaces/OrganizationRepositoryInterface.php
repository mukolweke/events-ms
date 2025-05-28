<?php

namespace App\Repositories\Interfaces;

interface OrganizationRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get organization by slug
     *
     * @param string $slug
     * @return mixed
     */
    public function findBySlug(string $slug);

    /**
     * Get organization with its events
     *
     * @param int $id
     * @return mixed
     */
    public function getWithEvents(int $id);

    /**
     * Get organization with its members
     *
     * @param int $id
     * @return mixed
     */
    public function getWithMembers(int $id);

    /**
     * Get organizations by user ID
     *
     * @param int $userId
     * @return mixed
     */
    public function getByUserId(int $userId);
}
