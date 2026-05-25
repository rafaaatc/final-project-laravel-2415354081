<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ServicePageController extends Controller
{
    public function index()
    {
        $response = Http::get(
            'http://127.0.0.1:8000/api/services'
        );

        $services = $response->json()['data'];

        return view(
            'services.index',
            compact('services')
        );
    }

    public function create()
    {
        return view('services.create');
    }

    public function store(Request $request)
    {
        Http::post(
            'http://127.0.0.1:8000/api/services',
            [
                'name' => $request->name,
                'price' => $request->price,
                'description' => $request->description,
                'status' => $request->status,
            ]
        );

        return redirect('/services');
    }

    public function edit($id)
    {
        $response = Http::get(
            "http://127.0.0.1:8000/api/services/$id"
        );

        $service = $response->json()['data'];

        return view(
            'services.edit',
            compact('service')
        );
    }

    public function update(Request $request, $id)
    {
        Http::put(
            "http://127.0.0.1:8000/api/services/$id",
            [
                'name' => $request->name,
                'price' => $request->price,
                'description' => $request->description,
                'status' => $request->status,
            ]
        );

        return redirect('/services');
    }

    public function destroy($id)
    {
        Http::delete(
            "http://127.0.0.1:8000/api/services/$id"
        );

        return redirect('/services');
    }

    public function activate($id)
    {
        Http::patch(
            "http://127.0.0.1:8000/api/services/$id/activate"
        );

        return redirect('/services');
    }

    public function deactivate($id)
    {
        Http::patch(
            "http://127.0.0.1:8000/api/services/$id/deactivate"
        );

        return redirect('/services');
    }
}