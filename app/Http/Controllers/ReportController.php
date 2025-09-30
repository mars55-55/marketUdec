<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:listing,user',
            'listing_id' => 'required_if:type,listing|exists:listings,id',
            'user_id' => 'required_if:type,user|exists:users,id',
            'reason' => 'required|in:inappropriate,spam,fraud,fake,other',
            'description' => 'required|string|max:1000',
        ]);

        $user = Auth::user();

        // Verificar que no esté reportándose a sí mismo
        if ($request->type === 'user' && $request->user_id == $user->id) {
            return back()->with('error', '❌ No puedes reportarte a ti mismo.');
        }

        if ($request->type === 'listing') {
            $listing = Listing::findOrFail($request->listing_id);
            if ($listing->user_id == $user->id) {
                return back()->with('error', '❌ No puedes reportar tu propio anuncio.');
            }
        }

        // Verificar si ya existe un reporte similar
        $existingReport = Report::where('reporter_id', $user->id)
            ->where('listing_id', $request->listing_id)
            ->where('reported_user_id', $request->user_id)
            ->where('reason', $request->reason)
            ->where('status', 'pending')
            ->first();

        if ($existingReport) {
            return back()->with('error', '⚠️ Ya has reportado este contenido por la misma razón.');
        }

        // Crear el reporte
        Report::create([
            'reporter_id' => $user->id,
            'listing_id' => $request->type === 'listing' ? $request->listing_id : null,
            'reported_user_id' => $request->type === 'user' ? $request->user_id : null,
            'type' => $request->type,
            'reason' => $request->reason,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        return back()->with('success', '🚨 Reporte enviado exitosamente. Nuestro equipo lo revisará en las próximas 24 horas.');
    }
}