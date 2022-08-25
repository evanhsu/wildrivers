<?php
require_once(__DIR__ . "/classes/Config.php");
header('location: ' . ConfigService::getConfig()->app_url . '/admin/index.php');

