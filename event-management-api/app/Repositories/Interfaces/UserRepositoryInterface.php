<?php

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Find user by email
     *
     * @param string $email
     * @return mixed
     */
    public function findByEmail(string $email);

    /**
     * Get user's organizations
     *
     * @param int $userId
     * @return mixed
     */
    public function getOrganizations(int $userId);

    /**
     * Get user's registered events
     *
     * @param int $userId
     * @return mixed
     */
    public function getRegisteredEvents(int $userId);

    /**
     * Update user's profile
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateProfile(int $id, array $data);
}
