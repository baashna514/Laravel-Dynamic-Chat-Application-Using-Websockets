<?php

namespace App\Http\Controllers;

use App\Events\MessageDeleteEvent;
use App\Events\MessageEvent;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;

class UserController extends Controller
{
    public function loadDashboard(){
        $data['users'] = User::whereNotIn('id', [Auth::id()])
            ->with(['sentMessages', 'receivedMessages'])
            ->get();
        return view('dashboard', $data);
    }

    public function saveChat(Request $request){
        try{
            $chat = Chat::create([
                'sender_id' => $request->sender_id,
                'receiver_id' => $request->receiver_id,
                'message' => $request->message,
            ]);
            // broadcast chat to MessageEvent...
            event(new MessageEvent($chat));
            return response()->json(['success'=>true, 'data' => $chat]);
        }catch (Exception $e){
            return response()->json(['success'=>false, 'msg' => $e->getMessage()]);
        }
    }

    public function loadChat(Request $request){
        try{
            $chats = Chat::where(function ($q) use ($request){
                return $q->where('sender_id', '=', $request->sender_id)
                        ->orWhere('sender_id', '=', $request->receiver_id);
            })
            ->where(function ($q) use ($request){
                return $q->where('receiver_id', '=', $request->sender_id)
                        ->orWhere('receiver_id', '=', $request->receiver_id);
            })->get();
            return response()->json(['success'=>true, 'data' => $chats]);
        }catch (Exception $e){
            return response()->json(['success'=>false, 'msg' => $e->getMessage()]);
        }
    }

    public function deleteChat(Request $request){
        try{
            Chat::where('id', $request->id)->delete();
            event(new MessageDeleteEvent($request->id));
            return response()->json(['success'=>true, 'msg' => 'Chat deleted successfully!']);
        }catch (Exception $e){
            return response()->json(['success'=>false, 'msg' => $e->getMessage()]);
        }
    }
}
