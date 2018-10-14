<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Panel;
use Illuminate\Support\Facades\DB;

class OneDayElectricityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $panel = Panel::where('serial', $request->panel_serial)->first();

        return [[
            'day'     => DB::table('one_hour_electricities')->where('hour' , '<', DB::raw('curdate()'))->where('panel_id',$panel->id)->max('hour'),
            'sum'     => DB::table('one_hour_electricities')->where('hour' , '<', DB::raw('curdate()'))->where('panel_id',$panel->id)->sum('kilowatts'),
            'min'     => DB::table('one_hour_electricities')->where('hour' , '<', DB::raw('curdate()'))->where('panel_id',$panel->id)->min('kilowatts'),
            'max'     => DB::table('one_hour_electricities')->where('hour' , '<', DB::raw('curdate()'))->where('panel_id',$panel->id)->max('kilowatts'),
            'average' => DB::table('one_hour_electricities')->where('hour' , '<', DB::raw('curdate()'))->where('panel_id',$panel->id)->avg('kilowatts')
        ]];
    }
}
