<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Stat;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Stat::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            Stat::updateOrCreate(
                [
                    'short_date' => $request->short_date,
                    'entity_value' => $request->entity_value
                ],
                [
                    'short_date' => $request->short_date,
                    'entity' => $request->entity,
                    'entity_value' => $request->entity_value,
                    'count' => $request->count,
                    'growth' => $request->growth,
                    'hate_speech_category' => $request->hate_speech_category,
                    'author' => $request->author
                ]
            );
            return response('success');
        } catch (Exception $e) {
            return response('error: ' . $e->getMessage(), '500');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Search for stats.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        // between dates and value
        if ($request->has('from_date') && $request->has('to_date') && $request->has('entity_value'))
            return Stat::whereBetween('short_date', [$request->from_date, $request->to_date])->where('entity_value', $request->entity_value)->get();

        // from date and value
        if ($request->has('from_date') && $request->has('entity_value'))
            return Stat::where('short_date', '>=', $request->from_date)->where('entity_value', $request->entity_value)->get();

        // to date and value
        if ($request->has('from_date') && $request->has('entity_value'))
            return Stat::where('short_date', '>=', $request->from_date)->where('entity_value', $request->entity_value)->get();


        // between dates and no value
        if ($request->has('from_date') && $request->has('to_date'))
            return Stat::whereBetween('short_date', [$request->from_date, $request->to_date])->get();

        // from date and no value
        if ($request->has('from_date') && $request->has('entity_value'))
            return Stat::where('short_date', '>=', $request->from_date)->get();

        // to date and no value
        if ($request->has('from_date') && $request->has('entity_value'))
            return Stat::where('short_date', '>=', $request->from_date)->get();
    }

    /**
     * Get count for today.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function today(Request $request)
    {
        if ($request->has('entity_value'))
            return Stat::where(['short_date' =>  Carbon::now()->format('Y-m-d'), 'entity_value' => $request->entity_value])->get();

        return Stat::where(['short_date' =>  Carbon::now()->format('Y-m-d'), 'entity' => 'categories'])->get();
    }

    public function countForPeriod(Request $request)
    {

        $data = [];

        $to_date = $request->to_date;
        $to_stats = Stat::where('short_date', $to_date)->get();

        if (!$request->has('from_date')) {
            foreach ($to_stats as $ts) {
                array_push($data, ['name' => $ts->entity_value, 'count' => $ts->count]);
            }
            return $data;
        }


        $from_date = $request->from_date;
        $from_stats = Stat::where('short_date', $from_date)->get();


        foreach ($to_stats as $ts) {
            foreach ($from_stats as $fs) {
                if ($ts->entity_value == $fs->entity_value) {
                    array_push($data, ['name' => $ts->entity_value, 'count' => $ts->count - $fs->count]);
                }
            }
        }

        return $data;
    }

    public function growthForPeriod(Request $request)
    {

        $data = [];

        $to_date = $request->to_date;
        $from_date = $request->from_date;
        $enity_value = $request->entity_value;

        if ($from_date) {
            $stats = Stat::whereBetween('short_date', [$from_date, $to_date])->where('entity_value', $enity_value)->orderBy('short_date', 'asc')->get();

            foreach ($stats as $s)
                array_push($data, ['count' => $s->count, 'name' => $s->entity_value, 'date' => $s->short_date]);
            return $data;
        }

        $stats = Stat::where('entity_value', $enity_value)->orderBy('short_date', 'asc')->get();

        foreach ($stats as $s)
            array_push($data, ['count' => $s->count, 'name' => $s->entity_value, 'date' => $s->short_date]);
        return $data;
    }
}
