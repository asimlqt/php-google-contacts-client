<?php
/**
 * Copyright 2013 Asim Liaquat
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
namespace Google\Contacts;

/**
 * Contacts Entry.
 *
 * @package    Google
 * @subpackage Contacts
 * @version    0.1
 * @author     Asim Liaquat <asimlqt22@gmail.com>
 */
class Entry
{
    /**
     * The xml entry for a single contact
     * 
     * @var array
     */
    private $entry;

    public function __construct($entry)
    {
        $this->entry = $entry;
    }

    public function getEtag()
    {
        return $this->entry['gd$etag'];
    }

    /**
     * Get the contact id
     * 
     * @return string
     */
    public function getId()
    {
        return $this->entry['id']['$t'];
    }    

    /**
     * Get the time contact was updated
     * 
     * @return \DateTime
     */
    public function getUpdated()
    {
        return new \DateTime($this->entry['updated']['$t']);
    }

    /**
     * Get edit url
     * 
     * @return string
     */
    public function getEditUrl()
    {
        return Util::getLinkHref($this->xml, 'edit');
    }

    /**
     * Get title
     * 
     * @return string
     */
    public function getTitle()
    {
        return $this->entry['title']['$t'];
    }

    /**
     * Get contact name object
     * 
     * @return \Google\Contacts\Entry\Name
     */
    public function getName()
    {
        if(isset($this->entry['gd$name'])) {
            return new Entry\Name($this->entry['gd$name']);
        }
        return null;
    }

    /**
     * Get the contact phone numbers
     * 
     * @return array \Google\Contacts\Entry\PhoneNumber
     */
    public function getPhoneNumbers()
    {
        $phones = array();
        if(isset($this->entry['gd$phoneNumber'])) {
            foreach($this->entry['gd$phoneNumber'] as $el) {
                $type = substr($el['rel'], strpos($el['rel'], '#')+1);
                $number = $el['$t'];
                $phones[] = new Entry\PhoneNumber($type, $number);
            }
        }
        return $phones;
    }

    /**
     * Get the contact email information
     * 
     * @return array \Google\Contacts\Entry\Email
     */
    public function getEmails()
    {
        $emails = array();
        if(isset($this->entry['gd$email'])) {
            $el = $this->entry['gd$email'];
            foreach($el as $email) {
                $type = substr($email['rel'], strpos($email['rel'], '#')+1);
                $address = $email['address'];
                $emails[] = new Entry\Email($type, $address, $email['primary'] === 'true');
            }
        }
        return $emails;
    }
}