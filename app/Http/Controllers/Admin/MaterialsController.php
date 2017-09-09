<?php

namespace App\Http\Controllers\Admin;

use App\Models\Material;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Bzarzuela\ModelFilter;
use Illuminate\Support\Facades\Auth;


class MaterialsController extends Controller
{
    private $model_filter;

    private $filter_rules = [
      'name' => ['like', 'name'],
    ];

    public function __construct()
    {
        $this->middleware('auth');
        $this->model_filter = new ModelFilter('materials');

        $this->model_filter->setRules($this->filter_rules);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::user()->hasRole('admin')) {
            alert()->error('Access Denied!');
            return back();
        }

        $filters = $this->model_filter->getFormData();

        $order_by = isset($filters['order']) ? $filters['order'] : '';

        $this->model_filter->setFormData($filters);

        $query = Material::query();

        if ($order_by != '') {
            $materials = $this->model_filter->filter($query)->orderBy($filters['sort'], $order_by)->paginate(10);
        } else {
            $materials = $this->model_filter->filter($query)->orderBy('id', 'ASC')->paginate(10);
        }

        return view('admin.materials.index', compact('materials', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->hasRole('admin')) {
            alert()->error('You are not allowed to manage Materials');
            return back();
        }

        return view('admin.materials.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $material = Material::firstOrCreate([
          'name' => $request->get('name'),
        ]);;

        alert()->success('Material successfully added');

        return redirect('/admin/materials');
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth::user()->hasRole('admin')) {
            alert()->error('You are not allowed to manage Materials');
            return back();
        }
        $material = Material::find($id);
        if (!$material) {
            alert()->error('Material: ' . $id . ' not found');

            return back();
        }

        return view('admin.materials.edit', compact('material'));
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
        $material = Material::findOrFail($id);
        $material->update($request->all());
        alert()->success('Material ID ' . $id . ' changes saved.');

        return redirect('/admin/materials');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $material   = Material::find($id);
        if ($material) {
            $material->delete();
        }
        return redirect('admin/materials');
    }

    public function filter(Request $request)
    {
        $this->model_filter->setFormData($request->except('_token'));

        return redirect('/admin/materials');
    }
}
