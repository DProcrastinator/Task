<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Beautician;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Models\BeauticianAppointment;
use App\Models\WorkingHour;
use Illuminate\Support\Carbon;
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
class BeauticianController extends BaseController
{
    public function index()
    {
        $beauticians = Beautician::pluck('name', 'id');
        return response()->json($beauticians);
    }


//boking an appointement
public function bookAppointment(Request $request){
    //validate the request using laravel validator
     $request->validate([
        'beautician_id' => 'required|integer',
        'date' => 'required|date',
        //'time' =>'required| date_format:H:i',
        //validate the time is
        'time' =>'required|date_format:H:i',
    ]);
    
    if(BeauticianAppointment::where('beautician_id', $request->beautician_id)->where('date', $request->date)->where('time', $request->time)->exists()){
        return response()->json(['error' => 'Appointment already exists'], 400);
    }

    $beautician = Beautician::where('id', $request->beautician_id)->exists();
    // return response()->json($beautician);
    if(!$beautician){
        return response()->json(['error' => 'Beautician not found'], 404);
    }

    //check if beautician is vacant
    $vacant = BeauticianAppointment::where('beautician_id', $request->beautician_id)->where('status', 'vacant')->get();
    if(!$vacant){
        return response()->json(['message' => 'Beautician is  reserved'], 400);
    }
    //get day from request using carbon
    $day = Carbon::parse($request->date)->format('l');
    // return response()->json($day);
    $workingday = WorkingHour::where('beautician_id', $request->beautician_id)->where('day',$day)->get();
    // return response()->json($workingday);
    //cheek if working day is null
    if(!$workingday  || $workingday->isEmpty()){
        return response()->json(['message' => 'Beautician is not available at this day'], 400);
    }
   
    //check if time is within working hours
    $open_time = Carbon::parse($workingday[0]->open_time);
    $close_time = Carbon::parse($workingday[0]->close_time);
    $appointment_time = Carbon::parse($request->time);
    if($appointment_time->lt($open_time) || $appointment_time->gt($close_time)){
        return response()->json(['message' => 'Beautician is not available at this time'], 400);
    }
  

    //check if appointment date is in the future
    $today = Carbon::now();
    $appointment_date = Carbon::parse($request->date);
    if($appointment_date->lt($today)){
        return response()->json(['message' => 'Appointment date is in the past'], 400);
    }
    
    //create appointment
    $appointment = new BeauticianAppointment();
    $appointment->beautician_id = $request->beautician_id;
    //date
    $appointment->date = $request->date;
    //time
    $appointment->time = $request->time;
    //reservation status
    $appointment->status = 'reserve';
    $appointment->save();
    return response()->json(['message' => 'Appointment booked successfully'], 200);
}


// get list of reserve and vacant time slot of the week 

public function getTimeSlot(Request $request , $beautician_id){

    $beautician = Beautician::find($beautician_id);
    if(!$beautician){
        return response()->json(['message' => 'Beautician does not exist'], 400);
    }
    // get list of reserve and vacant time slot of the week 
    $reserve = BeauticianAppointment::where('beautician_id', $beautician_id)->where('status','=', 'reserve')->get();
    $vacant = BeauticianAppointment::where('beautician_id', $beautician_id)->where('status','=', 'vacant')->get();
    $reserveTime = [];
    $vacantTime = [];
    // check data is null or not if null return empty array
    if($reserve){
    
        foreach($reserve as $reserve){
            $reserveTime[] = $reserve->time;
        }
    }
    if($vacant){
        foreach($vacant as $vacant){
            $vacantTime[] = $vacant->time;
        }
    }
    $data = [
        'reserve' => $reserveTime,
        'vacant' => $vacantTime
    ];
    return response()->json($data);
}
}
