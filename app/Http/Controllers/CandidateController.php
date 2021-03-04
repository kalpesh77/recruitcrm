<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
//use Illuminate\Database\Eloquent\Model;


class CandidateController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function index(Request $request)
    {

        if (!$request->bearerToken() || !$request->secure()) {
            return response()->json(['error' => 'Invalid request'], 500);
        }
        try {
            $limit = !empty($request->input('limit')) ? $request->input('limit') :  10;
            $candidate = Candidate::simplePaginate($limit)->toArray();
            $data = $candidate['data'];
            unset($candidate['data']);
            $result = $candidate;
            $result['data'] = $data;
            
            return (response()->json($result));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid request'], 500);
        }
    }

    public function create(Request $request)
    {

        if (!$request->bearerToken() || !$request->secure()) {
            return response()->json(['error' => 'Invalid request'], 500);
        }
        try {
            $data = $request->all();
            $validator = Validator::make($data, [
                'first_name' => 'required|max:40',
                'last_name' => 'max:40',
                'email' => 'email|max:100',
                'contact_number' => 'max:100',
                'gender' => 'in:1,2',
                'specialization' => 'max:200',
                'work_ex_year' => 'integer|max:30',
                'candidate_dob' => 'nullable|date',
                'address' => 'max:500',
                'resume' => 'max:10000|mimes:doc,docx,pdf'
            ]);

            if ($validator->fails()) {
                return response(['error' => $validator->errors(), 'Validation Error']);
            }

            if($files=$request->file('resume')){  
                $name=$files->getClientOriginalName();  
                $files->move('resume',$name);  
                $data['resume']=$name;  
            }  

            Candidate::create($data);

            return response(['message' => 'Created successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid request'], 500);
        }
    }

    public function search(Request $request)
    {
        if (!$request->bearerToken() || !$request->secure()) {
            return response()->json(['error' => 'Invalid request'], 500);
        }
        try {
            $condition = [];
            if (!empty($request->input('first_name'))) {
                $condition[] = ['first_name', 'like', '%' . $request->input('first_name') . '%'];
            }
            if (!empty($request->input('last_name'))) {
                $condition[] = ['last_name', 'like', '%' . $request->input('last_name') . '%'];
            }
            if (!empty($request->input('email'))) {
                $condition[] = ['email', 'like', '%' . $request->input('email') . '%'];
            }
            $limit = !empty($request->input('limit')) ? $request->input('limit') :  10;
            $candidate = Candidate::where($condition)->simplePaginate($limit)->toArray();
            $data = $candidate['data'];
            unset($candidate['data']);
            $result = $candidate;
            $result['data'] = $data;
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid request'], 500);
        }
    }

    public function show($id, Request $request)
    {
        if (!$request->bearerToken() || !$request->secure()) {
            return response()->json(['error' => 'Invalid request'], 500);
        }
        try {
            $candidate = Candidate::find($id);

            return response()->json($candidate);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid request'], 500);
        }
    }
}
