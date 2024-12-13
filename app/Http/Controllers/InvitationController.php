<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvitationController extends Controller
{
    public function acceptInvitation($invitationId)
    {
        // Find the invitation
        $invitation = Invitation::findOrFail($invitationId);

        // Ensure the invitation belongs to the authenticated user
        if ($invitation->user_id !== Auth::id()) {
            return response()->json(['error' => 'You cannot accept this invitation'], 403);
        }

        // Change the status to 'accepted'
        $invitation->update(['status' => 'accepted']);

        return response()->json(['message' => 'Invitation accepted']);
    }
}
