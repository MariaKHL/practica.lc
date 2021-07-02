<?php
  /**
  * @package App\MVC\Controllers
  * router.php - app routing
  *
  * @version 1.0.0
  * @link https://github.com/MariaKHL/practica.lc.git
  * @copyright 2021 Khlystun Maria
  * @license MIT LICENSE Copyright (c) 2021 Khlystun Maria.
  * @NOTE: INNER DOC LANGUAGE IS RU;
  */

  namespace App\MVC\Controllers;

  if (!defined('DSE') || !defined('DIR_ROOT')) { exit('Access denied!'); }


  final class Website
  {

    public function __construct($builder, $db, $template)
    {
      global $builder_static;
      $builder_static = $builder;
      $builder::importOnce('@mvc/views/tmp/' . $template . '.php');
    }
  }
?>
