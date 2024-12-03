<?php

namespace App\Http\Controllers\Kanban;

use App\Http\Controllers\Controller;
use App\Models\CardModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CardController extends Controller
{
    public function store(Request $request)
    {
        $p = 6000;
        $data = [
            'column_id' => $request->column_id,
            'card_name' => $request->card_name,
            'status' => 1,
            'position' => $p
        ];
        $p += 6000;
        CardModel::create($data);

        return response()->json('success', 200);
    }

    public function delete($id)
    {
        $data = CardModel::find($id);
        $data->delete();

        return response()->json('success', 200);
    }


    public function updateCardPositions(Request $request)
    {
        $updates = $request->input('updates');

        foreach ($updates as $update) {
            DB::table('cards')
                ->where('id', $update['id'])
                ->update([
                    'column_id' => $update['column_id'],
                    'position' => $update['position'],
                ]);
        }

        return response()->json(['message' => 'Positions updated successfully!']);
    }

    public function updatePositions(Request $request)
    {
        $cards = $request->all();

        try {
            foreach ($cards as $cardData) {
                CardModel::where('id', $cardData['id'])->update([
                    'column_id' => $cardData['column_id'],
                    'position' => $cardData['position'],
                ]);
            }

            return response()->json(['message' => 'Card positions updated successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update card positions.'], 500);
        }
    }

    public function move(CardModel $card)
    {
        request()->validate([
            'column_id' => ['required', 'exists:card_lists,id'],
            'position' => ['required', 'numeric']
        ]);

        $card->update([
            'column_id' => request('column_id'),
            'position' => round(request('position'), 5)
        ]);

        return redirect()->back();
    }

}
