<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Resource;
use App\Activity;
use App\Term;
use App\Http\Resources\ResourcesCollection;

class ResourceController extends Controller
{
    /**
     * @param Resource $resource
     * @return Resource
     */
    public function show(Resource $resource)
    {
        return $resource;
    }

    /**
     * @param Request $request
     * @param Resource $resource
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Resource $resource)
    {
        $request->validate([
            'name' => 'required',
        ]);
        $resource->name = $request->input('name');
        $resource->note = $request->input(('note'));
        $resource->save();
        return response()->json($resource, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        $resource = new Resource();
        $resource->name = $request->input('name');
        $resource->note = $request->input(('note'));
        $positions = Resource::pluck('pos')->toArray();
        $pos = !empty($positions) ? max($positions) : 0;
        $resource->pos = ++$pos;
        $resource->save();
        return response()->json($resource, Response::HTTP_CREATED);
    }

    /**
     * @param Resource $resource
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete(Resource $resource)
    {
        Activity::where('resource_id', $resource->id)->delete();
        $resource->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @return mixed
     */
    public function showPosition()
    {
        return Resource::orderBy('pos')->get();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePosition(Request $request)
    {
        $requestAsArray = $request->all();
        $validationArray = [];
        $requestKeys = array_keys($requestAsArray);
        foreach ($requestKeys as $key) {
            $validationArray[$key . '.pos'] = 'required|integer';
        }
        $request->validate($validationArray);
        foreach ($requestAsArray as $changedResource) {
            $resId = $changedResource['id'];
            $resource = Resource::find($resId);
            $resource->pos = $changedResource['pos'];
            $resource->save();
        }
        return response()->json(['message' => 'Resources reorderd']);
    }
}
