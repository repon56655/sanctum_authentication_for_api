<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProjectModel;

class ProjectController extends Controller
{
    //create api
    public function createProject(Request $request){
        $request->validate([
            "name" => "required",
            "description" => "required",
            "duration" => "required"
        ]);

        $project = new ProjectModel();

        $student_id = auth()->user()->id;
        $project->student_id = $student_id;
        $project->name = $request->name;
        $project->description = $request->description;
        $project->duration = $request->duration;
        $save_project = $project->save();

        if($save_project){
            return response()->json([
                "status" => 1,
                "message" => "Successfully Project Created",
            ]);
        }
    }

    //list api
    public function listProject(){
        $student_id = auth()->user()->id;
        $project_list = ProjectModel::where("student_id", $student_id)->get();
        return response()->json([
            "message" => "1",
            "project_list" => $project_list,
            "status" => "list of project list"
        ]);
    }

    //single api
    public function singleProject($id){
        $project_check = ProjectModel::find($id);
        if($project_check){
            $student_id = auth()->user()->id;
            $project_list = ProjectModel::where('id', $id)
            ->where('student_id', $student_id)
            ->get();

            return response()->json([
                "status" => "1",
                "message" => "Project Found",
                "data" => $project_list
            ]);


        }
        else{
            return response()->json([
                "status" => "0",
                "message" => "No Project Found"
            ]);
        }




    }

    //delete api
    public function deleteProject($id){
        $project_check = ProjectModel::find($id);
        if($project_check){
            $student_id = auth()->user()->id;
            $project_list = ProjectModel::where('id', $id)
            ->where('student_id', $student_id)
            ->first();
            $delete_project = $project_list->delete();

            if($delete_project){
                return response()->json([
                    "status" => "1",
                    "message" => "Project Deleted Successfully",
                ]);
            }
            else{
                return response()->json([
                    "status" => "0",
                    "message" => "Something Wrong"
                ]);
            }

        }
        else{
            return response()->json([
                "status" => "0",
                "message" => "No Project Found"
            ]);
        }

    }
}
