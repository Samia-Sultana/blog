<?php

namespace App\Http\Controllers;

use App\Models\Query;
use Illuminate\Http\Request;

class QueryController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application
     * |\Illuminate\Contracts\View\Factory
     * |\Illuminate\Contracts\View\View
     */
    public function index()
    {

        $breadcrumbs = [
            ['link' => "/Query", 'name' => "Query"], ['name' => "Index"]
        ];

        $data = Query::orderBy('id', 'desc')->paginate(30);

        return view('query.index', compact('data', 'breadcrumbs'));

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required',
                'phone' => 'required',
                'message' => 'required',
                'company' => 'required',
                'subject' => 'required',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Validation failed',
                'error' => $e->getMessage()
            ]);
        }

        try {
            $data = Query::create($request->all());

            return response()->json([
                'message' => 'Query created successfully',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Query creation failed',
                'error' => $e->getMessage()
            ]);
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Query  $query
     * @return \Illuminate\Http\Response
     */
    public function show(Query $query)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Query  $query
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Query $query)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Query  $query
     * @return \Illuminate\Http\Response
     */
    public function destroy(Query $query)
    {
        //
    }
}
