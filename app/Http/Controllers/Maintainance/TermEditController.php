<?php

namespace App\Http\Controllers\Maintainance;

use App\Ganttconfig;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TermEditController extends Controller
{

    /**
     * shows config entries for terms
     * @return \Illuminate\View\View
     */
    public function show()
    {
        $configEntries = [];
        $resourceTerm = Ganttconfig::find('resource_term')->toArray();
        $resourceTerm['humanString'] = 'Term for Resources';
        $configEntries[] = (object)$resourceTerm;
        $ruleTerm = Ganttconfig::find('rule_term')->toArray();
        $ruleTerm['humanString'] = 'Term for Rules';
        $configEntries[] = (object)$ruleTerm;
        $withoutVue = true;
        return view('maintainance.termedit', compact('configEntries'), compact('withoutVue'));
    }

    /**
     * update terms
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $requestAsArray = $request->except('_token');
        $validationArray = [];
        $requestKeys = array_keys($requestAsArray);
        foreach ($requestKeys as $key) {
            $validationArray[$key] = 'required';
        }
        $request->validate($validationArray);

        foreach ($requestAsArray as $inputField => $value) {
            $key = str_replace('termedit', '', $inputField);
            $entry = Ganttconfig::find($key);
            $entry->value = $value;
            $entry->save();
        }
        return redirect()->route('termedit.show');
    }
}
