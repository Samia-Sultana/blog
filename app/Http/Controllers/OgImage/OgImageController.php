<?php

namespace App\Http\Controllers\OgImage;

use App\Models\OgImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class OgImageController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumbs = [
            ['link' => "/og-images", 'name' => "Images"], ['name' => "Index"]
        ];

        $data = OgImage::latest('created_at')->paginate(10);

        $searchQuery = '';

        return view('og-image.index', compact('breadcrumbs', 'data', 'searchQuery'));
    }

    public function search(Request $request)
    {
        $breadcrumbs = [
            ['link' => "/og-images", 'name' => "Images"], ['name' => "Index"]
        ];
        $searchQuery = $request->input('query');

        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        $data = OgImage::where('name', 'like', '%'.$searchQuery.'%')
            ->orWhere('slug', 'like', '%'.$searchQuery.'%')
            ->orderBy($sortBy, $sortOrder)
            ->paginate(10);

        return view('og-image.index', compact('breadcrumbs', 'data','searchQuery'));
    }

    public function destroy(Request $request)
    {
        $id = $request->og_image_id;

        $data = OgImage::find($id)->delete();

        if($data){
            return redirect()->route('og-images')->with('success', 'Image successfully deleted.');
        }else{
            return redirect()->route('og-images')->with('error', 'Image failed deleted.');
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'slug' => 'required',
            'image' => 'required'
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = uniqid() . '.' . $image->getClientOriginalExtension();
            Storage::putFileAs('public/ogImages/images', $image, $filename);
            $validated['image'] = 'storage/ogImages/images/' . $filename;
        }


        $data = OgImage::create($validated);

        if($data){
            return redirect()->route('og-images')->with('success', 'Image successfully created.');
        }else{
            return redirect()->route('og-images')->with('error', 'Image failed created.');
        }
    }
}
