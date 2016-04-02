<?php
/**
 * MindTouch XArray
 *
 * Copyright (C) 2006-2016 MindTouch, Inc.
 * www.mindtouch.com  oss@mindtouch.com
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
include_once(dirname(__FILE__) . '/vendor/autoload.php');
spl_autoload_register(function($class) {
    $prefix = 'MindTouch\\XArray\\';
    $length = strlen($prefix);
    if(strncmp($prefix, $class, $length) !== 0) {
        return;
    }
    $relativeClass = substr($class, $length);
    $segments = explode('\\', $relativeClass);
    $path = dirname(__FILE__) . '/src/' . implode('/', $segments) . '.php';
    if(file_exists($path)) {

        /** @noinspection PhpIncludeInspection */
        include_once($path);
    }
});
