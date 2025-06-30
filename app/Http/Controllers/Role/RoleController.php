<?php

namespace App\Http\Controllers\Role;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\UserRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class RoleController extends Controller
{

    private $userRole;
    private $module;

    public function __construct(UserRole $userRole, Module $module)
    {
        $this->userRole = $userRole;
        $this->module = $module;
    }


    public function index(Request $request)
    {
        $breadcrumbs = [
            ['link' => "/roles", 'name' => "Roles"],
            ['name' => "Index"]
        ];

        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        $userRoles = $this->userRole->orderBy($sortBy, $sortOrder)->paginate(10);
        $modules = $this->module->all();
        return view('userRoles.index', compact('userRoles', 'breadcrumbs', 'modules'));
    }

    public function store(Request $request)
    {

        DB::beginTransaction();

        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'modules' => 'required|array',
            ]);

            $role = $this->userRole->create([
                'name' => $validatedData['name'],
            ]);

            $role->modules()->attach($validatedData['modules']);
            DB::commit();
            Session::flash('success', 'Role successfully created.');
            return redirect()->route('roles');
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('error', 'There was an error creating the role. Please try again.');
            return back()->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $userRoleData = UserRole::findOrFail($id);
            $modules = $userRoleData->modules;
            return response()->json([
                'userRole' => $userRoleData,
                'modules' => $modules,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'There was an error fetching the role data. Please try again.',
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'modules' => 'required|array',
            ]);

            $userRole = UserRole::findOrFail($id);

            $userRole->update([
                'name' => $validatedData['name'],
            ]);

            $userRole->modules()->sync($request->modules);

            Session::flash('success', 'Role successfully updated.');

            return redirect()->route('roles');
        } catch (\Exception $e) {
            Session::flash('error', 'An error occurred while updating the role: ' . $e->getMessage());
            return redirect()->route('roles');
        }
    }


    public function destroy(Request $request)
    {
        try {
            $userRole = UserRole::findOrFail($request->input('role_id'));

            $userRole->delete();

            Session::flash('success', 'Role successfully deleted.');

            return redirect()->route('roles');
        } catch (\Exception $e) {
            Session::flash('error', 'An error occurred while deleting the role: ' . $e->getMessage());
            return redirect()->route('roles');
        }
    }

}
