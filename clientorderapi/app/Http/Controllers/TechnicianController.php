<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class TechnicianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $token = Session::get('token');
        $url = env('URL_BASE_API', "http://localhost:8000");
        $response = Http::acceptJson()->withToken($token)->get($url . '/technician');
        if ($response->status() == Response::HTTP_OK) {
            $technicians = $response->json();
            return View('technician.index', compact('technicians'));
        } else {
            abort($response->status());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('technician.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $url = env('URL_BASE_API', "http://localhost:8000");
        $response = Http::acceptJson()->withToken(session::get('token'))->post($url . '/technician' , [
            'documento' => $request->documento,
            'name' => $request->name,
            'speciality' => $request->speciality,
            'phone' => $request->phone,
        ]);
        
        if($response->status() == Response::HTTP_OK)
        {
            Session()->flash('message', 'registro creado exitosamente');
            return redirect()->route('technician.index');
        }
        elseif($response->status() == Response::HTTP_BAD_REQUEST)
        {
            $errors = $response->json('errors');
            return redirect()->route('technician.create')->withErrors($errors)->withInput();
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
        $response = Http::acceptJson()->withToken(session::get('token'))->post($url . '/technician/' . $id,);

        if($response->successful())
        {
            $technician = $response->json();
            return view('technician.edit', compact('technician'));
        }
        elseif($response->status() == Response::HTTP_BAD_REQUEST)
        {
            $errors = $response->json()['errors'];
            return redirect()->route('technician.index')->withErrors($errors)->withInput();
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
        $response = Http::acceptJson()->withToken(session::get('token'))->put($url . '/technician/' . $id, [
            'id' => $request->$id,
            'documento' => $request->documento,
            'name' => $request->name,
            'speciality' => $request->speciality,
            'phone' => $request->phone,
        ]);
        
        if($response->status() == Response::HTTP_OK)
        {
            Session()->flash('message', 'registro creado exitosamente');
            return redirect()->route('technician.index');
        }
        elseif($response->status() == Response::HTTP_BAD_REQUEST)
        {
            $errors = $response->json('errors');
            return redirect()->route('technician.create')->withErrors($errors)->withInput();
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
        $url = env('URL_BASE_API', "http://localhost:8000");
        $response = Http::acceptJson()->withToken(Session::get('token'))->delete($url . '/technician/' . $id);
        if($response->successful())
        {
            session()->flash('message','Registro eliminado exitosamente');
            return redirect()->route('technician.index');
        }
        elseif($response->status() == Response::HTTP_BAD_REQUEST)
        {
            $errors = $response->json()['errors'];
            return redirect()->route('technician.index')->withInput()->withErrors($errors);
        }
        else
        {
            abort($response->status());
        }
    }
}
