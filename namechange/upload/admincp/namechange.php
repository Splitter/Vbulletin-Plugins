<?php
  require_once('./global.php');
  print_cp_header('Past Usernames');
  if (is_member_of($vbulletin->userinfo, 6) or is_member_of($vbulletin->userinfo, 5)) {
      if (!isset($_REQUEST['do'])) {
          $_REQUEST['do'] = 'form';
      }
      if ($_REQUEST['do'] == 'username') {
          $vbulletin->input->clean_array_gpc('p', array('username' => TYPE_STR, ));
          print_table_start($echobr = true, $width = '90%', $cellspacing = 0, $id = '');
          print_table_header("Usernames", $colspan = 2, $htmlise = false, $anchor = '', $align = 'center', $helplink = false);
          $result = $vbulletin->db->query_first("SELECT userid from " . TABLE_PREFIX . "user where username ='" . $vbulletin->GPC['username'] . "'");
          if ($result) {
              $result2 = $vbulletin->db->query_read("SELECT username FROM " . TABLE_PREFIX . "mp_namechange_oldnames WHERE userid ='" . $result['userid'] . "'");
              if ($vbulletin->db->num_rows($result2) > 0) {
                  while ($data = $vbulletin->db->fetch_array($result2)) {
                      print_label_row($data['username'], $value = '&nbsp;', $class = '', $valign = 'top', $helpname = null, $dowidth = false);
                  }
              } else {
                  print_label_row("User has no past usernames.", $value = '&nbsp;', $class = '', $valign = 'top', $helpname = null, $dowidth = false);
                  $_REQUEST['do'] = 'form';
              }
              print_table_footer($colspan = 2, $rowhtml = '', $tooltip = '', $echoform = true);
          } else {
              print_label_row("Username Does not Exist", $value = '&nbsp;', $class = '', $valign = 'top', $helpname = null, $dowidth = false);
              print_table_footer($colspan = 2, $rowhtml = '', $tooltip = '', $echoform = true);
              $_REQUEST['do'] = 'form';
          }
      }
      if ($_REQUEST['do'] == 'userid') {
          $vbulletin->input->clean_array_gpc('p', array('userid' => TYPE_STR, ));
          print_table_start($echobr = true, $width = '90%', $cellspacing = 0, $id = '');
          print_table_header("Usernames", $colspan = 2, $htmlise = false, $anchor = '', $align = 'center', $helplink = false);
          $result = $vbulletin->db->query_first("SELECT userid from " . TABLE_PREFIX . "user where userid ='" . $vbulletin->GPC['userid'] . "'");
          if ($result) {
              $result2 = $vbulletin->db->query_read("SELECT username FROM " . TABLE_PREFIX . "mp_namechange_oldnames WHERE userid ='" . $result['userid'] . "'");
              if ($vbulletin->db->num_rows($result2) > 0) {
                  while ($data = $vbulletin->db->fetch_array($result2)) {
                      print_label_row($data['username'], $value = '&nbsp;', $class = '', $valign = 'top', $helpname = null, $dowidth = false);
                  }
              } else {
                  print_label_row("User has no past usernames.", $value = '&nbsp;', $class = '', $valign = 'top', $helpname = null, $dowidth = false);
                  $_REQUEST['do'] = 'form';
              }
              print_table_footer($colspan = 2, $rowhtml = '', $tooltip = '', $echoform = true);
          } else {
              print_label_row("UserID Does not Exist", $value = '&nbsp;', $class = '', $valign = 'top', $helpname = null, $dowidth = false);
              print_table_footer($colspan = 2, $rowhtml = '', $tooltip = '', $echoform = true);
              $_REQUEST['do'] = 'form';
          }
      }
      if ($_REQUEST['do'] == 'form') {
          print_form_header('namechange', $do = 'username', $uploadform = false, $addtable = true, $name = 'namechange', $width = '90%', $target = '', $echobr = true, $method = 'post', $cellspacing = 0);
          print_table_header("Find By Current Username", $colspan = 2, false, '', 'center', false);
          print_input_row("Users Name", 'username', $track['creator'], true, 35, 0, '', false);
          print_submit_row('Find', '_default_', 2, '', '', false);
          print_table_footer($colspan = 2, $rowhtml = '', $tooltip = '', $echoform = true);
          echo "<br/><br/>";
          print_form_header('namechange', $do = 'userid', $uploadform = false, $addtable = true, $name = 'namechange', $width = '90%', $target = '', $echobr = true, $method = 'post', $cellspacing = 0);
          print_table_header("Find By Current UserID", $colspan = 2, false, '', 'center', false);
          print_input_row("Users UserID", 'userid', $track['creator'], true, 35, 0, '', false);
          print_submit_row('Find', '_default_', 2, '', '', false);
          print_table_footer($colspan = 2, $rowhtml = '', $tooltip = '', $echoform = true);
      }
  } else {
      print_cp_no_permission();
  }
  print_cp_footer();
?>