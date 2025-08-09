<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAnnouncementRequest;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAnnouncementRequest $request)
    {
        $pathImg = $request['image_path']->store('announcements', 'public');
        $announcement = Announcement::create([
            'user_id' => Auth::id(),
            'title' => $request['title'],
            'description' => $request['description'],
            'departement' => $request['departement'],
            'website' => $request['website'],
            'image_path' => $pathImg
        ]);

        return response()->json([
            'message' => $announcement ? 'Annonce creee avec succes!' : 'Echec de la creation de l\'annonce!',
            'image_path' => asset('storage/' . $announcement->image_path)
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $announcements = Auth::user()->announcement()->get()->map(function ($announcement) {
            return [
                'id' => $announcement->id,
                'title' => $announcement->title,
                'description' => $announcement->description,
                'departement' => $announcement->departement,
                'website' => $announcement->website,
                'image_path' => asset('storage/' . $announcement->image_path)
            ];
        })->toArray();

        return response()->json([
            'annonces' => $announcements
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Announcement $announcement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Announcement $announcement)
    {
        //
    }
}
