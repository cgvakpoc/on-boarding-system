<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use Auth;
use Validator;
use DB;
use App\FactSheet\FactSheet;
use App\FactSheet\Language;
use App\FactSheet\Education;
use App\FactSheet\HighSchool;
use App\FactSheet\JoineeCertification;
use App\FactSheet\JoineeExperience;
use App\FactSheet\JoineeJobDetails;
use App\FactSheet\JoineeRemuneration;
use App\FactSheet\JoineeSibling;
use App\FactSheet\JoineeSoftwareRating;
use App\FactSheet\JoineeVisa;
use App\FactSheet\Activity;

class FactSheetController extends Controller
{
	protected $user;
 	
 	protected $insert_id;

    public function __construct(Request $request)
    {
        if(!isset($request->token)){
            return response()->json(['success' => false]);
        }
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    protected $validationRules = [
        'position_applied'      	=>  'required',
        'candidate_name'        	=>  'required|string|min:3|max:255',
        'candidate_age'         	=>  'required|numeric',
        'candidate_dob'         	=>  'required',
        'candidate_town'        	=>  'required',
        'candidate_state'       	=>  'required',
        'candidate_father_name' 	=>  'required',
        'father_occupation'			=>  'required',
        'marital_status'        	=>  'required',
        'candidate_religion'    	=>  'required',
        'candidate_address'     	=>  'required',
        'candidate_mobile'      	=>  'required|numeric|digits_between:10,12|unique:fact_sheet,mobile',
        'phone'						=>	'numeric|digits_between:6,10|unique:fact_sheet,phonenumber',
        'candidate_email'       	=>  'required|unique:fact_sheet,email',
        'languages'             	=>  'required',
        'education'             	=>  'required',
        'maths_10_marks'        	=>  'required',
        'maths_12_marks'        	=>  'required',
        'ratings'               	=>  'required',
        'ambition'                  =>  'required',
        'passport'                  =>  'required'
    ];

    public function last_id(){
    	$this->insert_id = DB::table('fact_sheet')->latest()->first();
    	return $this->insert_id->id;
    }

    public function add_factsheet($request){
    	$form_state = 1;
        if($request->form_save === 'submit'){
        	$form_state = 0;
        }
        
        $dateofbirth = convert_date($request->candidate_dob);
        FactSheet::Create([
        	'name'				=>	$request->candidate_name,
        	'pos_applied'		=>	$request->position_applied,
        	'email'				=>	$request->candidate_email,
        	'phonenumber'		=>	$request->phone,
        	'mobile'			=>	$request->candidate_mobile,
        	'age'				=>	$request->candidate_age,
        	'dob'				=>	$dateofbirth,
        	'address'			=>	$request->candidate_address,
        	'town'				=>	$request->candidate_town,
        	'state'				=>	$request->candidate_state,
        	'father_name'		=>	$request->candidate_father_name,
        	'father_occupation'	=>	$request->father_occupation,
        	'marital_status'	=>	$request->marital_status,
        	'spouse_name'		=>	$request->spouse_name,
        	'spouse_occupation'	=>	$request->spouse_occupation,
        	'religion'			=>	$request->candidate_religion,
        	'edit_state'		=>	$form_state
        ]);
    }

    public function add_languages($request){
    	$id = $this->last_id();
    	$languages = $request->languages;
    	foreach($languages as $key => $value){
    		Language::Create([
    			'joinee_id' => $id,
    			'language'	=> $value['language']
    		]);
    	}
    }

    public function add_education($request){
        $id = $this->last_id();
        $education = $request->education;
        foreach($education as $key => $value) {
            Education::Create([
                'joinee_id'         =>  $id,
                'from'              =>  $value['from'],
                'to'                =>  $value['to'],
                'qualification'     =>  $value['qualification'],
                'course_name'       =>  $value['course_name'],
                'institution_name'  =>  $value['institution'],
                'medium'            =>  $value['medium'],
                'percentage'        =>  $value['percentage'],
                'arrears'           =>  $value['arrears'],
                'class_obtained'    =>  $value['class_obtained']
            ]);
        }
    }

    public function add_highschool($request){
        $id = $this->last_id();
        HighSchool::Create([
        	'joinee_id'			=> $id,
        	'maths_marks_10'	=> $request->maths_10_marks,
        	'maths_marks_12'	=> $request->maths_12_marks
        ]);
    }

    public function add_siblings($request){
        $id = $this->last_id();
        $joinee_sibling = $request->siblings;
        foreach($joinee_sibling as $key => $value) {
            JoineeSibling::Create([
               'joinee_id'      => $id,
               'sibling_name'   => $value['sibling_name'],
               'course'         => $value['course'],
               'institution'    => $value['institution']
            ]);
        }
    }

    public function add_certifications($request){
        $id = $this->last_id();
        $certifications = $request->certifications;
        foreach($certifications as $key => $value){
            JoineeCertification::Create([
                'joinee_id'             =>  $id,
                'certification_name'    =>  $value['certification_name'],
                'completion_year'       =>  $value['completion_year']
            ]);
        }
    }

    public function add_rating($request){
        $id = $this->last_id();
        $ratings = $request->ratings;
        foreach ($ratings as $key => $value) {
            JoineeSoftwareRating::Create([
                'joinee_id'         =>  $id,
                'software_subject'  =>  $value['subject'],
                'software_rating'   =>  $value['rating']
            ]);
        }
    }

    public function add_experience($request){
        $id = $this->last_id();
        $experience = $request->experience;
        if($experience != ''){
            foreach ($experience as $key => $value) {
                $date_from = convert_date($value['work_from']);
                $date_to = convert_date($value['work_to']);
                JoineeExperience::Create([
                    'joinee_id'         =>  $id,
                    'from'              =>  $date_from,
                    'to'                =>  $date_to,
                    'total_exp'         =>  $value['total_exp'],
                    'designation'       =>  $value['designation'],
                    'organisation'      =>  $value['organisation'],
                    'location'          =>  $value['location'],
                    'reason_to_leave'   =>  $value['reason_to_leave']
                ]);
            }
        }
    }

    public function add_activity($request){
    	$id = $this->last_id();
        $activities = $request->activities;
        if($activities != ''){
            foreach($activities as $key => $value){
                Activity::Create([
                    'joinee_id' =>  $id,
                    'activity'  =>  $value['activity']
                ]);
            }
        }
    }

    public function add_remuneration($request){
        $id = $this->last_id();
        JoineeRemuneration::Create([
            'joinee_id'         =>  $id,
            'take_home_sal'     =>  $request->salary,
            'deductions'        =>  $request->deductions,
            'monthly_ctc'       =>  $request->monthly_ctc,
            'yearly_ctc'        =>  $request->yearly_ctc,
            'others'            =>  $request->others
        ]);
    }

    public function add_job_details($request){
        $id = $this->last_id();
        JoineeJobDetails::Create([
        	'joinee_id' 		=> $id,
        	'responsibilities'	=> $request->responsibilities,
        	'achievements'		=> $request->achievements,
        	'ambition'			=> $request->ambition,
        	'passport'			=> $request->passport
        ]);
    }

    public function add_visadetails($request){
        $id = $this->last_id();
        JoineeVisa::Create([
        	'joinee_id'		=> $id,
        	'visa_applied'	=> $request->visa_applied,
        	'reject_reason' => $request->reason
        ]);
    }

    public function show_rating($id){
    	$rating = DB::table('joinee_software_rating as t1')
    			  ->select('t1.software_subject as subject','t2.rating_name as rating')
        		  ->join('proficiency_rating as t2','t1.software_rating','=','t2.id')
        		  ->where('t1.joinee_id',$id)
        		  ->get();
       	return $rating;
    }

    public function show_details($id){
    	$details = DB::table('fact_sheet as t1')
    			  ->select('t1.name','t1.pos_applied as position','t1.email','t1.phonenumber','t1.mobile','t1.age','t1.dob','t1.address','t2.town','t3.state','t1.father_name','t1.father_occupation','t4.status_name as marital_status','t1.spouse_name','t1.spouse_occupation','t1.religion')
        		  ->join('towns as t2','t1.town','t2.id')
        		  ->join('states as t3','t1.state','t3.id')
        		  ->join('status as t4','t1.marital_status','t4.id')
        		  ->where('t1.id',$id)
        		  ->get();
       	return $details;
    }

    public function show_jobDetails($id){
    	$job = DB::table('job_details as t1')
    			  ->select('t1.responsibilities','t1.achievements','t1.ambition','t2.status_name as passport')
        		  ->join('status as t2','t1.passport','t2.id')
        		  ->join('fact_sheet as t3','t1.joinee_id','t3.id')
        		  ->where('t3.id',$id)
        		  ->get();
       	return $job;
    }

    public function show_visa($id){
    	$visa = DB::table('visa_details as t1')
    			  ->select('t2.status_name as applied','t1.reject_reason as reason')
    			  ->join('status as t2','t1.visa_applied','t2.id')
        		  ->join('fact_sheet as t3','t1.joinee_id','t3.id')
        		  ->where('t3.id',$id)
        		  ->get();
       	return $visa;
    }

    public function update_factsheet($request){
    	$id = $request->id;
    	$dateofbirth = convert_date($request->candidate_dob);
    	$form_state = 1;
    	if($request->form_save === 'submit'){
    		$form_state = 0;
    	}
    	
    	$update_fact = FactSheet::where('id',$id)->update([
    		'name'				=>	$request->candidate_name,
        	'pos_applied'		=>	$request->position_applied,
        	'email'				=>	$request->candidate_email,
        	'phonenumber'		=>	$request->phone,
        	'mobile'			=>	$request->candidate_mobile,
        	'age'				=>	$request->candidate_age,
        	'dob'				=>	$dateofbirth,
        	'address'			=>	$request->candidate_address,
        	'town'				=>	$request->candidate_town,
        	'state'				=>	$request->candidate_state,
        	'father_name'		=>	$request->candidate_father_name,
        	'father_occupation'	=>	$request->father_occupation,
        	'marital_status'	=>	$request->marital_status,
        	'spouse_name'		=>	$request->spouse_name,
        	'spouse_occupation'	=>	$request->spouse_occupation,
        	'religion'			=>	$request->candidate_religion,
        	'edit_state'		=>	$form_state
    	]);
    }

    public function update_languages($request){
        $id = $request->id;
        $languages = $request->languages;
        if($languages != ''){
            Language::where('joinee_id',$id)->delete();
            foreach($languages as $key => $value){
                Language::Create([
                    'joinee_id' => $id,
                    'language'  => $value['language']
                ]);
            }
        }
    }

    public function update_education($request){
    	$id = $request->id;
    	$education = $request->education;
        if($education != ''){
            Education::where('joinee_id',$id)->delete();
            foreach($education as $key => $value){
                Education::Create([
                    'joinee_id'         =>  $id,
                    'from'              =>  $value['from'],
                    'to'                =>  $value['to'],
                    'qualification'     =>  $value['qualification'],
                    'course_name'       =>  $value['course_name'],
                    'institution_name'  =>  $value['institution'],
                    'medium'            =>  $value['medium'],
                    'percentage'        =>  $value['percentage'],
                    'arrears'           =>  $value['arrears'],
                    'class_obtained'    =>  $value['class_obtained']
                ]);
            }
        }
    }

    public function update_siblings($request){
    	$id = $request->id;
    	$siblings = $request->siblings;
    	if($siblings != ''){
    		JoineeSibling::where('joinee_id',$id)->delete();
	    	foreach($siblings as $key => $value){
	    		JoineeSibling::Create([
	    			'joinee_id'      => $id,
		            'sibling_name'   => $value['sibling_name'],
		            'course'         => $value['course'],
		            'institution'    => $value['institution']
	    		]);
	    	}
    	}
    }

    public function update_certification($request){
    	$id = $request->id;
    	$certifications = $request->certifications;
    	if($certifications != ''){
    		JoineeCertification::where('joinee_id',$id)->delete();
	    	foreach($certifications as $key => $value){
	    		JoineeCertification::Create([
	    			'joinee_id'             =>  $id,
	                'certification_name'    =>  $value['certification_name'],
	                'completion_year'       =>  $value['completion_year']
	    		]);
	    	}	
    	}
    }

    public function update_highschool($request){
    	$id = $request->id;
    	HighSchool::where('joinee_id',$id)->delete();
		HighSchool::Create([
			'joinee_id'         => $id,
            'maths_marks_10'    => $request->maths_10_marks,
            'maths_marks_12'    => $request->maths_12_marks
		]);
    }

    public function update_ratings($request){
    	$id = $request->id;
        $ratings = $request->ratings;
    	JoineeSoftwareRating::where('joinee_id',$id)->delete();
        foreach ($ratings as $key => $value) {
            JoineeSoftwareRating::Create([
                'joinee_id'         =>  $id,
                'software_subject'  =>  $value['subject'],
                'software_rating'   =>  $value['rating']
            ]);
        }
    }

    public function update_experience($request){
    	$id = $request->id;
        $experience = $request->experience;
        if($experience != ''){
            JoineeExperience::where('joinee_id',$id)->delete();
            foreach ($experience as $key => $value) {
                $date_from = convert_date($value['work_from']);
                $date_to = convert_date($value['work_to']);
                JoineeExperience::Create([
                    'joinee_id'         =>  $id,
                    'from'              =>  $date_from,
                    'to'                =>  $date_to,
                    'total_exp'         =>  $value['total_exp'],
                    'designation'       =>  $value['designation'],
                    'organisation'      =>  $value['organisation'],
                    'location'          =>  $value['location'],
                    'reason_to_leave'   =>  $value['reason_to_leave']
                ]);
            }
        }
    }

    public function update_remuneration($request){
    	$id = $request->id;
        JoineeRemuneration::where('joinee_id',$id)->delete();
        JoineeRemuneration::Create([
            'joinee_id'         =>  $id,
            'take_home_sal'     =>  $request->salary,
            'deductions'        =>  $request->deductions,
            'monthly_ctc'       =>  $request->monthly_ctc,
            'yearly_ctc'        =>  $request->yearly_ctc,
            'others'            =>  $request->others
        ]);    
    }

    public function update_job_details($request){
    	$id = $request->id;
        JoineeJobDetails::where('joinee_id',$id)->delete();
        JoineeJobDetails::Create([
        	'joinee_id' 		=> $id,
        	'responsibilities'	=> $request->responsibilities,
        	'achievements'		=> $request->achievements,
        	'ambition'			=> $request->ambition,
        	'passport'			=> $request->passport
        ]);
    }

    public function update_activity($request){
        $id = $request->id;
        $activities = $request->activities;
        if($activities != ''){
            Activity::where('joinee_id',$id)->delete();
            foreach($activities as $key => $value){
                Activity::Create([
                    'joinee_id' =>  $id,
                    'activity'  =>  $value['activity']
                ]);
            }
        }
    }

    public function update_visa($request){
        $id = $request->id;
        JoineeVisa::where('joinee_id',$id)->delete();
        JoineeVisa::Create([
        	'joinee_id'		=> $id,
        	'visa_applied'	=> $request->visa_applied,
        	'reject_reason' => $request->reason
        ]);
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(),$this->validationRules);
        if($validator->fails()){
            return response()->json($validator->errors(),404);
        }
        DB::beginTransaction();
        try{
            $this->add_factsheet($request);
            $this->add_languages($request);
            $this->add_siblings($request);
            $this->add_education($request);
            $this->add_highschool($request);
            $this->add_certifications($request);
            $this->add_rating($request);
            $this->add_experience($request);
            $this->add_remuneration($request);
            $this->add_job_details($request);
            $this->add_activity($request);
            $this->add_visadetails($request);
        }
        DB::commit();
        catch(\Exception $e){
            DB::rollback();
            error_404(false,$e);
            die;
        }
        
        $last_id = $this->last_id();
        // Get records of last inserted candidate
        // Data retrieved from multi-tables(using foreign keys)
        $rating = $this->show_rating($last_id);
        $details = $this->show_details($last_id);
        $visa = $this->show_visa($last_id);
        $job = $this->show_jobDetails($last_id);
        // Data retrieved using Model Relationships
        $education = FactSheet::find($last_id)->education()->get();
        $siblings = FactSheet::find($last_id)->siblings()->get();
        $experience = FactSheet::find($last_id)->experience()->get();
        $certification = FactSheet::find($last_id)->certification()->get();
        $renum = FactSheet::find($last_id)->renumeration()->get();
        $languages = FactSheet::find($last_id)->languages()->get();
        $activities = FactSheet::find($last_id)->activity()->get();

        $responseData['basic_details'] = $details;
        $responseData['language'] = $languages;
        $responseData['siblings'] = $siblings;
        $responseData['education'] = $education;
        $responseData['certification'] = $certification;
        $responseData['rating'] = $rating;
        $responseData['experience'] = $experience;
        $responseData['renumeration'] = $renum;
        $responseData['others'] = $job;
        $responseData['activities'] = $activities;
        $responseData['visa'] = $visa; 
        $message = 'Data saved successfully';
        success_200(true,$message,$responseData);
    }

    public function show($id)
    {	
        $fact_sheet = FactSheet::find($id);
    	if(count($fact_sheet) === 0){
            $err_msg = 'Data not found for the id '.$id;
    		error_404(false,$err_msg); 
            die;   	
        }
        
        $rating = $this->show_rating($id);
        $details = $this->show_details($id);
        $visa = $this->show_visa($id);
        $job = $this->show_jobDetails($id);
        $education = FactSheet::find($id)->education()->get();
        $siblings = FactSheet::find($id)->siblings()->get();
        $experience = FactSheet::find($id)->experience()->get();
        $certification = FactSheet::find($id)->certification()->get();
        $renum = FactSheet::find($id)->renumeration()->get();
        $languages = FactSheet::find($id)->languages()->get();
        $activities = FactSheet::find($id)->activity()->get();

        $responseData['basic_details'] = $details;
        $responseData['languages'] = $languages;
        $responseData['siblings'] = $siblings;
        $responseData['education'] = $education;
        $responseData['certification'] = $certification;
        $responseData['rating'] = $rating;
        $responseData['experience'] = $experience;
        $responseData['renumeration'] = $renum;
        $responseData['others'] = $job;
        $responseData['activities'] = $activities;
        $responseData['visa'] = $visa;
    	success_200(true,$responseData);
    }

    public function update(Request $request)
    {  	
    	$id = $request->id;
        $candidate_id = FactSheet::find($id);
        if(count($candidate_id) === 0){
            $msg = "Details cannot found for id ".$id;
            error_404(false,$msg);
            die;
        }
        $this->validationRules['candidate_email'] = 'required|email|unique:fact_sheet,email,'.$id.',id';
        $this->validationRules['phone'] = 'numeric|digits_between:6,10|unique:fact_sheet,phonenumber,'.$id.',id';
        $this->validationRules['candidate_mobile'] = 'required|numeric|digits_between:10,12|unique:fact_sheet,mobile,'.$id.',id';
      
    	$validator = Validator::make($request->all(),$this->validationRules);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }
        DB::beginTransaction();
        try{
            $this->update_factsheet($request);
            $this->update_languages($request);
            $this->update_education($request);
            $this->update_highschool($request);
            $this->update_siblings($request);
            $this->update_certification($request);
            $this->update_ratings($request);
            $this->update_experience($request);
            $this->update_remuneration($request);
            $this->update_job_details($request);
            $this->update_activity($request);
            $this->update_visa($request);
        }
        catch(\Exception $e){
            DB::rollback();
            error_404(false,$e);
            die;
        }
        DB::commit();
        // Get records of updated candidate
        // Data retrieved from multi-tables(using foreign keys)
        $rating = $this->show_rating($id);
        $details = $this->show_details($id);
        $visa = $this->show_visa($id);
        $job = $this->show_jobDetails($id);
        // Data retrieved using Model Relationships
        $education = FactSheet::find($id)->education()->get();
        $siblings = FactSheet::find($id)->siblings()->get();
        $experience = FactSheet::find($id)->experience()->get();
        $certification = FactSheet::find($id)->certification()->get();
        $renum = FactSheet::find($id)->renumeration()->get();
        $activities = FactSheet::find($id)->activity()->get();

        $responseData['basic_details'] = $details;
        $responseData['siblings'] = $siblings;
        $responseData['education'] = $education;
        $responseData['certification'] = $certification;
        $responseData['rating'] = $rating;
        $responseData['experience'] = $experience;
        $responseData['renumeration'] = $renum;
        $responseData['others'] = $job;
        $responseData['activities'] = $activities;
        $responseData['visa'] = $visa;

        $message = 'Data updated successfully';
        success_200(true,$message,$responseData);
    }
}
