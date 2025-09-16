<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $token = Session::get('token');
        $url = env('URL_BASE_API', "http://localhost:8000");
        $response = Http::acceptJson()->withToken($token)->get($url . '/technician');
        if ($response->successful()) {
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
        $url = env('URL_BASE_API', "http://localhost:8000");
        $responseTecnicians = Http::acceptJson()->withToken(session::get('token'))->get($url . '/technician');
        $responseTypeActivity = Http::acceptJson()->withToken(session::get('token'))->get($url . '/typeActivity');
        if ($responseTecnicians->successful() && $responseTypeActivity->successful()) {
            $technicians = $responseTecnicians->json();
            $types = $responseTypeActivity->json();
            return view('activity.create', compact('technicians', 'types'));
        } else {
            abort($responseTecnicians->status());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $url = env('URL_BASE_API', "http://localhost:8000");
        $response = Http::acceptJson()->withToken(session::get('token'))->post($url . '/Activity/', [
            'email' => $request->email,
            'password' => $request->password
        ]);

        if ($response->status() == Response::HTTP_OK) {
            Session()->flash('message', 'registro creado exitosamente');
            return redirect()->route('activity.index');
        } elseif ($response->status() == Response::HTTP_BAD_REQUEST) {
            $errors = $response->json('errors');
            return redirect()->route('type_activity.create')->withErrors($errors)->withInput();
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
        $response = Http::acceptJson()->withToken(session::get('token'))->post($url . '/activity/' . $id,);
        $responseTecnicians = Http::acceptJson()->withToken(session::get('token'))->get($url . '/technician');
        $responseTypeActivity = Http::acceptJson()->withToken(session::get('token'))->get($url . '/typeActivity');

        if ($response->successful()) {
            $technicians = $responseTecnicians->json();
            $types = $responseTypeActivity->json();
            $activity = $response->json();
            return view('activity.edit', compact('activity', 'technicians', 'types'));
        } elseif ($response->status() == Response::HTTP_BAD_REQUEST) {
            $errors = $response->json()['errors'];
            return redirect()->route('activity.index')->withErrors($errors)->withInput();
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
        $response = Http::acceptJson()->withToken(session::get('token'))->put($url . '/Activity/' . $id, [
            'id' => $request->id,
            'description' => $request->description,
            'hours' => $request->hours,
            'technician_id' => $request->technician_id,
            'type_activity_id' => $request->type_activity_id
        ]);

        if ($response->status() == Response::HTTP_OK) {
            Session()->flash('message', 'registro creado exitosamente');
            return redirect()->route('activity.index');
        } elseif ($response->status() == Response::HTTP_BAD_REQUEST) {
            $errors = $response->json('errors');
            return redirect()->route('activity.create')->withErrors($errors)->withInput();
        } else {
            abort($response->status());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $url = env('URL_BASE_API', "http://localhost:8000");
        $response = Http::acceptJson()->withToken(Session::get('token'))->delete($url . '/activity/' . $id);
        if($response->successful())
        {
            session()->flash('message','Registro eliminado exitosamente');
            return redirect()->route('activity.index');
        }
        elseif($response->status() == Response::HTTP_BAD_REQUEST)
        {
            $errors = $response->json()['errors'];
            return redirect()->route('activity.index')->withInput()->withErrors($errors);
        }
        else
        {
            abort($response->status());
        }
    }
}
