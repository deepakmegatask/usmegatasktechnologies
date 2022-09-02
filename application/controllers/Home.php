<?php
 defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/kolkata');

class Home extends CI_Controller {


	public function __construct()
    {
        parent::__construct();
         $this->load->library('form_validation');
         $this->load->helper('email');
         $this->load->model('lead_form_model');
         $this->load->model('user_contact_model');
         
     }



	public function index()
	{	

		$data["file"]="home";
		$data["title"]="Home";
		$this->load->view('include/template',$data);

	}



	

	public function thank_you()
	{	

		$data["file"]="thankyou";
		$data["title"]="Thank you";
		$this->load->view('include/template',$data);

	}



	public function add_leads()
    {              
        $this->form_validation->set_rules('f_name','First Name','required');
        $this->form_validation->set_rules('l_name','Last Name','required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('mob','Mobile','required');
        $this->form_validation->set_rules('budget','Budget','required');
        $this->form_validation->set_rules('category','Select Category','required'); 
            $form_data  = $this->input->post();

            if($this->form_validation->run() == FALSE)
            { 
                $response = array(
                'status' => 'error',
                'firstname_err' => form_error('f_name'),
                'lastname_err' => form_error('l_name'),
                'email_err' => form_error('email'),
                'mobile_err' => form_error('mob'),
                'budget_err' => form_error('budget'),
                'category_err' => form_error('category')
                );  
            }
            else
            {      

                 
                    $insertData = array();
                    $insertData['first_name']  = $form_data['f_name'];
                    $insertData['last_name']  = $form_data['l_name'];
                    $insertData['email']    = $form_data['email'];
                    $insertData['mob_no']  = $form_data['mob'];
                    $insertData['budget']    = $form_data['budget'];
                    $insertData['category']  = $form_data['category'];
                    $insertData['date_at'] = date("Y-m-d H:i:s");
                    $result = $this->lead_form_model->save($insertData);


                    $html_data = "Name : ".  $form_data['f_name']." ". $form_data['l_name']."\n";
                    $html_data .= "Email : ".  $form_data['email']."\n";
                    $html_data .= "Phone : ".  $form_data['mob']."\n";
                    $html_data .= "Budget : ".  $form_data['budget']."\n";
                    $html_data .= "Web Category : ".  $form_data['category']."\n";
                         
                    $subject_name = "New Lead Arrival Of ".$form_data['f_name'].' '.$form_data['l_name'];

                    $description =$html_data;

                    $this->load->library('email');
                    $config['mailtype'] = 'html';
                    $this->email->initialize($config);
                    $toemail = 'abbasdigitalmarket@gmail.com,leads@megatasktechnologies.com';
                    $this->email->from('info@megataskweb.com', 'New Lead');
                    $this->email->to($toemail);

                    $this->email->subject($subject_name);
                    $this->email->message( $description );

                    $resulst = $this->email->send();
                
                    

                    if($result > 0)
                    {   

                       
                         $response = array(
                            'status' => 'success',
                            'message' => "<h5 style='color:green;'>Thank You For Contacting Us....</h5>"
                            
                        );
                        
                         
                    }
                    else
                    { 
                        $response = array(
                              'status' => 'error',
                            'message' => "<h5 style='color:#1b561b;'>Contacting Us Failed</h5>"
                            
                        );
                    }


                 }    
         
             $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));

    }





    public function user_info()
    {              
        $this->form_validation->set_rules('name','First Name','required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('mobile','Mobile','required');
        $this->form_validation->set_rules('message','Message','required');
            $form_data  = $this->input->post();

            if($this->form_validation->run() == FALSE)
            { 
                $response = array(
                'status' => 'error',
                'name_err' => form_error('name'),
                'email_err' => form_error('email'),
                'mobile_err' => form_error('mobile'),
                'message_err' => form_error('message')
                );  
            }
            else
            {      

                 
                    $insertData = array();
                    $insertData['name']  = $form_data['name'];
                    $insertData['email']    = $form_data['email'];
                    $insertData['mob_num']  = $form_data['mobile'];
                    $insertData['message']    = $form_data['message'];
                    $insertData['date_at'] = date("Y-m-d H:i:s");
                    $result = $this->user_contact_model->save($insertData);
                    
                
                    $html_data = " Name : ".  $form_data['name']."\n";
                    $html_data .= " Email : ".  $form_data['email']."\n";
                    $html_data .= " Phone : ".  $form_data['mobile']."\n";
                    $html_data .= " Message : ".  $form_data['message']."\n";
                    
                    $subject_name = "New Lead Arrival Of ".$form_data['name'];
                    
                    

                    $description =$html_data;

                    $this->load->library('email');
                    $config['mailtype'] = 'html';
                    $this->email->initialize($config);
                    $toemail = 'abbasdigitalmarket@gmail.com,leads@megatasktechnologies.com';
                    $this->email->from('info@megataskweb.com', 'New Lead');
                    $this->email->to($toemail);

                    $this->email->subject($subject_name);
                    $this->email->message( $description );

                    $resulst = $this->email->send();
                    
                    

                    if($result > 0)
                    {
                         $response = array(
                            'status' => 'success',
                            'message' => "<h5 style='color:green;'>Thank You For Contacting Us....</h5>"
                        );
                    }
                    else
                    { 
                        $response = array(
                              'status' => 'error',
                            'message' => "<h5 style='color:#1b561b;'>Contacting Us Failed</h5>"
                        );
                    }
                   

                 }    
         
             $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }


	 
}
