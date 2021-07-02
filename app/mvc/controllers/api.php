<?php
  /**
  * @package App\MVC\Controllers\API
  * api.php - api of app
  *
  * @version 1.0.0
  * @link https://github.com/MariaKHL/practica.lc.git
  * @copyright 2021 Khlystun Maria
  * @license MIT LICENSE Copyright (c) 2021 Khlystun Maria.
  * @NOTE: INNER DOC LANGUAGE IS RU;
  */

  namespace App\MVC\Controllers;

  if (!defined('DSE') || !defined('DIR_ROOT')) { exit('Access denied!'); }


  final class API
  {

    public function __construct($builder, $db, $template)
    {
      sleep(0.5);
      $builder::importOnce('@mvc/views/snippets/closeArticle.php');
      $title = $builder::preparePath('@mvc/views/snippets/titleArticle.php');
      $content = $builder::preparePath('@mvc/views/snippets/contentArticle.php');
      $article = $db->articles->findOne(['aid' => end(explode('/', $_GET['route']))]);
      if (!empty($article)) {
        $title  = str_replace('[[title]]', $article->title, file_get_contents($title));
        $content  = str_replace('[[content]]', $article->content, file_get_contents($content));
      }else {
        $title  = str_replace('[[title]]', 'Статья не найдена!', file_get_contents($title));
        $content  = '';
      }
      echo $title . $content;
    }
  }
?>
