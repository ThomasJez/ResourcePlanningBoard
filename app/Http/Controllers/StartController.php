<?php

namespace App\Http\Controllers;

use App\Ganttconfig;
use Illuminate\Http\Request;
use App\Term;
use App\Http\Resources\ResourcesCollection;

class StartController extends Controller
{

    /**
     * @return string
     * @throws \Exception
     */
    public function show()
    {
        return (new \DateTime(Ganttconfig::find('start')->value))->format('Y-m-d');
    }

    /**
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function update(Request $request)
    {
        $request->validate([
            0 => 'required|date_format:Y-m-d',
        ]);
        $startString = $request->input(0);
        $chartStart = \DateTime::createFromFormat('Y-m-d', $startString);
        $config = Ganttconfig::find('start');
        $config->value = $chartStart->format('Y-m-d');
        $config->save();
        return view('planningboard.planningboard');
    }

}
