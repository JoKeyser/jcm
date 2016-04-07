<?php

/**
 * File for class MailSender
 *
 * PHP version 5
 *
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

/**
 * Class MailSender
 */
class MailSender extends AppCron {

    /**
     * @var string: Task name
     */
    public $name = 'MailSender';

    /**
     * @var string: Path to task class
     */
    public $path;

    /**
     * @var string: Class status
     */
    public $status = 'Off';

    /**
     * @var bool: is the task installed?
     */
    public $installed = False;

    /**
     * @var string: running time
     */
    public $time;

    /**
     * @var string: day's name (e.g. 'Monday')
     */
    public $dayName;

    /**
     * @var string: day's number (0-6)
     */
    public $dayNb;

    /**
     * @var string: hour (0-23)
     */
    public $hour;

    /**
     * @var array: task's settings
     */
    public $options=array("nb_version"=>10);

    /**
     * @var AppMail
     */
    private static $AppMail;

    /**
     * @var MailManager
     */
    private $Manager;

    /**
     * Constructor
     * @param AppDb $db
     */
    public function __construct(AppDb $db) {
        parent::__construct($db);
        $this->Manager = new MailManager($db);

        $this->path = basename(__FILE__);
        $this->time = AppCron::parseTime($this->dayNb, $this->dayName, $this->hour);
    }

    /**
     * Factory
     * @return AppMail
     */
    private function getMailer() {
        if (is_null(self::$AppMail)) {
            $config = new AppConfig($this->db);
            self::$AppMail = new AppMail($this->db, $config);
        }
        return self::$AppMail;
    }

    /**
     * Sends emails
     */
    public function run() {
        $result['status'] = false;

        // Clean DB
        $this->clean();

        // Get Mail API
        $this->getMailer();

        foreach ($this->Manager->all(0) as $key=>$email) {
            $recipients = explode(',', $email['recipients']);
            if (self::$AppMail->send_mail($recipients, $email['subject'], $email['content'], $email['attachments'])) {
                $result['status'] = $this->Manager->update(array('status'=>1), $email['mail_id']);
            } else {
                $result['status'] = false;
            }
        }
        return $result;
    }

    /**
     * Clean email table
     * @param int|null $day
     * @return bool
     */
    public function clean($day=null) {
        $day = (is_null($day)) ? $this->options['nb_version']: $day;
        $date_limit = date('Y-m-d',strtotime("now - $day day"));
        $sql = "SELECT * FROM {$this->Manager->tablename} WHERE date>={$date_limit} and status='1'";
        $data = $this->db->send_query($sql)->fetch_all(MYSQLI_ASSOC);
        foreach ($data as $key=>$email) {
            if (!$this->db->deletecontent($this->Manager->tablename, 'mail_id', $email['mail_id'])) {
                return false;
            }
        }
        return true;
    }
}