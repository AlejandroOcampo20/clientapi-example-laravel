<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $token = Session::get('token');
        $url = env('URL_BASE_API', "http://localhost:8000");
        $response = Http::acceptJson()->withToken($token)->get($url . '/order');
        if ($response->status() == Response::HTTP_OK) {
            $orders = $response->json();
            return View('order.index', compact('orders'));
        } else {
            abort($response->status());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('order.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $url = env('URL_BASE_API', "http://localhost:8000");
        $response = Http::acceptJson()->withToken(session::get('token'))->post($url . '/order' , [
            'causals' => $request->causals,
            'observations' => $request->observations,
            'cities' => $request->cities,
        ]);
        
        if($response->status() == Response::HTTP_OK)
        {
            Session()->flash('message', 'registro creado exitosamente');
            return redirect()->route('order.index');
        }
        elseif($response->status() == Response::HTTP_BAD_REQUEST)
        {
            $errors = $response->json('errors');
            return redirect()->route('order.create')->withErrors($errors)->withInput();
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
        $response = Http::acceptJson()->withToken(session::get('token'))->post($url . '/order/' . $id,);

        if($response->successful())
        {

            $responseCausal = Http::acceptJson()->withToken(session::get('token'))->get($url . '/causal');
            $responseObservation = Http::acceptJson()->withToken(session::get('token'))->get($url . '/observation');
            if($responseCausal->successful() and $responseObservation->successful()){

                //consultar actividades disponibles
                $availableActivities = [];

                //consultar actividades agregadas a la orden
                $addedActivities = [];

                $causals = $responseCausal->json();
                $observations = $responseObservation->json();
                $order = $response->json();
                return view('order.edit', compact('order','causals','observations'));
            }
        }
        elseif($response->status() == Response::HTTP_BAD_REQUEST)
        {
            $errors = $response->json()['errors'];
            return redirect()->route('order.index')->withErrors($errors)->withInput();
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
        $response = Http::acceptJson()->withToken(session::get('token'))->put($url . '/order/' . $id, [
            'id' => $request->$id,
            'causals' => $request->causals,
            'observations' => $request->observations,
            'cities' => $request->cities,
        ]);
        
        if($response->status() == Response::HTTP_OK)
        {
            Session()->flash('message', 'registro creado exitosamente');
            return redirect()->route('order.index');
        }
        elseif($response->status() == Response::HTTP_BAD_REQUEST)
        {
            $errors = $response->json('errors');
            return redirect()->route('order.create')->withErrors($errors)->withInput();
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
        $response = Http::acceptJson()->withToken(Session::get('token'))->delete($url . '/order/' . $id);
        if($response->successful())
        {
            session()->flash('message','Registro eliminado exitosamente');
            return redirect()->route('order.index');
        }
        elseif($response->status() == Response::HTTP_BAD_REQUEST)
        {
            $errors = $response->json()['errors'];
            return redirect()->route('order.index')->withInput()->withErrors($errors);
        }
        else
        {
            abort($response->status());
        }
    }
}
