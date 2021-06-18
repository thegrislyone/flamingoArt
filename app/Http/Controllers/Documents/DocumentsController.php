<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DocumentsController extends Controller
{
    
    public function downloadPrivacy() {

        return response()->download('documents/Politika_V_Otnoshenii_Obrabotki_Personalnykh_Dannykh.docx', 1);

    }

}
