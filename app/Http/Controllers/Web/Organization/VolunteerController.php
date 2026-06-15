<?php

namespace App\Http\Controllers\Web\Organization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VolunteerController extends Controller
{
    public function index(){
        view ('organization.volunteers.index');
    }
}
