# NameChange mod

## Last tested in VBulleting 3.8 and lower

###Install instructions:


1. Upload contents of the 'upload' folder to the forum root.


2. Import product-vb_namechange.xml through the product manager in the adminCP


3. Goto AdminCP->Usergroups->Usergroup Manager-> Set a time limit for each usergroup on how long they have to wait before each namechange.


4. Need to edit template "USERCP_SHELL" of your favorite theme. And add the following lines to the menu section


<!-- VB NameChange Hack -->
<tr> <td class="$navclass[namechange]" nowrap="nowrap"> <a class="smallfont" href="namechange.php">$vbphrase[mp_namechange_class] </a> </td> </tr>
<!-- /VB NameChange Hack --> 