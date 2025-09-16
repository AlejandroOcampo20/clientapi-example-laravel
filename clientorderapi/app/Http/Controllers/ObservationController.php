<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class ObservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $token = Session::get('token');
        $url = env('URL_BASE_API', "http://localhost:8000");
        $response = Http::acceptJson()->withToken($token)->get($url . '/observation');
        if ($response->status() == Response::HTTP_OK) {
            $observations = $response->json();
            return View('observation.index', compact('observations'));
        } else {
            abort($response->status());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('observation.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $url = env('URL_BASE_API', "http://localhost:8000");
        $response = Http::acceptJson()->withToken(session::get('token'))->post($url . '/observation' , [
            'email' => $request->email,
            'password' => $request->password
        ]);

        if($response->status() == Response::HTTP_OK)
        {
            Session()->flash('message', 'registro creado exitosamente');
            return redirect()->route('observation.index');
        }
        elseif($response->status() == Response::HTTP_BAD_REQUEST)
        {
            $errors = $response->json('errors');
            return redirect()->route('observation.create')->withErrors($errors)->withInput();
        }
        else
        {
            abort($response->status());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $url = env('URL_BASE_API', "http://localhost:8000");
        $response = Http::acceptJson()->withToken(session::get('token'))->post($url . '/observation/' . $id,);

        if($response->successful())
        {
            $observation = $response->json();
            return view('observation.edit', compact('observation'));
        }
        elseif($response->status() == Response::HTTP_BAD_REQUEST)
        {
            $errors = $response->json()['errors'];
            return redirect()->route('observation.index')->withErrors($errors)->withInput();
        }
        else
        {
            abort($response->status());
        }
    }
    
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $url = env('URL_BASE_API', "http://localhost:8000");
        $response = Http::acceptJson()->withToken(session::get('token'))->put($url . '/observation/' . $id, [
            'id' => $request->$id,
            'password' => $request->password
        ]);

        if($response->status() == Response::HTTP_OK)
        {
            Session()->flash('message', 'registro creado exitosamente');
            return redirect()->route('observation.index');
        }
        elseif($response->status() == Response::HTTP_BAD_REQUEST)
        {
            $errors = $response->json('errors');
            return redirect()->route('observation.create')->withErrors($errors)->withInput();
        }
        else
        {
            abort($response->status());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
