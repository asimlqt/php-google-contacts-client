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
 * Autoloads classes in this package.
 *
 * @package    Google
 * @subpackage Contacts
 * @version    0.1
 * @author     Asim Liaquat <asimlqt22@gmail.com>
 */
class Autoloader
{
    private $src;

    public function __construct()
    {
        $this->src = realpath(dirname(__FILE__) . '/../..') . '/';
    }

    private $classmap = array(
        'Google\\Contacts\\Request' => 'Google/Contacts/Request',
        'Google\\Contacts\\ServiceRequestInterface' => 'Google/Contacts/ServiceRequestInterface',
        'Google\\Contacts\\DefaultServiceRequest' => 'Google/Contacts/DefaultServiceRequest',
        'Google\\Contacts\\Exception' => 'Google/Contacts/Exception',
        'Google\\Contacts\\ServiceRequestFactory' => 'Google/Contacts/ServiceRequestFactory',
        'Google\\Contacts\\Util' => 'Google/Contacts/Util',
        'Google\\Contacts\\Service' => 'Google/Contacts/Service',
        'Google\\Contacts\\ContactEntry' => 'Google/Contacts/ContactEntry',
        'Google\\Contacts\\GroupEntry' => 'Google/Contacts/GroupEntry',
        'Google\\Contacts\\Entry\\Name' => 'Google/Contacts/Entry/Name',
        'Google\\Contacts\\Entry\\Link' => 'Google/Contacts/Entry/Link',
        'Google\\Contacts\\Entry\\Email' => 'Google/Contacts/Entry/Email',
        'Google\\Contacts\\Entry\\PhoneNumber' => 'Google/Contacts/Entry/PhoneNumber',
        'Google\\Contacts\\Entry\\StructuredPostalAddress' => 'Google/Contacts/Entry/StructuredPostalAddress',
        'Google\\Contacts\\Entry\\CustomFields' => 'Google/Contacts/Entry/CustomFields',
        'Google\\Contacts\\Entry\\ExtendedProperty' => 'Google/Contacts/Entry/ExtendedProperty',
        'Google\\Contacts\\ContactFeed' => 'Google/Contacts/ContactFeed',
        'Google\\Contacts\\GroupFeed' => 'Google/Contacts/GroupFeed',
        'Google\\Contacts\\Constants' => 'Google/Contacts/Constants',
    );

    public function autoload($cls)
    {
        if(array_key_exists($cls, $this->classmap))
            require_once $this->src . $this->classmap[$cls] . '.php';
    }
}

spl_autoload_register(array(new Autoloader(), 'autoload'));