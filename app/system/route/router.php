<?php
  /**
  * @package App\System\Route\Router
  * router.php - app routing
  *
  * @version 1.0.0
  * @link https://github.com/MariaKHL/practica.lc.git
  * @copyright 2021 Khlystun Maria
  * @license MIT LICENSE Copyright (c) 2021 Khlystun Maria.
  * @NOTE: INNER DOC LANGUAGE IS RU;
  */

  namespace App\System\Route;

  if (!defined('DSE') || !defined('DIR_ROOT')) { exit('Access denied!'); }


  final class Router
  {
    private const FILE_ROUTES = __DIR__ . DSE . 'routes.yaml';
    // согласно .htaccess
    private const GET_NAME  = 'route';

    private object $routes;
    private $url;
    private $get;
    private $post;

    public function __construct(?string $file_routes = NULL)
    {
      $this->routes = json_decode( json_encode(
        yaml_parse_file(
          (!empty($file_routes) && @is_file($file_routes)) ? $file_routes : self::FILE_ROUTES
      )));

      $this->url = $this->getCleanRequest($_GET[self::GET_NAME]);
      $this->get = ($this->url != false) ? (object) explode('/', $this->url) : false;
    }

    /**
    * @method startApp (@param object $builder, @param object $db )
    * @param string $builder  Экземпляр сборщика.
    * @param  @param object $db Экземпляр БД.
    * @todo Запускает фронт-контроллер приложения.
    * @return object
    */
    public function startApp(object $builder, object $db) : object
    {
      $app = (object) [];
      if ($this->get != false) {
        $app = (isset($this->routes->{$this->get->{0}})) ?
        $this->routes->{$this->get->{0}} :
        $this->routes->__string;
      }else {
        $app = $this->routes->__empty;
      }

      if ($app->autoload != false) {
        $builder->{$app->autoload};
      }

      if (class_exists($app->controller)) {
        $class = $app->controller;
        $controller = new $class($builder, $db, $app->template);
      }else {
        throw new \Exception(__CLASS__ . ": controller class {$app->controller} not found!", 1);
      }
      return $controller;
    }

    /**
    * @method getCleanRequest (@param string $data, @param bool|int $flag = false)
    * @param string $content Значние запроса.
    * @param bool|int $flag = false значение флага защиты. Если указан false то будет присвоен 2.
    * @todo Очищает содержимое запроса.
    * @return string|bool
    */
    protected function getCleanRequest(?string $data, $flag = false)
    {
      if ($flag === false) {
        $flag = 2;
      }
      $content = (object)
      [
        '<script','</script','<?','<?php','?>','src=',
        'src =','SELECT', 'FROM', 'WHERE','Select', 'From', 'Where',
        'select','$', 'from', 'where', '*', '_id', '__id', '\\', 'MongoDB',
        'BSON', 'mongodb', 'zips', 'Object', '->', '=>', 'INSERT', '</script>',
        'dba_insert', 'insertOne', 'insertMany', 'updateOne', 'updateMany',
      ];
      switch ($flag) {
        case 0:
          break;
        case 1:
          $data = strip_tags($data);
          break;
        case 2:
          $data = strip_tags($data);
          $data = htmlentities($data, ENT_QUOTES, "UTF-8");
          $data = htmlspecialchars($data, ENT_QUOTES);
          break;
        case 3:
          $data = strip_tags($data);
          $data = htmlentities($data, ENT_QUOTES, "UTF-8");
          $data = htmlspecialchars($data, ENT_QUOTES);
          foreach ($content as $key => $value) {
            $data = str_replace($value,"<pre>$value</pre>",$data);
          }
          break;
        case 4:
        $data = strip_tags($data);
        $data = htmlentities($data, ENT_QUOTES, "UTF-8");
        $data = htmlspecialchars($data, ENT_QUOTES);
        foreach ($content as $key => $value) {
          $data = str_replace($value," ::THIS_DANGER_CODE:: ",$data);
        }
          break;
        default:
        $data = strip_tags($data);
        $data = htmlentities($data, ENT_QUOTES, "UTF-8");
        $data = htmlspecialchars($data, ENT_QUOTES);
          break;
      }
      if (!empty($data)) {
        return($data);
      }else {
        return(false);
      }
    }
  }
?>
