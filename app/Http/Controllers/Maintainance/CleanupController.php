<?php

namespace App\Http\Controllers\Maintainance;

use App\Activity;
use App\Ganttconfig;
use App\Http\Controllers\Controller;

class CleanupController extends Controller
{
    /**
     * gets all activities which end before scheduling begins
     * @return View
     */
    public function show() {
        $start = (new \DateTime(Ganttconfig::find('start')->value))->format('Y-m-d');
        $pastActivities = $this->getPastActivities($start);
        return view('maintainance.cleanup',
            compact('start') + compact('pastActivities'));
    }

    /**
     * delete all old activities
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update() {
        $start = (new \DateTime(Ganttconfig::find('start')->value))->format('Y-m-d');
        $pastActivities = $this->getPastActivities($start);
        foreach ($pastActivities as $activity) {
            $activity->delete();
        }
        return redirect()->route('cleanup.update');
    }

    /**
     * helper method, gets all old activities
     * @param string $start
     * @return Illuminate\Database\Eloquent\Collection
     */
    private function getPastActivities($start) {
        $activities = Activity::with('resource')
            ->with('rule')
            ->get();

        $pastActivities = $activities->reject(function ($activity) use ($start){
            return $activity->end >= $start;
        });
        return $pastActivities;
    }
}
