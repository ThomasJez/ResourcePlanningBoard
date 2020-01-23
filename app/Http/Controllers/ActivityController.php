<?php

namespace App\Http\Controllers;

use App\Ganttconfig;
use Illuminate\Http\Request;
use App\Activity;
use App\Term;
use App\Http\Resources\ResourcesCollection;
use Illuminate\Http\Response;

class ActivityController extends Controller
{
    /**
     * @param Activity $activity
     * @return Activity
     */
    public function show(Activity $activity)
    {
        return $activity;
    }

    /**
     * @param Activity $activity
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function update(Activity $activity, Request $request)
    {
        $x = $request->get('x');
        $activity->start = (new \DateTime(Ganttconfig::find('start')->value))
            ->add(new \DateInterval('P' . $x . 'D'))
            ->format('Y-m-d');
        $activity->resource_id = $request->get('resourceId');
        $activity->duration = $request->get('duration');
        $activity->save();
        return response()->json($activity, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store(Request $request)
    {
        $activity = new Activity();
        $activity->id = $request->get('id');
        $x = $request->get('x');
        $activity->start = (new \DateTime(Ganttconfig::find('start')->value))
            ->add(new \DateInterval('P' . $x . 'D'))
            ->format('Y-m-d');
        $activity->resource_id = $request->get('resourceId');
        $activity->rule_id = $request->get('ruleId');
        $activity->duration = $request->get('duration');
        $activity->r = $request->get('r');
        $activity->g = $request->get('g');;
        $activity->b = $request->get('b');
        $activity->save();
        return response()->json($activity, Response::HTTP_CREATED);
    }

    /**
     * @param Activity $activity
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete(Activity $activity)
    {
        $activity->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

}
