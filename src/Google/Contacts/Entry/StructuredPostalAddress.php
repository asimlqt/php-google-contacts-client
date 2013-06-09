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
class StructuredPostalAddress
{
    /**
     * phone number type.
     * 
     * @var string
     */
    private $rel = '';

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

    private $formattedAddress;
    private $street;
    private $neighborhood;
    private $postcode;
    private $city;
    private $region;
    private $country;

    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Get phone number type
     * 
     * @return string
     */
    public function getRel()
    {
        return $this->rel;
    }

    /**
     * Set the email type
     * 
     * @param string $type
     */
    public function setRel($rel)
    {
        $this->rel = $rel;
        return $this;
    }

    /**
     * Returns true if this is the contacts primary email address and
     * false if not.
     * 
     * @return boolean
     */
    public function getPrimary()
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
        return $this;
    }

    /**
     * Get email address
     * 
     * @return string
     */
    public function getFormattedAddress()
    {
        return $this->formattedAddress;
    }
    
    /**
     * Set the email address
     * 
     * @param string $address
     */
    public function setFormattedAddress($address)
    {
        $this->formattedAddress = $address;
        return $this;
    }

    /**
     * Returns true if this is the contacts primary email address and
     * false if not.
     * 
     * @return boolean
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set whether this email address is the primary address or not.
     * 
     * @param boolean $bool
     */
    public function setStreet($street)
    {
        $this->street = $street;
        return $this;
    }

    public function getNeighborhood()
    {
        return $this->neighborhood;
    }
    
    public function setNeighborhood($neighborhood)
    {
        $this->neighborhood = $neighborhood;
        return $this;
    }
    
    public function getPostcode()
    {
        return $this->postcode;
    }
    
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;
        return $this;
    }
    
    public function getCity()
    {
        return $this->city;
    }
    
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }
    
    public function getRegion()
    {
        return $this->region;
    }
    
    public function setRegion($region)
    {
        $this->region = $region;
        return $this;
    }
    
    public function getCountry()
    {
        return $this->country;
    }
    
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }
    
}