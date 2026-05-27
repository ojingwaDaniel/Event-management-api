<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Models\User;
use App\Traits\LoadRelationship;
use Illuminate\Http\Request;
use Laravel\Sanctum\Sanctum;

class EventController extends Controller
{
    
    use LoadRelationship;
    private array $acceptedRelations = ["user", "attendees", "attendees.user"];
    public function __construct(){
        $this->middleware("auth:sanctum")->except(["index","show","userEvents"]);
        $this->authorizeResource(Event::class,"event");
    }
  
    public function index()
    {
        //
        $query = $this->applyIncludeRelation(Event::query(),$this->acceptedRelations);
        return EventResource::collection($query->latest()->paginate());
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventRequest $request)
    {
        //
        $event = Event::create([
            ...$request->validated(),
            "user_id" => $request->user()->id
        ]);
        return new EventResource($this->applyIncludeRelation($event,$this->acceptedRelations));
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        //
        
        return new EventResource($this->applyIncludeRelation($event,$this->acceptedRelations));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventRequest $request, Event $event)
    {
        //
        // $this->authorize("update-event",$event);
        $event->update($request->validated());
        return new EventResource($this->applyIncludeRelation($event,$this->acceptedRelations));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
    
        $event->delete();
        return response()->json([
            "message" => "Deleted the event sucessfully"
        ]);
    }
    public function userEvents(User $user){
        return  response()->json([
            $user->events

        ]) ;

    }
}