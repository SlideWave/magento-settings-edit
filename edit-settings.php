<?php
/*  Licensed to the Apache Software Foundation (ASF) under one
 *  or more contributor license agreements.  See the NOTICE file
 *  distributed with this work for additional information
 *  regarding copyright ownership.  The ASF licenses this file
 *   to you under the Apache License, Version 2.0 (the
 *  "License"); you may not use this file except in compliance
 *  with the License.  You may obtain a copy of the License at
 *   
 *    http://www.apache.org/licenses/LICENSE-2.0
 *   
 *  Unless required by applicable law or agreed to in writing,
 *  software distributed under the License is distributed on an
 *  "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 *  KIND, either express or implied.  See the License for the
 *  specific language governing permissions and limitations
 *  under the License.
 */

function applyChange(&$settings, $path, $newValue) {
    //split the path and walk the array
    $arr = &$settings;
    $walkingPath = explode(".", $path);

    $pathSize = count($walkingPath);

    for ($i = 0; $i < $pathSize - 1; $i++) {
        $pathPart = $walkingPath[$i];
        $arr = &$arr[$pathPart];
    }

    //when we get here we should be at the value. set it
    $arr[$walkingPath[$pathSize - 1]] = $newValue;
}

function startsWith($haystack,$needle,$case=true) {
    if ($case) {
        return strpos($haystack, $needle, 0) === 0;
    }

    return stripos($haystack, $needle, 0) === 0;
}

if (count($argv) < 4) {
    echo "usage: php edit-settings.php infile edits outfile";
    return;
}


$settings = require($argv[1]);
$changes;
if (strpos($argv[2], ";") !== FALSE) {
    $changes = explode(';', $argv[2]);
} else {
    $changes = array($argv[2]);
}

foreach ($changes as $change) {
    if (empty($change)) continue;
    
    //Change will be in the form a.b.c.d='' or a.b.c.d=X where X is an integer
    $pathAndValue = explode('=', $change);
    $value;
    if (startsWith($pathAndValue[1], "'")) {
        //string value
        $value = trim($pathAndValue[1], "'");
    } else {
        //integer value
        $value = (int)$pathAndValue[1];
    }

    applyChange($settings, $pathAndValue[0], $value);
}

//save the settings back to a file
file_put_contents($argv[3], "<?php return ".var_export($settings, TRUE).";");
