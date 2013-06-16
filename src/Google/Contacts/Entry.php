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
     * Etag
     * @var string
     */
    private $etag;

    /**
     * $id
     * @var string
     */
    private $id;

    /**
     * $updated
     * @var DateTime
     */
    private $updated;

    /**
     * $links
     * @var array
     */
    private $links = array();

    /**
     * $title
     * @var string
     */
    private $title;

    /**
     * $name
     * @var string
     */
    private $name;

    /**
     * $phoneNumbers
     * @var array
     */
    private $phoneNumbers = array();

    /**
     * $emails
     * @var array
     */
    private $emails = array();

    /**
     * $postalAddresses
     * @var array
     */
    private $postalAddresses = array();

    /**
     * $birthday
     * @var DateTime
     */
    private $birthday;

    /**
     * Custom fields
     * 
     * @var array
     */
    private $customFields = array();

    /**
     * The xml entry for a single contact
     * 
     * @var array
     */
    private $entry;

    /**
     * Constructor
     * @param array $entry 
     */
    public function __construct($entry=null)
    {
        if(!is_null($entry)) {
            //var_dump($entry);exit;
            //return;
            $this->etag = $entry['gd$etag'];
            $this->id = $entry['id']['$t'];
            $this->updated = new \DateTime($entry['updated']['$t']);

            $this->title = $entry['title']['$t'];

            foreach($entry['link'] as $link) {
                $l = new Entry\Link();
                $l->setRel($link['rel'])
                    ->setType($link['type'])
                    ->setHref($link['href']);
                $this->links[] = $l;
            }

            if(isset($entry['gContact$birthday'])) {
                $this->birthday = new \DateTime($entry['gContact$birthday']['when']);
            }

            if(isset($entry['gContact$userDefinedField'])) {
                $fields = $entry['gContact$userDefinedField'];
                $this->customFields = new Entry\CustomFields();
                foreach($fields as $field) {
                    $this->customFields[$field['key']] = $field['value'];
                }
            }

            if(isset($entry['gd$name'])) {
               $this->name = new Entry\Name($entry['gd$name']);
            }

            if(isset($entry['gd$phoneNumber'])) {
                foreach($entry['gd$phoneNumber'] as $el) {
                    //$type = substr($el['rel'], strpos($el['rel'], '#')+1);
                    $number = $el['$t'];
                    $primary = (isset($el['primary']) && $el['primary'] === 'true') ? true : false;
                    $this->phones[] = new Entry\PhoneNumber($el['rel'], $number, $primary);
                }
            }

            if(isset($entry['gd$email'])) {
                $el = $entry['gd$email'];
                foreach($el as $email) {
                    //$type = substr($email['rel'], strpos($email['rel'], '#')+1);
                    $address = $email['address'];
                    $primary = (isset($email['primary']) && $email['primary'] === 'true') ? true : false;
                    $this->emails[] = new Entry\Email($email['rel'], $address, $primary);
                }
            }

            if(isset($entry['gd$structuredPostalAddress'])) {
                foreach($entry['gd$structuredPostalAddress'] as $spa) {
                    $addr = new Entry\StructuredPostalAddress();
                    $addr->setPrimary((isset($spa['primary']) && $spa['primary'] === 'true'))
                        ->setRel($spa['rel'])
                        ->setFormattedAddress($spa['gd$formattedAddress']['$t'])
                        ->setStreet($spa['gd$street']['$t'])
                        ->setPostcode($spa['gd$postcode']['$t'])
                        ->setCity($spa['gd$city']['$t'])
                        ->setRegion($spa['gd$region']['$t'])
                        ->setCountry($spa['gd$country']['$t']);
                    $this->postalAddresses[] = $addr;
                }
            }
        }
    }

    /**
     * Get the etag
     * 
     * @return string
     */
    public function getEtag()
    {
        return $this->etag;
    }

    /**
     * Get the contact id
     * 
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }    

    /**
     * Get the time contact was updated
     * 
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Get edit url
     * 
     * @return string
     */
    public function getEditUrl()
    {
        return $this->editUrl;
    }

    /**
     * Get title
     * 
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the title
     * 
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get the date of birth
     * 
     * @return DateTime|null
     */
    public function getBirthday()
    {
        return $this->birthday;
    }
    
    /**
     * Set the date of birth
     * 
     * @param \DateTime $birthday
     */
    public function setBirthday(\DateTime $birthday)
    {
        $this->birthday = $birthday;
        return $this;
    }

    /**
     * Get custom fields
     * 
     * @return \Google\Contacts\Entry\CustomFields
     */
    public function getCustomFields()
    {
        return $this->customFields;
    }
    
    /**
     * [setCustomFields description]
     * 
     * @param \Google\Contacts\Entry\CustomFields $fields
     */
    public function setCustomFields(\Google\Contacts\Entry\CustomFields $customFields)
    {
        $this->customFields = $customFields;
        return $this;
    }

    /**
     * Get links
     * 
     * @return array
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * THIS IS PROBABLY NOT REQUIRED
     * 
     * @param [type] $links [description]
     */
    public function setLinks($links)
    {
        $this->links = $links;
        return $this;
    }

    /**
     * Get contact name object
     * 
     * @return \Google\Contacts\Entry\Name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the name components
     * 
     * @param Entry\Name $name
     */
    public function setName(Entry\Name $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get the contact phone numbers
     * 
     * @return array \Google\Contacts\Entry\PhoneNumber
     */
    public function getPhoneNumbers()
    {
        return $this->phoneNumbers;
    }

    /**
     * Set phone numbers
     * 
     * @param array $phoneNumbers
     */
    public function setPhoneNumbers(array $phoneNumbers)
    {
        $this->phoneNumbers = $phoneNumbers;
        return $this;
    }

    /**
     * Get the contact email information
     * 
     * @return array \Google\Contacts\Entry\Email
     */
    public function getEmails()
    {
        return $this->emails;
    }

    /**
     * Set emails
     * 
     * @param array $emails
     *
     * @return Entry
     */
    public function setEmails(array $emails)
    {
        $this->emails = $emails;
        return $this;
    }

    /**
     * Get postal addresses
     * 
     * @return array
     */
    public function getPostalAddresses()
    {
        return $this->postalAddresses;
    }

    /**
     * Set postal addresses
     * 
     * @param array $addresses
     */
    public function setPostalAddresses(array $addresses)
    {
        $this->postalAddresses = $addresses;
        return $this;
    }

    /**
     * Save this contact
     *
     * If the id of this entry is not null then anpu update will be performed
     * otherwise a new contact will be created
     * 
     * @return
     */
    public function save()
    {
        $adapter = new EntryToXmlAdapter();
        $post = $adapter->adapt($this);
        //var_dump($post);
        //exit;

        $serviceRequest = ServiceRequestFactory::getInstance();
        $request = $serviceRequest->getRequest(); /* @var $request \Google\Contacts\Request */
        $headers = array(
            'Content-Type' => 'application/atom+xml; charset=UTF-8; type=feed',
        );

        if(!is_null($this->id)) {
            $request->setMethod('PUT');
            $request->setFullUrl($this->id);
            $headers['If-Match'] = $this->etag;
        } else {
            $request->setEndpoint("default/full");
            $request->setMethod('POST');
        }

        $request->setPost($post);
        $request->setHeaders($headers);
        $res = $serviceRequest->execute();
        //var_dump($res);
    }
}