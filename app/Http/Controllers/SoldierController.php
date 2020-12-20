<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Soldier;
use App\Models\Team;
         

class SoldierController extends Controller
{
	/**
	 * Crea un soldado
	 */
    public function createSoldier(Request $request){

        $data = $request->getContent();

        $data = json_decode($data);

        if ($data) {
    		$soldier = new Soldier();

    		$soldier->name = $data->name;
            $soldier->surname = $data->surname;
            $soldier->birth_date = $data->birth_date;
            $soldier->join_army_date = $data->join_army_date;
            $soldier->rank = $data->rank;
            $soldier->dog_tag_number = $data->dog_tag_number;
            $soldier->state = $data->state;
    		try{
    			$soldier->save();
    			$response = "Soldier Created";

    		}catch(\Exception $e){
    			$response = $e->getMessage();
    		}    		
    	}else{
            $response = "Invalid data"; 
        }

    	print_r($response);
    	die;
    }

	/**
	 * Actualiza los datos del soldado introducidos por request
	 */
    public function updateSoldier(Request $request, $id){

        $soldier = Soldier::find($id);
        $response="";

        if($soldier){
    		$data = $request->getContent();

	    	$data = json_decode($data);

	    	if ($data) {
		    		
		    			$soldier->name = (isset($data->name) ? $data->name: $soldier->name);
		    			$soldier->surname = (isset($data->surname) ? $data->surname: $soldier->surname);
		    			$soldier->birth_date = (isset($data->birth_date) ? $data->birth_date: $soldier->birth_date);
		    			$soldier->join_army_date = (isset($data->join_army_date) ? $data->join_army_date: $soldier->join_army_date);
		    			$soldier->rank = (isset($data->rank) ? $data->rank: $soldier->rank);
		    			$soldier->dog_tag_number = (isset($data->dog_tag_number) ? $data->dog_tag_number: $soldier->dog_tag_number);		    			
		    		try{
		    			$soldier->save();
		    			$response = "Soldier Updated";

		    		}catch(\Exception $e){
		    			$response = $e->getMessage();
		    			}	    		
	    	}else{
                $response = "No valid data";
            }    		
    		
    	}else{
    		$response = "No soldier";
    	}
    	
    	print_r($response);
    	die;
	}

	/**
	 * Actualiza el estado del soldado
	 */
	public function stateSoldier(Request $request, $id){

		$soldier = Soldier::find($id);
		$response="";		
		
		if($soldier){

			$data = $request->getContent();
			$data = json_decode($data);

			if($data){
				$soldier->state = $data->state;

				try{
					$soldier->save();
					$response = "Soldier state Updated";

				}catch(\Exception $e){
					$response = $e->getMessage();
					}	
			}else{
				$response="No valid data";
			}
			
		}else{			
			$response = "No soldier";
		}

		print_r($response);
    	die;		
	}

	/**
	 * Da la una lista de soldados.
	 */
	public function soldiersList(){

		$response = "";
		$soldiers = Soldier::all();
		$index = 0;

		$response= [];

		foreach ($soldiers as $soldier) {
			
			$response[] = [
				"name" => $soldier->name,
				"surname" => $soldier->surname,
				"rank" => $soldier->rank,
				"dog_tag_number" => $soldier->dog_tag_number,				
			];
			if($soldier->team){		

				$response[$index]["team_id"] = $soldier->team->id;
				$response[$index]["team_name"] = $soldier->team->name;										
			}else{
				$response[$index]["team"] = "No team assigned";
			}
			$index +=1;
		}
		
		return response()->json($response);
	}

	/**
	 * Da la información de un soldado.
	 */
	public function infoSoldier($id){

		$response;
		$soldier = Soldier::find($id);
		if($soldier->team){
			$leaderId = $soldier->team->leader_id;
			$leader = Soldier::find($leaderId);
		}
		
		$response=[

			"name" => $soldier->name,
			"surname" => $soldier->surname,
			"birth date" => $soldier->birth_date,
            "join army date" => $soldier->join_army_date,
            "rank" => $soldier->rank,
			"dog_tag_number" => $soldier->dog_tag_number
		];
		
		if($soldier->team){

			$response["team_id"] = $soldier->team->id;
			$response["team_name"] = $soldier->team->name;

			if($leader){

				$response["leader_id"] = $leader->id;
				$response["leader_surname"] = $leader->surname;
				$response["leader_rank"] = $leader->rank;
			}
		}else{
			$response["team"] = "No team assigned";
		}

		

		return response()->json($response);
	}

	/**
	 * Da el historial de misiones de un soldado
	 */
	public function soldierHistorial($id){
		$response=[];
		$soldier = Soldier::find($id);

		if ($soldier) {

			for ($i=0; $i < count($soldier->mission); $i++) { 
				$response[$i]["mission id"] = $soldier->mission[$i]->id;				
				$response[$i]["mission description"] = $soldier->mission[$i]->description;				
				$response[$i]["mission register_date"] = $soldier->mission[$i]->register_date;				
				$response[$i]["mission state"] = $soldier->mission[$i]->state;				
			}
			
		}

		return response()->json($response);
	}
}
