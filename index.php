<?php
/**
 *
 * Copyright (C) 2008-2016 Scott Wakefield <scott@npclinics.com.au>
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 3
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;.
 *
 * @package OpenEMR
 * @author  Scott Wakefield <scott@npclinics.com.au>
 * @link    http://www.open-emr.org
 */

require_once __DIR__.'/vendor/autoload.php';

use OpenEMR\Framework\Http\Request;
use OpenEMR\Framework\Http\Response;

$request = Request::createFromGlobals();
$response = new Response();

if (!empty($_GET['site']))
    $site_id = $_GET['site'];
else if (is_dir("sites/" . $_SERVER['HTTP_HOST']))
    $site_id = $_SERVER['HTTP_HOST'];
else
    $site_id = 'default';

if (empty($site_id) || preg_match('/[^A-Za-z0-9\\-.]/', $site_id))
    die("Site ID '".htmlspecialchars($site_id,ENT_NOQUOTES)."' contains invalid characters.");

require_once __DIR__.'/sites/'.$site_id.'/sqlconf.php';

if ($config == 1) {
    $response->setStatusCode(302);
    $response->headers->set('Location', 'interface/login/login.php?site='.$site_id);
} else {
    $response->setStatusCode(302);
    $response->headers->set('Location', 'setup.php?site='.$site_id);
}

$response->send();
