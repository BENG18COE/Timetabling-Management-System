<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Department;
use App\Models\Lecturer;
use App\Models\Module;
use App\Models\StudentClass;
use App\Models\Venue;
use Illuminate\Http\Request;

class TimetableController extends Controller
{
    public function timetableParameters(Request $request){
        $departments = Department::All();
        $venues     = Venue::all();
        $classes    = StudentClass::with('department')->get();
        $courses    = Course::all();
        $modules    = Module::all();
        $lecturers  = Lecturer::all();       

        return response()->json( compact("departments","venues","classes","courses","modules","lecturers"));
    }
}
