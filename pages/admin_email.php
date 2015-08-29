<?php
/**
 * @author Florian Perdreau (fp@florianperdreau.fr)
 * @copyright Copyright (C) 2014 Florian Perdreau
 * @license <http://www.gnu.org/licenses/agpl-3.0.txt> GNU Affero General Public License v3
 *
 * This file is part of Journal Club Manager.
 *
 * Journal Club Manager is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Journal Club Manager is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Journal Club Manager.  If not, see <http://www.gnu.org/licenses/>.
 */

// Declare classes
require('../includes/boot.php');
$user = new User($db,$_SESSION['username']);

// Send mail
$result = "
    <h1>Mailing list</h1>
    <p class='page_description'>Here you can send an email to users who subscribed to the newsletter.</p>
    <section>
        <h2>Send an email</h2>
        <form id='mailing_send'>
            <input type='hidden' name='mailing_send' value='true'>
            <div class='submit_btns'>
                <input type='submit' name='send' value='Send' class='mailing_send'>
            </div>
            <div class='formcontrol'>
                <label>Subject:</label>
                <input type='text' name='spec_head' placeholder='Subject' required/>
            </div>
            <div class='formcontrol'>
                <label>Message</label>
                <textarea name='spec_msg' class='tinymce' required></textarea>
            </div>
        </form>
    </section>";

echo json_encode($result);
exit;