<?php if(!defined('BASEPATH')) exit('No direct script access allowed');







class Lead_form_model extends Base_model

{

    public $table = "contact_details";

    var $column_order = array(null, 'l_name','l_email','l_mobile','l_age','address','qualification'); //set column field database for datatable orderable

    var $column_search = array('l_name','l_email','l_mobile','l_age'); //set column field database for datatable searchable 

    var $order = array('id' => 'desc'); // default order



        



        function __construct() {



            parent::__construct();



        }



        // Ajax Scroll


    


        // Ajax Scroll End







     function delete($id) {



        $this->db->where('id', $id);



        $this->db->delete($this->table);        



        return $this->db->affected_rows();



    }







    public function find($id) {



            $query = $this->db->select('*')



                    ->from($this->table)



                    ->where('id', $id)



                    ->get();



            if ($query->num_rows() > 0) {



                $result = $query->result();



                return $result[0];



            } else {



                return array();



            }



        }



        // Get Video List

        function get_datatables()

        {

            $this->_get_datatables_query();

            if(isset($_POST['length']) && $_POST['length'] != -1)

            $this->db->limit($_POST['length'], $_POST['start']);

            $query = $this->db->get();

            return $query->result();

        }

        // Get Database 

         public function _get_datatables_query()

        {     

            $this->db->from($this->table);

            $i = 0;     

            foreach ($this->column_search as $item) // loop column 

            {

                if(isset($_POST['search']['value']) && $_POST['search']['value']) // if datatable send POST for search

                {

                    if($i===0) // first loop

                    {

                        $this->db->like($item, $_POST['search']['value']);

                    }

                    else

                    {

                        $this->db->or_like($item, $_POST['search']['value']);

                    }

                }

                $i++;

            }

             

            if(isset($_POST['order'])) // here order processing

            {

                $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);

            } 

            else if(isset($this->order))

            {

                $order = $this->order;

                $this->db->order_by(key($order), $order[key($order)]);

            }

        }



        // Count  Filtered

        function count_filtered()

        {

            $this->_get_datatables_query();

            $query = $this->db->get();

            return $query->num_rows();

        }

        // Count all

        public function count_all()

        {

            $this->db->from($this->table);

            return $this->db->count_all_results();

        }



           //Email Template Function

         public function get_template_dtl( array $arra_data )
        {


            $html_template =  get_product_template();

                           
                       
            $bg_img =  $arra_data['bg_img'];
            $message =  $arra_data['message'];
            $company_name =  $arra_data['company_name'];
            $visit_us =  $arra_data['visit_us'];
            $tel_number =  $arra_data['tel_number'];

           
            $html_template = str_replace("##bg_img##",$bg_img,$html_template);
            $html_template = str_replace("##message##",$message,$html_template);
            $html_template = str_replace("##company_name##",$company_name,$html_template);
            $html_template = str_replace("##visit_us##",$visit_us,$html_template);
            $html_template = str_replace("##tel_number##",$tel_number,$html_template);
             
                        
            return $html_template;
         
        }



}











  