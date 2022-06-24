<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Carbon\Carbon;
use Faker\Provider\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return EventResource::collection(Event::orderBy('start_date', 'ASC')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param EventRequest $request
     * @return EventResource
     */
    public function store(EventRequest $request): EventResource
    {
        $event = Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $this->parseDate($request->dateRange['startDate']),
            'end_date' => $this->parseDate($request->dateRange['endDate']),
        ]);
        return new EventResource($event);
    }

    /**
     * Display the specified resource.
     *
     * @param Event $event
     * @return EventResource
     */
    public function show(Event $event): EventResource
    {
        return new EventResource($event);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param EventRequest $request
     * @param Event $event
     * @return EventResource
     */
    public function update(EventRequest $request, Event $event): EventResource
    {
        $event->title = $request->title;
        $event->description = $request->description;
        $event->start_date = $this->parseDate($request->dateRange['startDate']);
        $event->end_date = $this->parseDate($request->dateRange['endDate']);
        $event->is_finished = $this->checkFinishedStatus($request->dateRange['endDate']);
        $event->save();

        return new EventResource($event);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Event $event
     * @return Response
     */
    public function destroy(Event $event): Response
    {
        $event->delete();

        return response()->noContent();
    }

    /**
     * @param $date
     * @return string
     */
    public function parseDate($date): string
    {
        return Carbon::parse($date)->format('Y-m-d');
    }

    /**
     * @param $date
     * @return bool
     */
    public function checkFinishedStatus($date): bool
    {
        $today = Carbon::now()->format('Y-m-d');
        $endDate = Carbon::parse($date)->format('Y-m-d');
        return $today > $endDate;
    }

    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function filterEvents(Request $request)
    {
        $events = null;
        $today = Carbon::today()->format('Y-m-d');
        $error = false;
        switch ($request->key) {
            case('finished'):
                $events = Event::where('is_finished', true)->orderBy('start_date', 'ASC')->get();
                break;
            case('upcoming'):
                $events = Event::where('start_date', '>=', $today)->orderBy('start_date', 'ASC')->get();
                break;
            case('upcoming-7'):
                $dateAfterSevenDays = Carbon::today()->addDays(7)->format('Y-m-d');
                $events = Event::whereBetween('start_date', [$today, $dateAfterSevenDays])->orderBy('start_date', 'ASC')->get();
                break;
            case('finished-7'):
                $dateBeforeSevenDays = Carbon::today()->subDays(7)->format('Y-m-d');
                $events = Event::whereBetween('end_date', [$dateBeforeSevenDays, $today])->orderBy('start_date', 'ASC')->get();
                break;
            default:
                $error = true;
        }
        return EventResource::collection($events);
    }
}
