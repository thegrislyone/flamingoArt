<?php

namespace App\Http\Controllers\Notifications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Items\ItemsModel;
use App\Models\Items\PurchasesModel;
use App\Models\Items\FavoritesModel;
use App\Models\User;
use App\Models\Notifications\NotificationsModel;

use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{

    public function getNotifications() {

        $user_id = Auth::user()->id;

        $notifications = NotificationsModel::where('to', '=', $user_id)->get();

        $notifications = array_map(function ($notification) {

            $notification['item'] = ItemsModel::find($notification['item_id']);
            unset($notification['item_id']);

            $notification['from'] = User::find($notification['from']);
            unset($notification['to']);

            return $notification;

        }, $notifications->toArray());

        $notifications = array_reverse($notifications);

        return response()->json($notifications, 200);

    }

    public function checkNotification(Request $request) {

        $notification_id = $request['notificaton_id'];

        NotificationsModel::find($notification_id)->update([
            'checked' => true
        ]);

        $status = [
            'status' => true
        ];

        return response()->json($status, 200);

    }

}
