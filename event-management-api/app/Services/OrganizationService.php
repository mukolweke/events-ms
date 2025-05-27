<?php

namespace App\Services;

use App\Repositories\Interfaces\OrganizationRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Exception;

class OrganizationService
{
    protected $organizationRepository;

    public function __construct(OrganizationRepositoryInterface $organizationRepository)
    {
        $this->organizationRepository = $organizationRepository;
    }

    /**
     * Get all organizations
     *
     * @return mixed
     */
    public function getAllOrganizations()
    {
        return $this->organizationRepository->all();
    }

    /**
     * Create a new organization
     *
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    public function createOrganization(array $data)
    {
        try {
            $this->validateOrganizationData($data);

            Log::info('Creating new organization', ['data' => $data]);
            return $this->organizationRepository->create($data);
        } catch (Exception $e) {
            Log::error('Error creating organization: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update organization settings
     *
     * @param int $id
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    public function updateOrganization(int $id, array $data)
    {
        try {
            $this->validateOrganizationData($data);

            Log::info('Updating organization', ['id' => $id, 'data' => $data]);
            return $this->organizationRepository->update($id, $data);
        } catch (Exception $e) {
            Log::error('Error updating organization: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get organization with its events
     *
     * @param int $id
     * @return mixed
     */
    public function getOrganizationWithEvents(int $id)
    {
        return $this->organizationRepository->getWithEvents($id);
    }

    /**
     * Get organization with its members
     *
     * @param int $id
     * @return mixed
     */
    public function getOrganizationWithMembers(int $id)
    {
        return $this->organizationRepository->getWithMembers($id);
    }

    /**
     * Get organization by slug
     *
     * @param string $slug
     * @return mixed
     */
    public function getOrganizationBySlug(string $slug)
    {
        return $this->organizationRepository->findBySlug($slug);
    }

    /**
     * Delete an organization
     *
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function delete(int $id)
    {
        try {
            Log::info('Deleting organization', ['id' => $id]);
            return $this->organizationRepository->delete($id);
        } catch (Exception $e) {
            Log::error('Error deleting organization: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Validate organization data
     *
     * @param array $data
     * @throws Exception
     */
    protected function validateOrganizationData(array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:organizations,slug',
            'description' => 'required|string',
            'logo' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            throw new Exception($validator->errors()->first());
        }
    }
}
