<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAnnouncementRequest;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
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
    public function update(Request $request, Announcement $announcement)
    {
        //
    }
}
