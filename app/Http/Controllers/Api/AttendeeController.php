<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResource;
use App\Models\Attendee;
use App\Models\Event;
use App\Traits\LoadRelationship;
use Illuminate\Http\Request;

class AttendeeController extends Controller
{
    use LoadRelationship;
    private $acceptedRelations = ["user","event"];
    public function __construct(){
        $this->middleware("auth:sanctum")->except(["index","show","update"]);
        $this->authorizeResource(Attendee::class,"attendee");
    }
    
    public function index( Event $event)
    {
        $attendees = $this->applyIncludeRelation($event->attendees(),$this->acceptedRelations);

        return AttendeeResource::collection($attendees->latest()->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request ,Event $event )
    {
        //
        $attendee = $event->attendees()->create([
            "user_id" => 1
        ]);
        $attendee = $this->applyIncludeRelation($attendee,$this->acceptedRelations);
        return new AttendeeResource($attendee);

    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event, Attendee $attendee)
    {
        //
        return new AttendeeResource($this->applyIncludeRelation($attendee,$this->acceptedRelations));
    }

    /**
     * Update the specified resource in storage.
     */
   

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event, Attendee $attendee)
    {
        //
       
        // $this->authorize("delete-attendee",[$event,$attendee]);
        $attendee->delete();
        return response()->json([
            "message" => "Attendee Deleted sucessfully"
        ]);
        
    }
}
