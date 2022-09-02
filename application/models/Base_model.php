<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Base_model extends CI_Model{

    

    public $table;



    function __construct() {

            //parent::__construct();

            $this->table = $this->db->dbprefix.$this->table;

            $this->init_autoloader();

        }



        public function save($data) {

            if (!empty($data['id'])) {

                $this->db->where('id', $data['id']);

                $this->db->update($this->table, $data);

                $updated_status = $this->db->affected_rows();

                if($updated_status) {

                    return $data['id'];

                } else {

                    return false;

                }

            } else {

                $this->db->insert($this->table, $data);

                return $this->db->insert_id();

            }

        }



        public function all() {

            $query = $this->db->select("")

                    ->from($this->table)

                    ->get();

            if ($query->num_rows() > 0) {

                return $query->result();

            } else {

                return array();

            }

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



        public function findBy($condition = array(),$orderBy = array()) {

            $this->db->select('*');

            $this->db->from($this->table);

            $this->db->where($condition);

            if(count($orderBy) > 0) {

                $this->db->order_by($orderBy['key'], $orderBy['value']);

            }

            $query = $this->db->get();

            if ($query->num_rows() > 0) {

                return $query->result();

            } else {

                return array();

            }

        }



        public function findOneBy($condition = array()) {

            $query = $this->db->select('*')

                    ->from($this->table)

                    ->where($condition)

                    ->get();

            if ($query->num_rows() > 0) {

                $result = $query->result();

                return $result[0];

            } else {

                return array();

            }

        }

        

        private function init_autoloader() {

            spl_autoload_register(function($classname) {

                if (file_exists('application/interfaces/' . $classname . '.php')) {

                    require_once 'application/interfaces/' . $classname . '.php';

                }

            });

        }

        

        public function findByLimit($condition = array(),$limit,$start) {

            $this->db->limit($limit, $start);

            $query = $this->db->select('*')

                    ->from($this->table)

                    ->where($condition)

                    ->get();

            if ($query->num_rows() > 0) {

                return $query->result();

            } else {

                return array();

            }

        }

        

     /**

      * 

      * @param type $id

      * @return type

      */   

    function delete($id) {

        $this->db->where('id', $id);

        $this->db->delete($this->table);        

        return $this->db->affected_rows();

    }



    // Count Row 

    function countRow($searchText = '')

    {

        $this->db->select('*');

        $this->db->from($this->table); 

        $query = $this->db->get();        

        return count($query->result());

    }



    // Row Listing

    function rowListing($searchText = '', $page, $segment)

    {

        $this->db->select('*');

        $this->db->from($this->table);      

        $this->db->limit($page, $segment);

        $this->db->order_by('id', 'desc');

        $query = $this->db->get();        

        $result = $query->result();        

        return $result;

    }



    // get dynamic fild & data

    

    /* 

       How To  Data Bind => like That 

        ======================

        $where['table']  = 'table_Name';

        $where  = array();

        $where['field']  = 'id,name,status,date';

        $where['orderby']  = '-id'; // Desc when - add

        $where['limit']  = '0,10';

        $where['id']  = '10';

        $where['name']  = 'user';

        $where['like']  = 'fieldname,value';

        



    */    

    function findDynamic($where)  

    {



        foreach($where as $key=>$v)

        {

            // Fields set

            if($key == 'field')

            {

                $this->db->select($v);

            }

            

            // Order By

            if($key == 'orderby')

            {

                $temp_order = explode('-',$v);

                if(count($temp_order) >1)

                 $this->db->order_by($temp_order[1], 'DESC');

                else

                    $this->db->order_by($v, 'ASC');

            }

            // LIMIT

            if($key == 'limit')

            {

                $this->db->limit($v);

            }



            // Like

            if($key == 'like')

            {

                $temp = explode(',',$v);

                

                $this->db->like($temp[0], $temp[1]);

                

            }

            

            // where

            if($key != 'field' AND $key != 'orderby' AND $key != 'limit' AND $key != 'table' AND $key != 'like')

            {

                $temp_where = array($key=>$v);

               $this->db->where($temp_where);

            }

            

        } 

        if(!isset($where['table']))
            $this->db->from($this->table); 
        else
          $this->db->from($where['table']); 



        $query = $this->db->get(); 

        //echo $this->db->last_query();



        $result = $query->result();        

        return $result;

    }

}



?>