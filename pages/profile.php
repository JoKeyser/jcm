<?php
/*
Copyright © 2014, Florian Perdreau
This file is part of Journal Club Manager.

Journal Club Manager is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Journal Club Manager is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with Journal Club Manager.  If not, see <http://www.gnu.org/licenses/>.
*/

@session_start();
require_once($_SESSION['path_to_includes'].'includes.php');
check_login();

// Declare classes
$user = new users($_SESSION['username']);
$Press = new Press();
$publication_list = $user->getpublicationlist(null);

$notif_yes_status = 'unchecked';
$notif_no_status = 'unchecked';
if ($user->notification == 0) {
    $notif_no_status = 'checked';
} else {
    $notif_yes_status = 'checked';
}

$rem_yes_status = 'unchecked';
$rem_no_status = 'unchecked';
if ($user->reminder == 0) {
    $rem_no_status = 'checked';
} else {
    $rem_yes_status = 'checked';
}

$result = "
<div id='content'>
    <span id='pagename'>Hello $user->fullname!</span>
    <div class='operation_button'><a rel='leanModal' href='#modal' class='modal_trigger' id='modal_trigger_delete'>Delete my account</a>
    </div>

    <div class='section_header'>Personal information</div>
    <div class='section_content'>
        <form method='post' action='' class='form' id='profile_persoinfo_form'>
            <input type='hidden' name='username' value='$user->username'/>
            <label for='firstname' class='label'>First Name</label><input type='text' name='firstname' value='$user->firstname'/>
            <label for='lastname' class='label'>Last Name</label><input type='text' name='lastname' value='$user->lastname'/></br>
            <label for='status' class='label'>Status: </label>$user->status<br>
            <label for='password' class='label'>Password</label> <a href='' class='change_pwd' id='$user->email'>Change my password</a></br>
            <label for='position' class='label'>Position</label>
            <select name='position'>
                <option value='$user->position' selected='selected'>$user->position</option>
                <option value='researcher'>Researcher</option>
                <option value='postdoc'>Post-doc</option>
                <option value='phdstudent'>PhD student</option>
                <option value='master'>Master</option>
            </select></br>
            <label class='label'>Number of submitted presentation: </label>$user->nbpres<br>
            <input type='hidden' name='user_modify' value='true' />
            <p style='text-align: right'><input type='submit' name='user_modify' value='Modify' class='profile_persoinfo_form' id='submit'/></p>
            <div class='feedback_perso'></div>
        </form>
    </div>

    <div class='section_header'>Contact information</div>
    <div class='section_content'>
        <form method='post' action='' class='form' id='profile_emailinfo_form'>
            <label for='email' class='label'>Email</label><input size='40' type='text' name='email' value='$user->email'/></br>
            <label for='notification' class='label'>I wish to receive email notifications</label>
            <input type='radio' name='notification' value='1' $notif_yes_status>Yes</input>
            <input type='radio' name='notification' value='0' $notif_no_status>No</input>
            </br>
            <label for='reminder' class='label'>I wish to receive reminders</label>
            <input type='radio' name='reminder' value='1' $rem_yes_status>Yes</input>
            <input type='radio' name='reminder' value='0' $rem_no_status>No</input>
            <input type='hidden' name='user_modify' value='true' />
            <input type='hidden' name='username' value='$user->username'/>
            <p style='text-align: right'><input type='submit' name='user_modify' value='Modify' class='profile_emailinfo_form' id='submit'/></p>
            <div class='feedback_mail'></div>
        </form>
    </div>
    </br>

    <div class='section_header'>My submissions</div>
    <div class='section_content'>
    $publication_list
    </div>
</div>
";

echo json_encode($result);
