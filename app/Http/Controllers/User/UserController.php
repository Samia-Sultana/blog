<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }


    public function index(Request $request)
    {
        $breadcrumbs = [
            ['link' => "/users", 'name' => "Users"],
            ['name' => "Index"]
        ];

        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $users = $this->user->orderBy($sortBy, $sortOrder)->with('userRole')->paginate(10);
        $userRoles = UserRole::all();
        return view('users.index', compact('users', 'breadcrumbs', 'userRoles'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users|max:255',
            'phone' => 'required|string|max:20',
            'password' => [
                        'required',
                        'string',
                        'min:8',               // Minimum 8 characters
                        'regex:/[A-Z]/',       // At least one uppercase letter
                        'regex:/[a-z]/',       // At least one lowercase letter
                        'regex:/[0-9]/',       // At least one number
                        'regex:/[@$!%*#?&]/',  // At least one special character
                    ],
            'role_id' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $validatedData['password'] = Hash::make($validatedData['password']);
        $validatedData['role'] = 'admin';

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('uploads/users', 'public');
            $validatedData['image'] = $imagePath;
        }

        User::create($validatedData);

        Session::flash('success', 'User successfully created.');

        return redirect()->route('users');
    }

    public function edit(User $user)
    {
        $userData = User::with('userRole')->findOrFail($user->id);

        return response()->json($userData);
    }

    public function view()
    {
        $breadcrumbs = [
            ['link' => "/users", 'name' => "Users"],
            ['name' => "Index"]
        ];

        $user = Auth::user();

        return view('users.profile', compact('user', 'breadcrumbs'));
    }

    public function update(Request $request, $id)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'password' => [
                        'nullable',
                        'string',
                        'min:8',               // Minimum 8 characters
                        'regex:/[A-Z]/',       // At least one uppercase letter
                        'regex:/[a-z]/',       // At least one lowercase letter
                        'regex:/[0-9]/',       // At least one number
                        'regex:/[@$!%*#?&]/',  // At least one special character
                    ],
            'role_id' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = User::find($id);

        if (isset($validatedData['password']) && $validatedData['password'] !== null) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }
        if ($request->hasFile('image')) {

            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }
            $validatedData['image'] = $request->file('image')->store('uploads/users', 'public');
        }

        $validatedData['role'] = 'admin';

        $user->update($validatedData);

        Session::flash('success', 'User successfully updated.');

        return redirect()->back();
    }

    public function destroy(Request $request)
    {

        $userId = $request->input('user_id');
        $user = User::findOrFail($userId);

        $user->delete();

        Session::flash('success', 'User successfully deleted.');

        return redirect()->route('users');
    }
}
