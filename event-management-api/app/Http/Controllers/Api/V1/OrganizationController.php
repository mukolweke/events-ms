<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\OrganizationRequest;
use App\Http\Resources\OrganizationResource;
use App\Services\OrganizationService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class OrganizationController extends BaseController
{
    protected $organizationService;

    public function __construct(OrganizationService $organizationService)
    {
        $this->organizationService = $organizationService;
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        $userId = Auth::id();
        $organizations = $this->organizationService->getOrganizationsByUserId($userId);
        return OrganizationResource::collection($organizations);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrganizationRequest $request): OrganizationResource
    {
        $organization = $this->organizationService->createOrganization($request->validated());
        return new OrganizationResource($organization);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): OrganizationResource
    {
        $organization = $this->organizationService->getOrganizationWithEvents($id);
        return new OrganizationResource($organization);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrganizationRequest $request, int $id): OrganizationResource
    {
        $organization = $this->organizationService->updateOrganization($id, $request->validated());
        return new OrganizationResource($organization);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): Response
    {
        $this->organizationService->delete($id);
        return response()->noContent();
    }

    /**
     * Get organization by slug
     */
    public function showBySlug(string $slug): OrganizationResource
    {
        $organization = $this->organizationService->getOrganizationBySlug($slug);
        return new OrganizationResource($organization);
    }
}
