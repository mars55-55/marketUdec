<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Obtener conversaciones del usuario
        $conversations = Conversation::where('user1_id', $user->id)
            ->orWhere('user2_id', $user->id)
            ->with(['user1', 'user2', 'listing', 'messages' => function($query) {
                $query->latest()->limit(1);
            }])
            ->orderBy('updated_at', 'desc')
            ->get();

        // Conversación seleccionada
        $selectedConversation = null;
        $messages = collect();
        
        if ($request->has('conversation')) {
            $selectedConversation = $conversations->where('id', $request->conversation)->first();
            
            if ($selectedConversation) {
                // Verificar que el usuario tiene acceso a esta conversación
                if ($selectedConversation->user1_id == $user->id || $selectedConversation->user2_id == $user->id) {
                    $messages = $selectedConversation->messages()
                        ->with('user')
                        ->orderBy('created_at', 'asc')
                        ->get();
                    
                    // Marcar mensajes como leídos
                    $selectedConversation->messages()
                        ->where('user_id', '!=', $user->id)
                        ->whereNull('read_at')
                        ->update(['read_at' => now()]);
                } else {
                    $selectedConversation = null;
                }
            }
        }

        // Si hay parámetro listing_id, crear o encontrar conversación
        if ($request->has('listing_id')) {
            $listing = Listing::findOrFail($request->listing_id);
            
            // No permitir conversación consigo mismo
            if ($listing->user_id != $user->id) {
                $conversation = Conversation::where('listing_id', $listing->id)
                    ->where(function($query) use ($user, $listing) {
                        $query->where(function($q) use ($user, $listing) {
                            $q->where('user1_id', $user->id)
                              ->where('user2_id', $listing->user_id);
                        })->orWhere(function($q) use ($user, $listing) {
                            $q->where('user1_id', $listing->user_id)
                              ->where('user2_id', $user->id);
                        });
                    })->first();

                if (!$conversation) {
                    $conversation = Conversation::create([
                        'user1_id' => $user->id,
                        'user2_id' => $listing->user_id,
                        'listing_id' => $listing->id,
                    ]);
                    
                    // Recargar conversaciones
                    $conversations = Conversation::where('user1_id', $user->id)
                        ->orWhere('user2_id', $user->id)
                        ->with(['user1', 'user2', 'listing', 'messages' => function($query) {
                            $query->latest()->limit(1);
                        }])
                        ->orderBy('updated_at', 'desc')
                        ->get();
                }
                
                return redirect()->route('conversations.index', ['conversation' => $conversation->id]);
            }
        }

        return view('conversations.index', compact('conversations', 'selectedConversation', 'messages'));
    }

    public function show(Conversation $conversation)
    {
        $user = Auth::user();
        
        // Verificar acceso
        if ($conversation->user1_id != $user->id && $conversation->user2_id != $user->id) {
            abort(403);
        }

        return redirect()->route('conversations.index', ['conversation' => $conversation->id]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user2_id' => 'required|exists:users,id',
            'listing_id' => 'nullable|exists:listings,id',
            'message' => 'required|string|max:1000',
        ]);

        $user = Auth::user();

        // Verificar que no intente crear conversación consigo mismo
        if ($request->user2_id == $user->id) {
            return back()->withErrors(['error' => 'No puedes iniciar una conversación contigo mismo.']);
        }

        // Buscar conversación existente
        $conversation = Conversation::where(function($query) use ($user, $request) {
            $query->where('user1_id', $user->id)
                  ->where('user2_id', $request->user2_id);
        })->orWhere(function($query) use ($user, $request) {
            $query->where('user1_id', $request->user2_id)
                  ->where('user2_id', $user->id);
        });

        if ($request->listing_id) {
            $conversation->where('listing_id', $request->listing_id);
        }

        $conversation = $conversation->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'user1_id' => $user->id,
                'user2_id' => $request->user2_id,
                'listing_id' => $request->listing_id,
            ]);
        }

        // Crear el mensaje
        Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => $user->id,
            'message' => $request->message,
        ]);

        // Actualizar timestamp de la conversación
        $conversation->touch();

        return redirect()->route('conversations.index', ['conversation' => $conversation->id]);
    }

    public function storeMessage(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'message' => 'required|string|max:1000',
        ]);

        $user = Auth::user();
        $conversation = Conversation::findOrFail($request->conversation_id);

        // Verificar acceso
        if ($conversation->user1_id != $user->id && $conversation->user2_id != $user->id) {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 403);
        }

        // Crear mensaje
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => $user->id,
            'message' => $request->message,
        ]);

        // Actualizar timestamp de conversación
        $conversation->touch();

        return response()->json([
            'success' => true,
            'message' => $message->message,
            'created_at' => $message->created_at->toISOString(),
        ]);
    }

    public function checkNewMessages(Conversation $conversation, Request $request)
    {
        $user = Auth::user();
        
        // Verificar acceso
        if ($conversation->user1_id != $user->id && $conversation->user2_id != $user->id) {
            return response()->json(['messages' => []], 403);
        }

        $afterTime = $request->query('after');
        
        $query = $conversation->messages()
            ->where('user_id', '!=', $user->id)
            ->with('user');

        if ($afterTime) {
            $query->where('created_at', '>', $afterTime);
        }

        $messages = $query->orderBy('created_at', 'asc')->get();

        // Marcar como leídos
        if ($messages->count() > 0) {
            $conversation->messages()
                ->where('user_id', '!=', $user->id)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        }

        return response()->json([
            'messages' => $messages->map(function($message) {
                return [
                    'id' => $message->id,
                    'message' => $message->message,
                    'created_at' => $message->created_at->toISOString(),
                    'user_name' => $message->user->name,
                ];
            })
        ]);
    }

    public function block(Conversation $conversation)
    {
        $user = Auth::user();
        
        // Verificar acceso
        if ($conversation->user1_id != $user->id && $conversation->user2_id != $user->id) {
            abort(403);
        }

        // Implementar lógica de bloqueo si es necesaria
        // Por ahora, simplemente retornamos
        
        return back()->with('success', 'Conversación bloqueada.');
    }

    public function unblock(Conversation $conversation)
    {
        $user = Auth::user();
        
        // Verificar acceso
        if ($conversation->user1_id != $user->id && $conversation->user2_id != $user->id) {
            abort(403);
        }

        // Implementar lógica de desbloqueo si es necesaria
        // Por ahora, simplemente retornamos
        
        return back()->with('success', 'Conversación desbloqueada.');
    }
}
