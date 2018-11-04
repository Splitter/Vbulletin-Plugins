<?php
  // ####################### SET PHP ENVIRONMENT ###########################
  error_reporting(E_ALL & ~E_NOTICE);
  
  // #################### DEFINE IMPORTANT CONSTANTS #######################
  define('THIS_SCRIPT', 'namechange');
  define('CSRF_PROTECTION', true);
  
  // ################### PRE-CACHE TEMPLATES AND DATA ######################
  // get special phrase groups
  $phrasegroups = array('user');
  
  // get special data templates from the datastore
  $specialtemplates = array();
  
  // pre-cache templates used by all actions
  $globaltemplates = array('USERCP_SHELL', 'USERCP', 'usercp_nav_folderbit', );
  // pre-cache templates used by specific actions
  $actiontemplates = array();
  
  require_once('./global.php');
  require_once(DIR . '/includes/functions_user.php');
  
  
  $days = $vbulletin->usergroupcache[$vbulletin->userinfo['usergroupid']]['mpusernamelimit'];
  $seconds = $days * 86400;
  $last = $vbulletin->db->query_first("SELECT mplastchange FROM " . TABLE_PREFIX . "user WHERE userid='" . $vbulletin->userinfo['userid'] . "'");
  $today = time();
  $dif = $today - $last['mplastchange'];
  
  if ($dif < $seconds or $days >= 1000) {
      if ($days >= 1000) {
          $content = "You do not have permission to change your name.<br>";
      } else {
          
          $newdif = $seconds - $dif;
          $daydif = $newdif / 86400;
          $daydif = round($daydif, 2);
          $content = "You have " . $daydif . " days until you can change your name again.";
      }
      eval('$HTML = "' . fetch_template('mp_namechange_nopermission') . '";');
      construct_usercp_nav('namechange');
      $frmjmpsel['usercp'] = 'class="fjsel" selected="selected"';
      $navbits = construct_navbits(array('namechange.php' => 'No Permission'));
  } else {
      if (!isset($_REQUEST['do'])) {
          $_REQUEST['do'] = 'showform';
      }
      if ($_REQUEST['do'] == 'save') {
          $vbulletin->input->clean_array_gpc('p', array('newusername' => TYPE_STR, 'verifyusername' => TYPE_STR, ));
          if ($vbulletin->GPC['newusername'] != $vbulletin->GPC['verifyusername']) {
              $nameerrors = "<div style='color:red'>Both username fields must match</div>";
              $_REQUEST['do'] = 'showform';
          } else {
              $userid = $vbulletin->userinfo['userid'];
              $oldname = $vbulletin->userinfo['username'];
              $userinfo = fetch_userinfo($userid);
              
              $newuser =& datamanager_init('User', $vbulletin, ERRTYPE_ARRAY);
              $newuser->adminoverride = true;
              
              $newuser->set_existing($userinfo);
              
              $newuser->set('username', $vbulletin->GPC['newusername']);
              if ($newuser->errors) {
                  $nameerrors = "<div style='color:red'>";
                  foreach ($newuser->errors as $value) {
                      $nameerrors .= $value;
                  }
                  $nameerrors .= "</div>";
                  $_REQUEST['do'] = 'showform';
              } else {
                  $newuser->save();
                  $result = $vbulletin->db->query_read("SELECT * FROM " . TABLE_PREFIX . "mp_namechange_oldnames WHERE username = '" . $oldname . "' AND userid='" . $vbulletin->userinfo['userid'] . "'");
                  if ($vbulletin->db->num_rows($result) <= 0) {
                      $vbulletin->db->query_write("INSERT INTO " . TABLE_PREFIX . "mp_namechange_oldnames (username,userid) VALUES ('" . $oldname . "','" . $vbulletin->userinfo['userid'] . "')");
                  }
                  $vbulletin->db->query_write("UPDATE " . TABLE_PREFIX . "user SET mplastchange='" . time() . "' WHERE userid='" . $vbulletin->userinfo['userid'] . "' ");
                  
                  eval('$HTML = "' . fetch_template('mp_namechange_table') . '";');
                  construct_usercp_nav('namechange');
                  $frmjmpsel['usercp'] = 'class="fjsel" selected="selected"';
                  $navbits = construct_navbits(array('namechange.php' => 'Username Changed'));
              }
          }
      }      
      if ($_REQUEST['do'] == 'showform') {
          eval('$HTML = "' . fetch_template('mp_namechange_form') . '";');
          construct_usercp_nav('namechange');
          $frmjmpsel['usercp'] = 'class="fjsel" selected="selected"';
          $navbits = construct_navbits(array('namechange.php' => 'Change Username'));
      }
  }
  construct_forum_jump();
  
  
  
  eval('$navbar = "' . fetch_template('navbar') . '";');
  eval('print_output("' . fetch_template('USERCP_SHELL') . '");');
?>
