<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvitationController extends Controller
{
    public function acceptInvitation($invitationId)
    {
        $invitation = Invitation::findOrFail($invitationId);

        if ((int) $invitation->to_user_id !== (int) Auth::id()) {
            abort(403, 'You cannot accept this invitation');
        }

        $invitation->update(['status' => 'accepted']);

        return response()->json(['message' => 'Invitation accepted'], 200);
    }

}
