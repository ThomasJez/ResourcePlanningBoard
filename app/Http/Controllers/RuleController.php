<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rule;
use App\Term;
use App\Http\Resources\ResourcesCollection;
use Illuminate\Http\Response;

class RuleController extends Controller
{
    /**
     * @param Rule $rule
     * @return Rule
     */
    public function show(Rule $rule)
    {
        return $rule;
    }

    /**
     * @param Request $request
     * @param Rule $rule
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Rule $rule)
    {
        $request->validate([
            'name' => 'required',
            'duration' => 'required|integer|max:21|min:1',
            'r' => 'required|integer|max:255|min:0',
            'g' => 'required|integer|max:255|min:0',
            'b' => 'required|integer|max:255|min:0',
        ]);
        $rule->name = $request->input('name');
        $rule->note = $request->input('note');
        $rule->duration = $request->input('duration');
        $rule->r = $request->input('r');
        $rule->g = $request->input('g');
        $rule->b = $request->input('b');
        $rule->save();
        return response()->json($rule, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'duration' => 'required|integer|max:21|min:1',
            'r' => 'required|integer|max:255|min:0',
            'g' => 'required|integer|max:255|min:0',
            'b' => 'required|integer|max:255|min:0',
        ]);
        $rule = new Rule();
        $rule->name = $request->input('name');
        $rule->note = $request->input(('note'));
        $rule->duration = $request->input(('duration'));
        $rule->r = $request->input('r');
        $rule->g = $request->input('g');
        $rule->b = $request->input('b');
        $positions = Rule::pluck('pos')->toArray();
        $pos = !empty($positions) ? max($positions) : 0;
        $rule->pos = ++$pos;
        $rule->save();
        return response()->json($rule, Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @param Rule $rule
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete(Request $request, Rule $rule)
    {
        $rule->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @return mixed
     */
    public function showPosition()
    {
        return Rule::orderBy('pos')->get();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePosition(Request $request)
    {
        $requestArray = $request->all();
        $validationArray = [];
        $requestKeys = array_keys($requestArray);
        foreach ($requestKeys as $key) {
            $validationArray[$key . '.pos'] = 'required|integer';
        }
        $request->validate($validationArray);
        foreach ($requestArray as $changedRule) {
            $id = $changedRule['id'];
            $rule = Rule::find($id);
            $rule->pos = $changedRule['pos'];
            $rule->save();
        }
        return response()->json(['message' => 'Rules reorderd']);
    }

}
