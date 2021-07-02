<?php

  if (!defined('DSE') || !defined('DIR_ROOT')) { exit('Access denied!'); }
  global $builder_static;

  $builder_static::importOnce('@mvc/views/sections/base/head.php');
  $builder_static::importOnce('@mvc/views/sections/base/nav_desktop.php');
  $builder_static::importOnce('@mvc/views/sections/base/nav_mobile.php');
  $builder_static::importOnce('@mvc/views/sections/homepage.php');
  $builder_static::importOnce('@mvc/views/sections/base/footer.php');
?>
