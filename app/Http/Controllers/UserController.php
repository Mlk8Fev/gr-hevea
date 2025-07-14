<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Services\NavigationService;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $navigationService;

    public function __construct(NavigationService $navigationService)
    {
        $this->navigationService = $navigationService;
    }

    // Afficher la liste des utilisateurs (superadmin seulement)
    public function index(Request $request)
    {
        $query = User::query();

        // Recherche par nom, prénom, email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                  ->orWhere('prenom', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('secteur', 'LIKE', "%{$search}%")
                  ->orWhere('fonction', 'LIKE', "%{$search}%");
            });
        }

        // Filtre par statut
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $users = $query->get();
        $secteurs = \App\Models\Secteur::orderBy('code')->get();
        $navigation = $this->navigationService->getNavigation();
        
        return view('admin.users.wowdash', compact('users', 'secteurs', 'navigation'));
    }

    // Afficher le formulaire de création d'utilisateur
    public function create()
    {
        $navigation = $this->navigationService->getNavigation();
        return view('admin.users.create', compact('navigation'));
    }

    // Créer un nouvel utilisateur
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:superadmin,admin,manager,user',
            'secteur' => 'nullable|string|max:255',
            'fonction' => 'nullable|string|max:255',
            'siege' => 'boolean',
            'status' => 'required|in:active,inactive'
        ]);

        User::create([
            'name' => $request->prenom . ' ' . $request->nom,
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'secteur' => $request->secteur,
            'fonction' => $request->fonction,
            'siege' => $request->siege ?? false,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur créé avec succès !');
    }

    // Afficher le formulaire d'édition
    public function edit(User $user)
    {
        $navigation = $this->navigationService->getNavigation();
        return view('admin.users.edit', compact('user', 'navigation'));
    }

    // Mettre à jour un utilisateur
    public function update(Request $request, User $user)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:superadmin,admin,manager,user',
            'secteur' => 'nullable|string|max:255',
            'fonction' => 'nullable|string|max:255',
            'siege' => 'boolean',
            'status' => 'required|in:active,inactive'
        ]);

        $data = [
            'name' => $request->prenom . ' ' . $request->nom,
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'role' => $request->role,
            'secteur' => $request->secteur,
            'fonction' => $request->fonction,
            'siege' => $request->siege ?? false,
            'status' => $request->status,
        ];

        // Mettre à jour le mot de passe seulement s'il est fourni
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur mis à jour avec succès !');
    }

    // Activer/Désactiver un utilisateur
    public function toggleStatus(User $user)
    {
        $user->update([
            'status' => $user->status === 'active' ? 'inactive' : 'active'
        ]);

        $status = $user->status === 'active' ? 'activé' : 'désactivé';
        return redirect()->route('admin.users.index')->with('success', "Utilisateur $status avec succès !");
    }

    // Supprimer un utilisateur
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé avec succès !');
    }
}
