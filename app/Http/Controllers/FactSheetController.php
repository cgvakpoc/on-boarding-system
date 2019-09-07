<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use Auth;
use Validator;
use DB;
use App\FactSheet\FactSheet;
use App\FactSheet\Education;
use App\FactSheet\HighSchool;
use App\FactSheet\JoineeCertification;
use App\FactSheet\JoineeExperience;
use App\FactSheet\JoineeJobDetails;
use App\FactSheet\JoineeRemuneration;
use App\FactSheet\JoineeSibling;
use App\FactSheet\JoineeSoftwareRating;
use App\FactSheet\JoineeVisa;


class FactSheetController extends Controller
{
	protected $user;
 	
 	private $insert_id;

    public function __construct(Request $request)
    {
        if(!isset($request->token)){
            return response()->json(['success' => false]);
        }
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    protected $validationRules = [
        'position_applied'      =>  'required',
        'candidate_name'        =>  'required|string|min:3|max:255',
        'candidate_age'         =>  'required|numeric',
        'candidate_dob'         =>  'required',
        'candidate_town'        =>  'required',
        'candidate_state'       =>  'required',
        'candidate_father_name' =>  'required',
        'father_occupation'		=>  'required',
        'marital_status'        =>  'required',
        'candidate_religion'    =>  'required',
        'candidate_address'     =>  'required',
        'candidate_mobile'      =>  'required|numeric|unique:fact_sheet,mobile',
        'candidate_email'       =>  'required|unique:fact_sheet,email',
        'languages'             =>  'required',
        'candidate_education'   =>  'required',
        'maths_10_marks'        =>  'required',
        'maths_12_marks'        =>  'required',
        'software_rating'       =>  'required',
        'ambition'              =>  'required',
        'passport_available'    =>  'required'
    ];

    public function last_id(){
    	$this->insert_id = DB::table('fact_sheet')->latest()->first();
    	return $this->insert_id->id;
    }

    public function add_factsheet($request){
        $dateofbirth = convert_date($request->candidate_dob);
        FactSheet::updateOrCreate([
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
        	'languages'			=>	serialize($request->languages)
        ]);
    }

    public function add_education($request){
        $id = $this->last_id();
        $education = $request->education;
        foreach ($education as $key => $value) {
            Education::updateOrCreate([
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
        HighSchool::updateOrCreate([
        	'joinee_id'			=> $id,
        	'maths_marks_10'	=> $request->maths_10_marks,
        	'maths_marks_12'	=> $request->maths_12_marks
        ]);
    }

    public function add_siblings($request){
        $id = $this->last_id();
        $joinee_sibling = $request->siblings;
        foreach($joinee_sibling as $key => $value) {
            JoineeSibling::updateOrCreate([
               'joinee_id'      => $insert_id->id,
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
            JoineeCertification::updateOrCreate([
                'joinee_id'             =>  $id,
                'certification_name'    =>  $value['certification_name'],
                'completion_year'       =>  $value['completion_year']
            ]);
        }
    }

    public function add_rating($request){
    	$delete_row = JoineeSoftwareRating::where('joinee_id',$request->id)->delete();
    	if($delete_row == true){
    		echo "records deleted";
    	}
    	
        $id = $this->last_id();
        $ratings = $request->ratings;
        foreach ($ratings as $key => $value) {
            JoineeSoftwareRating::updateOrCreate([
                'joinee_id'         =>  $id,
                'software_subject'  =>  $value['subject'],
                'software_rating'   =>  $value['rating']
            ]);
        }
    }

    public function add_experience($request){
        $id = $this->last_id();
        $experience = $request->experience;
        foreach ($experience as $key => $value) {
            $date_from = convert_date($value['work_from']);
            $date_to = convert_date($value['work_to']);
            JoineeExperience::updateOrCreate([
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

    public function add_remuneration($request){
        $id = $this->last_id();
        JoineeRemuneration::updateOrCreate([
            'joinee_id'         =>  $id,
            'take_home_sal'     =>  $request->salary,
            'deductions'        =>  $request->deductions,
            'monthly_ctc'       =>  $request->monthly_ctc,
            'yearly_ctc'        =>  $request->yearly_ctc,
            'others'            =>  $request->others
        ]);
    }

    public function add_job_details($request){
        $job_details = new JoineeJobDetails();
        $id = $this->last_id();
        JoineeJobDetails::updateOrCreate([
        	'joinee_id' 		=> $id,
        	'responsibilities'	=> $request->responsibilities,
        	'achievements'		=> $request->achievements,
        	'ambition'			=> $request->ambition,
        	'activities'		=> serialize($request->activities),
        	'passport'			=> $request->passport
        ]);
    }

    public function add_visadetails($request){
        $id = $this->last_id();
        JoineeVisa::updateOrCreate([
        	'joinee_id'		=> $id,
        	'visa_applied'	=> $request->visa_applied,
        	'reject_reason' => $request->reason
        ]);
    }

    public function update_factsheet($id,$request){
        //echo $id;die;
        $factsheet = new factsheet();
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
    			  ->select('t1.name','t1.pos_applied as position','t1.email','t1.phonenumber','t1.mobile','t1.age','t1.dob','t1.address','t2.town','t3.state','t1.father_name','t1.father_occupation','t4.status_name as marital_status','t1.spouse_name','t1.spouse_occupation','t1.religion','t1.languages')
        		  ->join('towns as t2','t1.town','t2.id')
        		  ->join('states as t3','t1.state','t3.id')
        		  ->join('status as t4','t1.marital_status','t4.id')
        		  ->where('t1.id',$id)
        		  ->get();
       	$details[0]->languages = unserialize($details[0]->languages);
       	return $details;
    }

    public function show_jobDetails($id){
    	$job = DB::table('job_details as t1')
    			  ->select('t1.responsibilities','t1.achievements','t1.ambition','t1.activities','t2.status_name as passport')
        		  ->join('status as t2','t1.passport','t2.id')
        		  ->join('fact_sheet as t3','t1.joinee_id','t3.id')
        		  ->where('t3.id',$id)
        		  ->get();
       	$job[0]->activities = unserialize($job[0]->activities);
       	return $job;
    }

    public function show_visa($id){
    	$visa = DB::table('visa_details as t1')
    			  ->select('t3.status_name as applied','t1.reject_reason as reason')
    			  ->join('status as t3','t1.visa_applied','t3.id')
        		  ->join('fact_sheet as t2','t1.joinee_id','t2.id')
        		  ->where('t2.id',$id)
        		  ->get();
       	return $visa;
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
            $this->add_siblings($request);
            $this->add_education($request);
            $this->add_highschool($request);
            $this->add_certifications($request);
            $this->add_rating($request);
            $this->add_experience($request);
            $this->add_remuneration($request);
            $this->add_job_details($request);
            $this->add_visadetails($request);
        }
        catch(ValidationException $e){
            DB::rollback();
            $err_msg = "Cannot insert the data";
            error_404(false,$err_msg);
            die;
        }
        DB::commit();

        $last_id = $this->last_id();
        $rating = $this->show_rating($last_id);
        $details = $this->show_details($last_id);
        $visa = $this->show_visa($last_id);
        $job = $this->show_jobDetails($last_id);

        $education = FactSheet::find($last_id)->education()->get();
        $siblings = FactSheet::find($last_id)->siblings()->get();
        $experience = FactSheet::find($last_id)->experience()->get();
        $certification = FactSheet::find($last_id)->certification()->get();
        $renum = FactSheet::find($last_id)->renumeration()->get();

        $responseData['basic_details'] = $details;
        $responseData['siblings'] = $siblings;
        $responseData['education'] = $education;
        $responseData['certification'] = $certification;
        $responseData['rating'] = $rating;
        $responseData['experience'] = $experience;
        $responseData['renumeration'] = $renum;
        $responseData['others'] = $job;
        $responseData['visa'] = $visa;
        
        $message = 'Data saved successfully';
        success_200(true,$message,$responseData);
    }

    public function show($id)
    {	
        $fact_sheet = FactSheet::find($id);

    	if(count($fact_sheet) === 0){
            $err_msg = 'Data not found';
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

        $responseData['basic_details'] = $details;
        $responseData['siblings'] = $siblings;
        $responseData['education'] = $education;
        $responseData['certification'] = $certification;
        $responseData['rating'] = $rating;
        $responseData['experience'] = $experience;
        $responseData['renumeration'] = $renum;
        $responseData['others'] = $job;
        $responseData['visa'] = $visa;
       
    	success_200(true,$responseData);
    }

    public function update($id,Request $request)
    {   
        $this->validationRules['candidate_email'] = 'required|unique:fact_sheet,email,'.$request->id.',id';
        $this->validationRules['candidate_phone'] = 'numeric|unique:fact_sheet,phonenumber,'.$request->id.',id';
        $this->validationRules['candidate_mobile'] = 'required|numeric|unique:fact_sheet,mobile,'.$request->id.',id';
    	$validator = Validator::make($request->all(),$this->validationRules);

        if($validator->fails()){
            return response()->json($validator->errors(),404);
        }

        //$this->add_highschool($request);
        //$this->add_education($request);
        //$this->add_certifications($request);
        //$this->add_rating($request);
        //$this->add_experience($request);
        //$this->add_remuneration($request);
        $this->add_job_details($request);
        //$this->add_visadetails($request);
    }
}
