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
 * Contacts Service.
 *
 * @package    Google
 * @subpackage Contacts
 * @version    0.1
 * @author     Asim Liaquat <asimlqt22@gmail.com>
 */
class GroupFeed
{
    /**
     * 
     * @var array
     */
    private $feed;

    public function __construct($feed)
    {
        $this->feed = $feed;
    }

    public function getEntries()
    {
        $entries = array();

        if(isset($this->feed['feed']['entry']))
            if(count($this->feed['feed']['entry']) > 0)
                foreach ($this->feed['feed']['entry'] as $entry)
                    $entries[] = new GroupEntry($entry);

        return $entries;
    }

}