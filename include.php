<?php

define('ROOT', __DIR__);
require_once(ROOT.'/classes/Comment.php');
require_once(ROOT.'/classes/News.php');
require_once(ROOT.'/classes/utils/Config.php');
require_once(ROOT.'/classes/utils/CommentManager.php');
require_once(ROOT.'/classes/utils/DB.php');
require_once(ROOT.'/classes/utils/HttpResponseHelper.php');
require_once(ROOT.'/classes/utils/NewsManager.php');
require_once(ROOT.'/classes/exception/CommentManagerException.php');
require_once(ROOT.'/classes/exception/NewsManagerException.php');
require_once(ROOT.'/classes/exception/NotFoundException.php');
