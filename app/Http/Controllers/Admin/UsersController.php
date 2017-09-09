<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Bzarzuela\ModelFilter;

class UsersController extends Controller
{
    private $model_filter;

    private $filter_rules = [
      'user_id' => ['=', 'user_id'],
      'name' => ['like', 'name'],
      'email' => ['=', 'email'],
    ];

    public function __construct()
    {
        $this->middleware('auth');
        $this->model_filter = new ModelFilter('users');

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

        $query = User::query();

        if ($order_by != '') {
            $users = $this->model_filter->filter($query)->orderBy($filters['sort'], $order_by)->paginate(10);
        } else {
            $users = $this->model_filter->filter($query)->orderBy('id', 'ASC')->paginate(10);
        }

        return view('admin.users.index', compact('users', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles_base  = Role::all();

        $roles  = [];
        foreach ($roles_base as $this_role) {
            $roles[$this_role->id]  = ucwords($this_role->name);
        }

        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *[
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\UserRequest $request)
    {
        $user   = User::create([
            'name'  => $request->input('name'),
            'user_id' => $request->input('user_id'),
            'email' => $request->input('email'),
            'password'  => bcrypt($request->input('password'))
        ]);
        $user->save();

        $user->roles()->sync($request->input('roles'));

        alert()->success("User {$user->name} has been successfully created");
        return redirect('admin/users');
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
        //
        $roles_base  = Role::all();

        $roles  = [];
        foreach ($roles_base as $this_role) {
            $roles[$this_role->id]  = ucwords($this_role->name);
        }

        $user_base   = User::find($id);
        $user_roles = $user_base->roles;

        $user   = $user_base->toArray();
        $user['roles']  = [];
        foreach ($user_roles as $this_role) {
            $user['roles'][]    = $this_role->id;
        }

        return view('admin.users.edit', compact('roles', 'user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\EditUserRequest $request, $id)
    {
        //
        $user   = User::find($id);
        $user->name = $request->input('name');
        $user->user_id = $request->input('user_id');
        $user->email    = $request->input('email');
        if ($request->input('password') != '') {
            $user->password = bcrypt($request->input('password'));
        }
        $user->save();

        $user->roles()->sync($request->input('roles'));

        alert()->success("User {$user->name} successfully edited");
        return redirect('admin/users');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user   = User::find($id);
        if ($user) {
            $user->delete();
        }
        return redirect('admin/users');
    }

    public function filter(Request $request)
    {
        $this->model_filter->setFormData($request->except('_token'));

        return redirect('/admin/users');
    }
}
