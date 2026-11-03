<?php

namespace App\Http\Controllers;

use App\Models\AreaPage;

class AreaPageController extends Controller
{
    public function show(string $slug)
    {
        $areaPage = AreaPage::query()->where('slug', $slug)->firstOrFail();

        if (!$areaPage->isReadyForPublication()) {
            abort(404);
        }

        return view('areas.show', compact('areaPage'));
    }
}
