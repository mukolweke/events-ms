<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\OrganizationResource;
use App\Services\OrganizationService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class OrganizationPublicController extends BaseController
{
    protected $organizationService;

    public function __construct(OrganizationService $organizationService)
    {
        $this->organizationService = $organizationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        $organizations = $this->organizationService->getAllOrganizations();

        return OrganizationResource::collection($organizations);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): OrganizationResource
    {
        $organization = $this->organizationService->getOrganizationWithEvents($id);
        return new OrganizationResource($organization);
    }
}
