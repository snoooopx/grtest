<?php 

require_once APPPATH.'libraries/REST_Controller.php';

/**
* c_tasks controller class
*/
class C_tasks_rest extends REST_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_tasks');
		$this->load->library('form_validation');
	}


	function task_get($id=0)
	{
		$res = $this->m_tasks->get_selected($id);

		if ( $res->num_rows() > 0 AND $id > 0) 
		{
			$this->response($res->result_array()[0]);
		}
		else
		{
			if ( $id == 0) 
			{
				$this->response($this->m_tasks->get_all()->result());
			}
			else
			{
				$this->response(array('message' => 'failure','status'=>'No Task with specified ID' ));
			}
		}
	}


	function task_put($id)
	{
		$this->form_validation->set_data($this->put());

		if ($this->form_validation->run('task_put') != FALSE) 
		{
			$task = array('id'=>$this->put('id'), 'user'=>$this->put('user'), 'title'=>$this->put('title'), 'email'=>$this->put('email'));

			$res = $this->m_tasks->update($task);

			if ($res > 0) 
			{
				$this->response(array('message'=>'success','status'=>'Task Update Success'));
			}
			else
			{
				$this->response(array('message'=>'failure','status'=>'Task Update Failed '), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
			}
		}
		else
		{
			$this->response(array('message'=>'failure', 'status'=>$this->form_validation->error_array()), REST_Controller::HTTP_BAD_REQUEST );
		}		
	}


	
	function task_post()
	{
		$this->form_validation->set_data($this->post());

		if ($this->form_validation->run('task_post') != FALSE) {
			
			$task = array( 'user'=>$this->post('user'), 'title'=>$this->post('title'), 'email'=>$this->post('email'));

			$res = $this->m_tasks->create($task);

			if ($res) {
				$this->response(array('message'=>'success','status'=>'New Task created with-> '.$res. ' ID '));
			}
			else{
				$this->response(array('message'=>'failure','status'=>'task insert failed '), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
			}
		}
		else{
			$this->response(array('message'=>'failure', 'status'=>$this->form_validation->error_array()), REST_Controller::HTTP_BAD_REQUEST );
		}
		
	}

	
	function task_delete($id)
	{
		$res = $this->m_tasks->delete($id);
		if ($res > 0) 
		{
			$this->response(array('message'=>'success','status'=>'Task DELETE Success'));
		}
		else
		{
			$this->response(array('message'=>'failure','status'=>'Task DELETE Failed '), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
		}
	}


/*
	function index_get()
	{
		//echo json_encode($res);
		//return $res;
		
	}


	//show all tasks
	public function taskishs()
	{
		$res = $this->m_tasks->get_all();

		$this->response($res->result());
		//echo json_encode($res);
		//return $res;
	}

	//show one task
	public function show($id=0)
	{
		$res = $this->m_tasks->get_selected($id);
		//echo json_encode($res);
		return json_encode($res);
	}

	//create task
	public function create()
	{
		return "hello from show";
	}

	//update task
	public function update()
	{
		return "hello from show";
	}*/

	//delete task
	/*public function delete()
	{
		return "hello from show";
	}
*/




}

 ?>