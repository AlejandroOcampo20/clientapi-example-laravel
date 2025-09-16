<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class CausalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $token = Session::get('token');
        $url = env('URL_BASE_API', "http://localhost:8000");
        $response = Http::acceptJson()->withToken($token)->get($url . '/causal');
        if ($response->status() == Response::HTTP_OK) {
            $causals = $response->json();
            return View('causal.index', compact('causals'));
        } else {
            abort($response->status());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('causal.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $url = env('URL_BASE_API', "http://localhost:8000");

        $response = Http::acceptJson()
            ->withToken(Session::get('token'))
            ->post($url . '/causal', [
                'descripcion' => $request->descripcion
            ]);

        if ($response->status() == Response::HTTP_OK) {
            Session()->flash('message', 'registro creado exitosamente');
            return redirect()->route('causal.index');
        } elseif ($response->status() == Response::HTTP_BAD_REQUEST) {
            $errors = $response->json('errors');
            return redirect()->route('causal.create')->withErrors($errors)->withInput();
        } else {
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
        $response = Http::acceptJson()->withToken(session::get('token'))->post($url . '/causal/' . $id,);

        if ($response->successful()) {
            $causal = $response->json();
            return view('causal.edit', compact('causal'));
        } elseif ($response->status() == Response::HTTP_BAD_REQUEST) {
            $errors = $response->json()['errors'];
            return redirect()->route('causal.index')->withErrors($errors)->withInput();
        } else {
            abort($response->status());
        }
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $url = env('URL_BASE_API', "http://localhost:8000");

        $response = Http::acceptJson()
            ->withToken(Session::get('token'))
            ->put($url . '/causal/' . $id, [
                'descripcion' => $request->descripcion
            ]);

        if ($response->status() == Response::HTTP_OK) {
            Session()->flash('message', 'registro actualizado exitosamente');
            return redirect()->route('causal.index');
        } elseif ($response->status() == Response::HTTP_BAD_REQUEST) {
            $errors = $response->json('errors');
            return redirect()->route('causal.edit', $id)->withErrors($errors)->withInput();
        } else {
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
