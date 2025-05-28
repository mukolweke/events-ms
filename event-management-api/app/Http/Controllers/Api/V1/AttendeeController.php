<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResource;
use App\Services\RegistrationService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\Attendee;
use App\Models\Event;
use App\Models\Organization;

class AttendeeController extends Controller
{
    protected $registrationService;

    public function __construct(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of attendees for an event.
     */
    public function index($org_slug, Event $event)
    {
        $attendees = $this->registrationService->getEventAttendees($event->id);
        return AttendeeResource::collection($attendees);
    }

    /**
     * Register a user for an event.
     */
    public function register($org_slug, Event $event): AttendeeResource|\Illuminate\Http\JsonResponse
    {
        try {
            $userId = request()->get('userId');
            $attendee = $this->registrationService->registerForEvent($event->id, $userId, request()->all());

            return new AttendeeResource($attendee);
        } catch (\Exception $e) {
            Log::error('Error in register method: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
                'event_id' => $event->id
            ]);

            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Cancel event registration.
     */
    public function cancel($org_slug, Event $event): Response
    {
        $userId = request()->user()->id;
        $this->registrationService->cancelRegistration($event->id, $userId);

        Log::info('Registration cancelled', ['event_id' => $event->id, 'user_id' => $userId]);
        return response()->noContent();
    }

    /**
     * Export attendees list for an event.
     */
    public function export($org_slug, Event $event): StreamedResponse
    {
        $attendees = $this->registrationService->getEventAttendees($event->id);

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="attendees.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($attendees) {
            $file = fopen('php://output', 'w');

            // Add headers
            fputcsv($file, ['ID', 'Name', 'Email', 'Registration Date', 'Status']);

            // Add data
            foreach ($attendees as $attendee) {
                fputcsv($file, [
                    $attendee->id,
                    $attendee->user->name,
                    $attendee->user->email,
                    $attendee->registration_date,
                    $attendee->status,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * List all attendees for the current organization.
     */
    public function listForOrganization()
    {
        $attendees = $this->registrationService->getOrganizationAttendees();

        return AttendeeResource::collection($attendees);
    }
}
