<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\FeedbacksModel;

class FeedbacksController extends Controller
{
    
    public function sendFeedback(Request $request) {

        $email = $request['email'];
        $description = $request['description'];

        FeedbacksModel::create([
            'email' => $email,
            'feedback_text' => $description
        ]);

        $status = [
            'status' => true,
        ];

        return response()->json($status, 200);

    }

    public function getFeedbacks() {

        $feedbacks = FeedbacksModel::get();

        return response()->json($feedbacks, 200);

    }

}
