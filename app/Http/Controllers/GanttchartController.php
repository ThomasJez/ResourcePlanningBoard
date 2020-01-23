<?php

namespace App\Http\Controllers;

use App\Ganttconfig;
use App\Resource;
use App\Activity;
use App\Rule;
use App\Term;
use App\Http\Resources\ResourcesCollection;
use Illuminate\Http\Response;

class GanttchartController extends Controller
{

    /**
     * provides all planning data via a single db request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function getEverything()
    {
        $chartStart = new \DateTime(Ganttconfig::find('start')->value);
        $start = $chartStart->getTimestamp();

        $resourceTerm = Ganttconfig::find('resource_term')->value;
        $ruleTerm = Ganttconfig::find('rule_term')->value;

        $g_activities = [];
        $g_resources = [];
        $resources = Resource::orderBy('pos')->get();
        foreach ($resources as $i => $resource) {
            $g_resources[$resource->id] = $i;
        }
        $activities = Activity::all();
        foreach ($activities as $activity) {
            $activity->y = $g_resources[$activity->resource_id];
            $absStart = new \DateTime($activity->start);
            $relStart = $chartStart->diff($absStart);
            $activity->x = (integer)$relStart->format('%r%a');
            if ($activity->x + $activity->duration < 0) {
                continue;
            }
            $g_activities[] = $activity;
        }
        $rules = Rule::orderBy('pos')->get();

        return response()->json([
            'resources' => $resources,
            'activities' => $g_activities,
            'rules' => $rules,
            'start' => $start,
            'resource_term' => $resourceTerm,
            'rule_term' => $ruleTerm
            ],
            Response::HTTP_OK
        );
    }

}
