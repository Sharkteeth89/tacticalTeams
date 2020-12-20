<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mission;

class MissionController extends Controller
{
    public function createMission(Request $request){

        $data = $request->getContent();

        $data = json_decode($data);

        if ($data) {
    		$mission = new Mission();

    		$mission->description = $data->description;
            $mission->register_date = $data->register_date;
            $mission->priority = $data->priority;
            if(isset($data->state)){
                $mission->state = $data->state;
            } 
            
    		try{
    			$mission->save();
    			$response = "Mission Created";

    		}catch(\Exception $e){
    			$response = $e->getMessage();
    		}    		
    	}else{
            $response = "Invalid data"; 
        }

    	print_r($response);
    	die;
	}
	
    public function updateMission(Request $request, $id){

        $mission = Mission::find($id);
        $response="";

        if($mission){
    		$data = $request->getContent();

	    	$data = json_decode($data);

	    	if ($data) {
		    		
		    			$mission->description = (isset($data->description) ? $data->description: $mission->description);
		    			$mission->register_date = (isset($data->register_date) ? $data->register_date: $mission->register_date);
		    			$mission->priority = (isset($data->priority) ? $data->priority: $mission->priority);
		    			$mission->state = (isset($data->state) ? $data->state: $mission->state);
		    		try{
		    			$mission->save();
		    			$response = "Mission Updated";

		    		}catch(\Exception $e){
		    			$response = $e->getMessage();
		    			}	    		
	    	}else{
                $response = "No valid data";
            }    		
    		
    	}else{
    		$response = "No mission";
    	}
    	
    	print_r($response);
    	die;
	}	

	public function missionList(){

        $response = "";
        $mission = Mission::orderBy('priority', 'DESC')->get();
        $response = [];

        if($mission){

            foreach ($mission as $mission) {
                $response [] = [
                "id" => $mission->id,
                "register_date" => $mission->register_date,
                "priority" => $mission->priority,
                "state" => $mission->state,
                ];
            }

        }else{
            $response = "Misión no encontrada";
        }

        return response()->json($response);
    }

	public function missionInfo($id){
		
		$mission = Mission::find($id);

		if ($mission) {
			$response=[
				"description" => $mission->description,
				"priority" => $mission->priority,
				"register date" => $mission->register_date,
				"state" => $mission->state
			];

			$team = $mission->team;

			if ($team) {
				$response["team_id"] = $team->id;
				$response["team_name"] = $team->name;				
				$response["leader id"] = $team->leader->id;				
				$response["dog_tag_number"] = $team->leader->dog_tag_number;				
				$response["rank"] = $team->leader->rank;				
				$response["surname"] = $team->leader->surname;				
			}

			for ($i=0; $i < count($mission->soldier); $i++) { 
				$response[$i]["soldier id"] = $mission->soldier[$i]->id;				
				$response[$i]["dog_tag_number"] = $mission->soldier[$i]->dog_tag_number;				
				$response[$i]["rank"] = $mission->soldier[$i]->rank;				
				$response[$i]["surname"] = $mission->soldier[$i]->surname;
			}					
		}		

		return response()->json($response);
	}	
	
}
