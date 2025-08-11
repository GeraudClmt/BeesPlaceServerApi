<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAnnouncementRequest;
use App\Http\Requests\UpdateAnnoncementRequest;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::select(['title', 'description', 'departement', 'website', 'image_path'])->get();

        return response()->json([
            'message' => 'Liste de toutes les annonces',
            'annnouncements' => $announcements
        ], 200);
    }
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
        $showAnnouncement = $announcement::select(['title', 'description', 'departement', 'website', 'image_path'])->get();

        return response()->json([
            'message' => $announcement ? 'Annonce cree avec succes!' : 'Echec de la creation de l\'annonce!',
            'id' => $showAnnouncement,
            'image_path' => asset('storage/' . $announcement->image_path)
        ], 200);
    }
    public function show()
    {
        $announcements = Auth::user()->announcement()->get()->map(function ($announcement) {
            return [
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
    public function update(UpdateAnnoncementRequest $request)
    {
        $announcement = Auth::user()->announcement()->find($request['id']);
        if ($announcement) {
            $announcement->update([
                'title' => $request['title'] ? $request['title'] :  $announcement->title,
                'description' => $request['description'] ? $request['description'] : $announcement->description,
                'departement' => $request['departement'] ? $request['departement'] : $announcement->departement,
                'website' => $request['website'] ? $request['website'] : $announcement->website,
                'image_path' => $request['image_path'] ? $request['image_path'] : $announcement->image_path
            ]);
            return response()->json([
                'message' => 'Annonce mise à jour avec succes!',
                'new_announcement' => $announcement
            ], 200);
        } else {
            return response()->json([
                'message' => 'Annonce non trouvee'
            ], 404);
        }
    }
    public function delete(Request $request)
    {
        $request->validate(
            [
                'id' => 'required|integer'
            ],
            [
                'id.required' => 'Le champ id est requis',
                'id.integer' => 'Le champ id doit être un nombre entier'
            ]
        );

        try {
            $announcement =  Auth::user()->announcement()->find($request->id);
            if ($announcement) {
                $announcement->changeActive();
                return response()->json([
                    'message' => 'Suppression reussi'
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Annonce non trouvee'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Echec de la suppression de l\'annonce!'
            ], 400);
        }
    }
}
