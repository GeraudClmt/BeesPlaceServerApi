<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAnnouncementRequest;
use App\Http\Requests\UpdateAnnouncementRequest;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::where('is_active', '=', true)->select(['title', 'description', 'departement', 'website', 'image_path'])->get()->map(function($announcement){
            return [
                'title' => $announcement->title,
                'description' => $announcement->description,
                'departement' => $announcement->departement,
                'website' => $announcement->website,
                'image_path' => asset('storage/' . $announcement->image_path)
            ];
        })->toArray();

        return response()->json([
            'message' => 'Liste de toutes les annonces',
            'announcements' => $announcements
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
        $showAnnouncement = Announcement::select(['title', 'description', 'departement', 'website', 'image_path'])->find($announcement->id);

        return response()->json([
            'message' => $announcement ? 'Annonce cree avec succes!' : 'Echec de la creation de l\'annonce!',
            'announcement' => $showAnnouncement,
        ], 200);
    }
    public function show()
    {
        $announcements = Auth::user()->announcement()->where('is_active', '=', true)->get()->map(function ($announcement) {
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
    public function update(UpdateAnnouncementRequest $request)
    {
        $pathImg = null;
        $announcement = Auth::user()->announcement()->find($request['id']);
        if($request['image_path']){
            $pathImg = $request['image_path']->store('announcements', 'public');
        }
        if ($announcement) {
            $announcement->update([
                'title' => $request['title'] ? $request['title'] :  $announcement->title,
                'description' => $request['description'] ? $request['description'] : $announcement->description,
                'departement' => $request['departement'] ? $request['departement'] : $announcement->departement,
                'website' => $request['website'] ? $request['website'] : $announcement->website,
                'image_path' => $pathImg != null ? $pathImg : $announcement->image_path
            ]);
            $showAnnouncement = Announcement::select(['title', 'description', 'departement', 'website', 'image_path'])->find($announcement->id);
            return response()->json([
                'message' => 'Annonce mise à jour avec succes!',
                'new_announcement' => $showAnnouncement
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
    public function showById(Request $request){
        $request->validate(
            [
                'id' => 'required|integer'
            ],
            [
                'id.required' => 'Le champ id est requis',
                'id.integer' => 'Le champ id doit être un nombre entier'
            ]
        );
        $announcement = Auth::user()->announcement()->find($request->id);

        return response()->json([
                'id' => $announcement->id,
                'title' => $announcement->title,
                'description' => $announcement->description,
                'department' => $announcement->departement,
                'image_path' => asset('storage/' . $announcement->image_path)
            ], 200);
    }
}
