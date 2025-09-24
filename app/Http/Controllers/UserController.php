<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Fonction;
use App\Models\Cooperative;
use App\Models\Secteur;
use App\Models\CentreCollecte;
use App\Services\NavigationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    protected $navigationService;

    public function __construct(NavigationService $navigationService)
    {
        $this->navigationService = $navigationService;
    }

    // Afficher la liste des utilisateurs
    public function index(Request $request)
    {
        $query = User::with(['fonction', 'cooperative', 'secteurRelation', 'centreCollecte'])
                    ->orderBy('created_at', 'desc');

        // Filtre de recherche simplifié
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->search}%")
                  ->orWhere('username', 'LIKE', "%{$request->search}%")
                  ->orWhere('email', 'LIKE', "%{$request->search}%")
                  ->orWhere('role', 'LIKE', "%{$request->search}%")
                  ->orWhere('secteur', 'LIKE', "%{$request->search}%");
            });
        }

        // Filtre par statut
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filtre par fonction
        if ($request->filled('fonction_id') && $request->fonction_id !== 'all') {
            $query->where('fonction_id', $request->fonction_id);
        }

        // Filtre par coopérative
        if ($request->filled('cooperative_id') && $request->cooperative_id !== 'all') {
            $query->where('cooperative_id', $request->cooperative_id);
        }

        $users = $query->paginate($request->get('per_page', 10));
        $secteurs = Secteur::orderBy('code')->get();
        $fonctions = Fonction::orderBy('nom')->get();
        $cooperatives = Cooperative::orderBy('nom')->get();
        $navigation = $this->navigationService->getNavigation();
        
        return view('admin.users.wowdash', compact('users', 'secteurs', 'fonctions', 'cooperatives', 'navigation'));
    }

    // Afficher le formulaire de création
    public function create()
    {
        $fonctions = Fonction::orderBy('nom')->get();
        $cooperatives = Cooperative::orderBy('nom')->get();
        $secteurs = Secteur::orderBy('code')->get();
        $centresCollecte = CentreCollecte::orderBy('nom')->get();
        $navigation = $this->navigationService->getNavigation();
        
        return view('admin.users.create', compact('fonctions', 'cooperatives', 'secteurs', 'centresCollecte', 'navigation'));
    }

    // Enregistrer un nouvel utilisateur
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:superadmin,admin,manager,user,qualite,agc',
            'secteur' => 'nullable|string|max:255',
            'fonction_id' => 'required|exists:fonctions,id',
            'cooperative_id' => 'nullable|exists:cooperatives,id',
            'centre_collecte_id' => 'nullable|exists:centres_collecte,id',
            'siege' => 'boolean',
            'status' => 'nullable|in:active,inactive'
        ]);

        // Vérifier si la fonction nécessite une coopérative
        $fonction = Fonction::find($request->fonction_id);
        
        if ($fonction && $fonction->peut_gerer_cooperative && !$request->cooperative_id) {
            return back()->withErrors(['cooperative_id' => 'Cette fonction nécessite de spécifier une coopérative.']);
        }

        User::create([
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'secteur' => $request->secteur,
            'fonction_id' => $request->fonction_id,
            'cooperative_id' => $request->cooperative_id,
            'centre_collecte_id' => $request->centre_collecte_id,
            'siege' => $request->siege ?? false,
            'status' => $request->status ?? 'active',
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur créé avec succès !');
    }

    // Afficher le formulaire d'édition
    public function edit(User $user)
    {
        $fonctions = Fonction::orderBy('nom')->get();
        $cooperatives = Cooperative::orderBy('nom')->get();
        $secteurs = Secteur::orderBy('code')->get();
        $centresCollecte = CentreCollecte::orderBy('nom')->get();
        $navigation = $this->navigationService->getNavigation();
        
        return view('admin.users.edit', compact('user', 'fonctions', 'cooperatives', 'secteurs', 'centresCollecte', 'navigation'));
    }

    // Mettre à jour un utilisateur
    public function update(Request $request, User $user)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:superadmin,admin,manager,user,qualite,agc',
            'secteur' => 'nullable|string|max:255',
            'fonction_id' => 'required|exists:fonctions,id',
            'cooperative_id' => 'nullable|exists:cooperatives,id',
            'centre_collecte_id' => 'nullable|exists:centres_collecte,id',
            'siege' => 'boolean',
            'status' => 'nullable|in:active,inactive'
        ]);

        // Vérifier si la fonction nécessite une coopérative
        $fonction = Fonction::find($request->fonction_id);
        
        if ($fonction && $fonction->peut_gerer_cooperative && !$request->cooperative_id) {
            return back()->withErrors(['cooperative_id' => 'Cette fonction nécessite de spécifier une coopérative.']);
        }

        // Préparer les données de mise à jour
        $updateData = [
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'secteur' => $request->secteur,
            'fonction_id' => $request->fonction_id,
            'cooperative_id' => $request->cooperative_id,
            'centre_collecte_id' => $request->centre_collecte_id,
            'siege' => $request->siege ?? false,
            'status' => $request->status ?? 'active',
        ];

        // Ajouter le mot de passe seulement s'il est fourni
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur mis à jour avec succès !');
    }

    // Supprimer un utilisateur
    public function destroy(User $user)
    {
        try {
            $user->delete();
            return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé avec succès !');
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')->with('error', 'Impossible de supprimer cet utilisateur.');
        }
    }

    // Afficher les détails d'un utilisateur
    public function show(User $user)
    {
        $user->load(['fonction', 'cooperative', 'secteurRelation', 'centreCollecte']);
        $navigation = $this->navigationService->getNavigation();
        
        return view('admin.users.show', compact('user', 'navigation'));
    }

    // Activer/Désactiver un utilisateur
    public function toggleStatus(User $user)
    {
        try {
            $newStatus = $user->status === 'active' ? 'inactive' : 'active';
            $user->update(['status' => $newStatus]);

            $statusText = $newStatus === 'active' ? 'activé' : 'désactivé';
            return redirect()->route('admin.users.index')->with('success', "Utilisateur $statusText avec succès !");
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')->with('error', 'Erreur lors du changement de statut.');
        }
    }
}
