<?php

namespace App\Http\Controllers\Admin;

use App\Holiday;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HolidayController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::user()->hasRole('admin')) {
            $this->redirect('/');
        }
        //
        $holidays   = Holiday::orderBy('event_date', 'DESC')->paginate(20);
        return view('admin.holiday.index', compact('holidays'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.holiday.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public
    function store(Request $request)
    {
        //
        if (!Auth::user()->hasRole('admin')) {
            $this->redirect('/');
        }
        Holiday::create([
            'name'  => $request->input('name', ''),
            'event_date'    => Carbon::parse($request->input('event_date'))
        ]);

        return redirect('admin/holiday');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Holiday $holiday)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Holiday $holiday)
    {
        //
        if (!Auth::user()->hasRole('admin')) {
            $this->redirect('/');
        }
        return view('admin.holiday.edit', compact('holiday'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Holiday $holiday)
    {
        //
        if (!Auth::user()->hasRole('admin')) {
            $this->redirect('/');
        }
        $holiday->name  = $request->input('name', '');
        $holiday->event_date    = Carbon::parse($request->input('event_date'));
        $holiday->save();
        return redirect('admin/holiday');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Holiday $holiday)
    {
        //
        if (!Auth::user()->hasRole('admin')) {
            $this->redirect('/');
        }
        $holiday->delete();
        return redirect('admin/holiday');
    }
}
