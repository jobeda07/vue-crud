<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Traits\Uploadable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    use Uploadable;

    public function index(Request $request){
        $data = $request->input('search');
        $query = Student::query();

        if ($data) {
            $query->where(function ($q) use ($data) {
                $q->where('name', 'LIKE', '%' . $data . '%')
                    ->orWhere('phone', 'LIKE', '%' . $data . '%')
                    ->orWhere('gpa', 'LIKE', '%' . $data . '%')
                    ->orWhere('email', 'LIKE', '%' . $data . '%');
            });
        }

        $students = $query->orderBy('id', 'desc')->paginate(5);

        $studentArray = $students->map(function($student) {
            return [
                "id" => $student->id,
                "name" => $student->name,
                "email" => $student->email,
                "phone" => $student->phone,
                "gpa" => $student->gpa,
                "address" => $student->address,
                "created_at" => $student->created_at,
                "image" => $student->image ? env('APP_URL') . '/' . $student->image : '',
            ];
        })->toArray(); // Convert the collection to an array

        return response()->json([
            'success' => true,
            'students' => $studentArray,
            'current_page' => $students->currentPage(),
            'last_page' => $students->lastPage(),
            'total' => $students->total(),
        ]);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'gpa' => 'required',
            'image' => 'nullable|image|mimes:png,jpg,jpeg',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }
        $student = Student::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'gpa' => $request->gpa,
        ]);
        if ($request->file('image')) {
            $image_path = $this->uploadOne($request->image, 400, 300, 'images/student/', true);
            $student->image = $image_path;
            $student->save();
        }
        return response([
            'student' => $student,
            'message' => 'Student Created successfully'
        ]);
    }

    public function edit($id) {
        $student=Student::find($id);
        if(!$student){
            return response()->json([
                'status'=>404,
                'message' => "No Student Found!"
                ],404);
        }
        $student= [
            "id" => $student->id,
            "name" => $student->name,
            "email" => $student->email,
            "phone" => $student->phone,
            "gpa" => $student->gpa,
            "address" => $student->address,
            "created_at" => $student->created_at,
            "image" => $student->image ? env('APP_URL') . '/' . $student->image : '',
        ];
        return response([
            'student' => $student,
        ]);
    }
    public function delete($id) {
        $student=Student::find($id);
        if(!$student){
            return response()->json([
                'status'=>404,
                'message' => "No Student Found!"
                ],404);
        }
        $student->delete();
        return response([
            'message' => 'Student Delete successfully'
        ]);
    }
    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'gpa' => 'required',
            'image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }
        $student=Student::find($id);
        if($student){
            $student->name = $request->name;
            $student->email = $request->email;
            $student->phone = $request->phone;
            $student->address = $request->address;
            $student->gpa = $request->gpa;
            if ($request->file('image')) {
                $image_path = $this->uploadOne($request->image, 400, 300, 'images/student/', true);
                $student->image = $image_path;
            }else{
                $student->image = $student->image;
            }
            $student->save();
            return response([
                'student' => $student,
                'message' => 'Student Update successfully'
            ]);
        }else{
            return response()->json([
                'status'=>404,
                'message' => "No Student Found!"
            ],404);
        }

    }

}
