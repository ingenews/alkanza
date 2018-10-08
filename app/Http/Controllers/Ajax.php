<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Ajax extends Controller
{
    

	public function centro(Request $request){
		switch ($request->input('oper')) {
			case 'insert':
				$datos=[
					'nombre'=>$request->input('nombre'),
					'lat'=>$request->input('lat'),
					'lon'=>$request->input('lon')
				];
				$id_usuario = DB::table('centro')->insertGetId($datos);
			break;
			case 'select':
				$json=[];
				$centros = DB::table('centro')->get();
				foreach ($centros as $centro) 
					array_push($json, array("id_centro"=>$centro->id_centro, "nombre"=>$centro->nombre, "lat"=>$centro->lat, "lon"=>$centro->lon, "balanceado"=>$centro->balanceado));
				echo json_encode($json);
			break;
			case 'delete':
				DB::table('centro')->where('id_centro', '=', $request->input('id_centro'))->delete();
			break;

			case 'balance':
				$balance=$request->input('balance');
				if ($balance==0)
					$balance=1;
				else
					$balance=0;
				DB::table('centro')->where('id_centro', '=', $request->input('id_centro'))->update(['balanceado' => $balance]);
			break;
			
			case 'guarda':
				$datos=[
					'nombre'=>$request->input('nombre'),
					'lat'=>$request->input('lat'),
					'lon'=>$request->input('lon'),
					'radio'=>$request->input('radio'),
					'valor'=>$request->input('valor')
				];
				$id_calculo = DB::table('calculo')->insertGetId($datos);
			break;

			case 'calculos':
				$json=[];
				$calculos = DB::table('calculo')->get();
				foreach ($calculos as $calculo) 
					array_push($json, array("id_calculo"=>$calculo->id_calculo, "nombre"=>$calculo->nombre, "lat"=>$calculo->lat, "lon"=>$calculo->lon, "radio"=>$calculo->radio, "valor"=>$calculo->valor));
				echo json_encode($json);
			break;

			case 'deleteCal':
				DB::table('calculo')->where('id_calculo', '=', $request->input('id_calculo'))->delete();
			break;
			
		}
	}

}
