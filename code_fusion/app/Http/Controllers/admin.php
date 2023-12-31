<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\category;
use App\Models\course;
use App\Models\material;
use App\Models\quiz;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Session as SessionFacade;
use Illuminate\Support\Facades\URL as URLFacade;

class admin extends Controller
{
    
    public function view_category()
    {
        $datas=category::all();
        return view('admin.category',compact('datas'));
    }

    public function add_category(Request $request)
    { 
        
        $data=new category;
        if ($request->hasFile('file')) {
            $image = $request->file('file');
            $imagename = uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads'), $imagename);
            $data->image = $imagename;
        }
        $data->category_name=$request->category;
        $data->description=$request->desc;
        $data->save();
        if ($data->save()) {
            return redirect()->back()->with('message', 'Category added successfully');
        } else {
            return redirect()->back()->with('error','Failed to add category');
        }

    }

    public function delete_category($id)
    {
        $data = category::find($id);

            if ($data) 
            {
                 $data->delete();
                 return redirect()->back()->with('messagee', 'Category deleted successfully');
            } 
            else 
            {
                 return redirect()->back()->with('errore', 'Failed to delete category');
            }
    }

    public function edit_category($id)
    {
        $datas=category::find($id);
        return view('admin.category_edit',compact('datas'));
    }

    public function category_edit(Request $request ,$id)
    {
        $datas=category::find($id);
        $datas->category_name = $request->input('category_name');
        $datas->save();
        return redirect()->back()->with('message', 'Category Edited Successfully');
    }

    public function add_course()
    {
        $category=category::all();
        return view('admin.add_course',compact('category'));
        
    }

    public function upload_course(Request $request)
    {
      
        $data = new course;
       
    if ($request->hasFile('file')) {
        $image = $request->file('file');
        $imagename = uniqid() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('uploads'), $imagename);
        $data->image = $imagename;
    }
   
        $data->category = $request->input('category');
        $data->name = $request->input('name');
        $data->details = $request->input('details');
        $data->fees = $request->input('fees');
       
        $data->save();
        return redirect()->back()->with('message', 'Course Added Successfully');

    }

    public function course_view()
    {
        $category=course::all();
        return view('admin.course_view',compact('category'));
        
    }

    public function course_delete($id)
    {
        $data = course::find($id);

            if ($data) 
            {
                 $data->delete();
                 return redirect()->back()->with('messagee', 'Category deleted successfully');
            } 
            else 
            {
                 return redirect()->back()->with('errore', 'Failed to delete category');
            }
    }

    public function course_data($data)
    {
    
        $notes=  DB::table('materials')
        ->where('cid', $data)
        ->get();
        return view('admin.course_data',compact('data','notes'));
        
    }

    public function store(Request $request, $data)
    {
       
    
        $course = new material();
    
        if ($request->hasFile('file')) {
            $image = $request->file('file');
            $imagename = uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads'), $imagename);
            $course->image = $imagename;
        }
    
        $course->main_title = $request->input('main_title');
        $course->sub_title = $request->input('sub_title');
        $course->description = $request->input('description');
        $course->syntax = $request->input('syntax');
        $course->cid = $data; // Assuming $data contains the course ID
    
        $course->save();
    
        return redirect()->back()->with('message', 'Course Added Successfully');
    }

    

    public function add_quiz_slot($data)
    {
       
    
        $course = new material();
    

        $course->type = 'quiz';
    
        $course->cid = $data;
    
        $course->save();
    
        return redirect()->back()->with('message', 'Course Added Successfully');
    }

    

    public function add_questions($data,$id)
    {
       
        return view('admin.add_questions',compact('data','id'));
    }

    

    public function upload_quiz(Request $request, $data,$id)
    {
       
    
        $course = new quiz();
    
        if ($request->hasFile('file')) {
            $image = $request->file('file');
            $imagename = uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads'), $imagename);
            $course->image = $imagename;
        }
    
        $course->question = $request->input('question');
        $course->ans1 = $request->input('ans1');
        $course->ans2 = $request->input('ans2');
        $course->ans3 = $request->input('ans3');
        $course->ans4 = $request->input('ans4');
        $course->answer = $request->input('answer');
        $course->qid = $id;
        $course->cid = $data; 
    
        $course->save();
    
        return redirect()->back()->with('message', 'Quiz Added Successfully');
    }
    

    
}
