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
namespace Google\Contacts\Entry;

/**
 * Contacts Service.
 *
 * @package    Google
 * @subpackage Contacts
 * @version    0.1
 * @author     Asim Liaquat <asimlqt22@gmail.com>
 */
class Email
{
    /**
     * phone number type.
     * 
     * @var string
     */
    private $type = '';

    /**
     * Email address
     * 
     * @var string
     */
    private $address = '';

    /**
     * Whether this is the contacts primary email address or not
     * 
     * @var boolean
     */
    private $primary;

    /**
     * Constructor
     */
    public function __construct($type, $address, $primary)
    {
        $this->type = $type;
        $this->address = $address;
        $this->primary = $primary;
    }

    /**
     * Get phone number type
     * 
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the email type
     * 
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get email address
     * 
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }
    
    /**
     * Set the email address
     * 
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * Returns true if this is the contacts primary email address and
     * false if not.
     * 
     * @return boolean
     */
    public function isPrimary()
    {
        return $this->primary;
    }

    /**
     * Set whether this email address is the primary address or not.
     * 
     * @param boolean $bool
     */
    public function setPrimary($bool)
    {
        $this->primary = $bool;
    }
    
}