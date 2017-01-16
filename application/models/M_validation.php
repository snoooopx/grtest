<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|---------------------------------------------------------------------------------
|m_validation Model Class for Validation Operations and so on...
|---------------------------------------------------------------------------------
*/
class M_validation extends CI_Model {
 
   function __construct()
   {
     parent::__construct();
     $this->load->model('m_users');
   }
  

  /*
  |---------------------------------------------------------------------------------
  |Generate Salt For User Password
  |---------------------------------------------------------------------------------
  */
  public function generate_salt()
  {

    return mcrypt_create_iv( 22, MCRYPT_DEV_URANDOM );
  }
  

  /*
  |---------------------------------------------------------------------------------
  |Get User Salt
  |---------------------------------------------------------------------------------
  */
  private function get_user_salt($user_id)
  {
    
  }

  
  /*
  |---------------------------------------------------------------------------------
  |Hashing User Password With Salt
  |---------------------------------------------------------------------------------
  */
  public function hash_password( $password, $salt="" )
  {
     $option = [
        'cost'=>11
        /*'salt' => $salt,*/
      ];
      $hashed_password = password_hash( $password, PASSWORD_BCRYPT, $option);

      return $hashed_password;
  }

  /*
  |---------------------------------------------------------------------------------
  |Verifying User Login Password
  |---------------------------------------------------------------------------------
  */
  private function verify_password( $password )
  {
    
  }

  
  /*
  |---------------------------------------------------------------------------------
  |Check User Session
  |If Logged in Return Login info
  |Else Destroy Session and Redirect to Login Page 
  |---------------------------------------------------------------------------------
  */
  public function check_user_loggedin()
  {
      if( $this->session->userdata('logged_in') )
      {
          $session_data = $this->session->userdata('logged_in');

          return $session_data;
      }
      else
      {
          //Destroying Session
          $this->destroy_session();
          //Redirect to login page
          redirect( site_url('backend/login/check'), 'refresh' );
      }
  }

  /*
  |---------------------------------------------------------------------------------
  | Generate ACL For Specified User
  |---------------------------------------------------------------------------------
  */
  public function generate_acl($user_id="0")
  {
    $this->load->model('m_config');
    $acl_unsorted = $this->m_config->get_sections($user_id);
    $acl_sorted = array();
    /*echo "<pre>";
    print_r($sb_unsorted);
    echo "</pre>";
    die;*/
    foreach ( $acl_unsorted as $row ) 
    {
      $acl_sorted[ $row[ 'section_link' ] ][ 'info' ][ 'section_icon' ] = $row['section_icon'];
      $acl_sorted[ $row[ 'section_link' ] ][ 'info' ][ 'section_seq'  ] = $row['section_seq'];
      $acl_sorted[ $row[ 'section_link' ] ][ 'info' ][ 'section_name' ] = $row['section_name'];
      //$acl_sorted[ $row[ 'section' ] ][ 'info' ][ 'sectionicon' ] = $row['section_seq'];

      foreach ($row as $key => $value) 
      {
        $acl_sorted[ $row['section_link'] ][ $row['subsection_link']] [ $key ] = $value;
      }
    }

    return $acl_sorted;
  }


/*
  |---------------------------------------------------------------------------------
  | Generate Sidebar For Specified User
  |---------------------------------------------------------------------------------
  */
  public function generate_sidebar($user_id="0")
  {
    $this->load->model('m_config');
    $sb_unsorted = $this->m_config->get_sections($user_id);
    $sb_sorted = array();
    /*echo "<pre>";
    print_r($sb_unsorted);
    echo "</pre>";
    die;*/
    foreach ( $sb_unsorted as $row ) 
    {
      //$sb_sorted[ $row[ 'section' ] ][ 'info' ][ 'sectionicon' ] = $row['section_seq'];
      if ( $row['r'] !=='1') 
      {
        continue;
      }
      $sb_sorted[ $row[ 'section_link' ] ][ 'info' ][ 'section_icon' ] = $row['section_icon'];
      $sb_sorted[ $row[ 'section_link' ] ][ 'info' ][ 'section_seq'  ] = $row['section_seq'];
      $sb_sorted[ $row[ 'section_link' ] ][ 'info' ][ 'section_name' ] = $row['section_name'];

      foreach ($row as $key => $value) 
      {
        $sb_sorted[ $row[ 'section_link' ] ][ $row['subsection_link']] [ $key ] = $value;
      }
    }
   /* echo "<pre>";
    print_r($sb_sorted);
    echo "</pre>";
    die;*/
    return $sb_sorted;
  }

  /*
  |---------------------------------------------------------------------------------
  | Unset Sesitive User Info in Session 
  | And Destroy Session
  |---------------------------------------------------------------------------------
  */
  public function destroy_session()
  {
    //Unset User Info
    $this->session->unset_userdata('logged_in');
    //Unset Section list
    $this->session->unset_userdata('section_list');
    //Unset User ACL
    $this->session->unset_userdata('acl');
    //Destroy Session   
    $this->session->sess_destroy();
  }


#################################################################################################
//
//-> CHECKING USER PERMISSION FOR REQUESTED PAGE
//
#################################################################################################
    public function check_requested_page_perms( $rqst_section, $rqst_subsection )
    {
      $allow = array();

      //Getting ACL for Logged in User From Session
      $acl = $this->session->userdata('acl');
     /* echo "<pre>";
      print_r($acl);
      echo "</pre>";
      exit;*/
  
      /*
      * Validating Section Permission
      * If Section Doesn`t Exist in ACT Return FALSE for CRUD
      */ 
      if( !isset( $acl[$rqst_section] ) )
      {
        $allow['read']   = FALSE;
        $allow['create'] = FALSE;
        $allow['update'] = FALSE;
        $allow['delete'] = FALSE;
      
        return $allow;
      }

      /*
      * Validating Subsection For Specified Section
      * If SubSection Doesn`t Exist in ACT Return FALSE for CRUD
      */ 
      if( !isset( $acl[$rqst_section][$rqst_subsection] ) )
      {
        $allow['read']   = FALSE;
        $allow['create'] = FALSE;
        $allow['update'] = FALSE;
        $allow['delete'] = FALSE;
        
        return $allow;

      }

      //CHECKING FOR READ PERMISSION
      if( $acl[$rqst_section][$rqst_subsection]['r'] == '1' )
      {
        $allow['read'] = TRUE;
      }
      else
      {
        $allow['read'] = FALSE;
      }

      //CHECKING FOR CREATE PERMISSION
      if( $acl[$rqst_section][$rqst_subsection]['c'] == '1' )
      {
        $allow['create'] = TRUE;
      }
      else
      {
        $allow['create'] = FALSE;
      }

      //CHECKING FOR UPDATE PERMISSION
      if( $acl[$rqst_section][$rqst_subsection]['u'] == '1' )
      {
        $allow['update'] = TRUE;
      }
      else
      {
        $allow['update'] = FALSE;
      }

      //CHECKING FOR DELETE PERMISSION
      if( $acl[$rqst_section][$rqst_subsection]['d'] == '1' )
      {
        $allow['delete'] = TRUE;
      }
      else
      {
        $allow['delete'] = FALSE;
      }

      $allow['section_name'] = $acl[$rqst_section][$rqst_subsection]['section_name'];
      $allow['subsection_name'] = $acl[$rqst_section][$rqst_subsection]['subsection_name'];
    return $allow;
    }

  


//--> End of "m_validation" Model Class
}
?>