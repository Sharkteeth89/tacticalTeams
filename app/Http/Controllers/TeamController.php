<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\Soldier;
use App\Models\Mission;
use App\Models\SoldierMission;

class TeamController extends Controller
{
    public function createTeam(Request $request){

        $data = $request->getContent();

        $data = json_decode($data);

        if ($data) {
    		$team = new Team();
			$team->name = $data->name;
			            
    		try{
    			$team->save();
    			$response = "Team Created";

    		}catch(\Exception $e){
    			$response = $e->getMessage();
    		}    		
    	}else{
            $response = "Invalid data"; 
        }

    	print_r($response);
    	die;
    }
    public function updateTeam(Request $request, $id){

        $team = Team::find($id);
        $response="";

        if($team){
    		$data = $request->getContent();

	    	$data = json_decode($data);

	    	if ($data) {		    		
		    		$team->name = (isset($data->name) ? $data->name: $team->name);
		    			
		    		try{
		    			$team->save();
		    			$response = "Team Updated";

		    		}catch(\Exception $e){
		    			$response = $e->getMessage();
		    		}	    		
	    	}else{
                $response = "No valid data";
            }    		
    		
    	}else{
    		$response = "No team";
    	}    	
    	print_r($response);
    	die;
    }    
    public function deleteTeam($id){

        $team = Team::find($id);
        $response="";

        if($team){
            try {
                $team->delete();
                $response = "Team Deleted";
            } catch (\Exception $e) {
                $response = $e->getMessage();
            }
        }else{
            $response = "No team";
        }
        print_r($response);
    	die;
    }
	public function addSoldier(Request $request){

        $response = "";
        $data = $request->getContent();

        $data = json_decode($data);

        $soldier = Soldier::find($data->soldier);

        if($data&&Team::find($data->team)&&$soldier){


            $soldier->team_id = $data->team;

            try{
                $soldier->save();
                $response = "OK";
            }catch(\Exception $e){
                $response = $e->getMessage();
            }

        }
        return response($response);

    }	
	public function addLeader(Request $request){

        $response = "";
        
        $data = $request->getContent();

		$data = json_decode($data);
        $team = Team::find($data->team);
        $soldier = Soldier::find($data->soldier);

        if($data&&$team&&$soldier){

            $soldier->team_id = $data->team;
            if (!isset($team->leader_id)) {
                $team->leader_id = $data->soldier;

                try{
                    $team->save();
                    $soldier->save();
                    $response = "Leader Added";
                }catch(\Exception $e){
                    $response = $e->getMessage();
                }

            }else{
                $response = "Leader already exists";
            }
        }
        return response($response);

    }
    public function assingMission(Request $request){
        $response = "";
        
        $data = $request->getContent();

        $data = json_decode($data);
        
        $team = Team::find($data->team_id);
        $mission = Mission::find($data->mission_id);
        if($data){

            if ($team && !isset($team->mission_id)){
                if ($mission) {
                    $team->mission_id = $mission->id;
                    $mission->state = "in_progress";
    
                    $soldiers = $this->getTeamMembers($team->id);
    
                    foreach($soldiers as $soldier){
                        $soldier_mission = new SoldierMission;
                        $soldier_mission->soldier_id = $soldier['id'];
                        $soldier_mission->mission_id = $mission->id;
                        $soldier_mission->save();
                    }
    
                    try {
                        $team->save();
                        $mission->save();
                        $response = "Mission Assigned";
                    } catch (\Exception $e) {
                        $response = $e->getMessage();
                    }
                }else{
                    $response = "No valid mission";
                }
            }else{
                $response = "No valid team or mission already assigned";
            }
        }
        

        return response($response);
    }
    public function getTeamMembers($id){

        $soldiers = Soldier::all();

        $response = [];

        foreach ($soldiers as $soldier) {

            if($soldier->team_id === $id){

                $response[] = [
                    "id" => $soldier->id
                ];
            }

        }
        return $response;
    }
    public function teamInfo($id){
        $team = Team::find($id);
        $response=[];

        if ($team) {

            $response=[
                "Leader id" => $team->leader->id,
                "Leader name" => $team->leader->name,
                "Leader surname" => $team->leader->surname,
                "Leader birth date" => $team->leader->birth_date,
                "Leader join_army_date" => $team->leader->join_army_date, 
                "Leader rank" => $team->leader->rank,               
                "Leader dog_tag_number" => $team->leader->dog_tag_number,                
                "Leader state" => $team->leader->state                
                
            ];
            for ($i=0; $i < count($team->soldier); $i++) { 
                $response[$i]["soldier id"] = $team->soldier[$i]->id;				
                $response[$i]["soldier surname"] = $team->soldier[$i]->surname;				
                $response[$i]["soldier rank"] = $team->soldier[$i]->rank;				
                $response[$i]["soldier state"] = $team->soldier[$i]->state;				
            }
        }

        return $response;
    }
    public function updateLeader(Request $request){
        $response = "";
        
        $data = $request->getContent();

		$data = json_decode($data);
        $team = Team::find($data->team);
        
        if($data&&$team){

            $team->leader_id = $data->soldier;
            try{
                $team->save();
                $response = "Leader updated";
            }catch(\Exception $e){
                $response = $e->getMessage();
            }

        }else{
            $response = "Leader doesn't exist";
        }        
        return $response;
    }
    public function removeTeamMember($id){

        $response;

        $soldier = Soldier::find($id);
        $team = Team::find($soldier->team_id);

        if ($soldier) {

            if($team->leader_id === $soldier->id){
                $team->leader_id = null;
                try{
                    $team->save();
                }catch(\Exception $e){
                    $response = $e->getMessage();
                }
            }
            $soldier->team_id = null;
            try{
                $soldier->save();
                $response = "soldier deleted from team";
            }catch(\Exception $e){
                $response = $e->getMessage();
            }

        }else{
            $response = "No valid soldier";
        }
        return $response;
    }
}
