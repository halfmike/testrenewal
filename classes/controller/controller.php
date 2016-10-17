<?php


/**
 * Базовый класс контроллер
 */
class Controller
{
    protected $model;
    protected $f3;
    protected $layout = 'layout.html';
    CONST VIEW = 'index';

    public function __construct()
    {
        $model = 'Model' . (get_class($this) != __CLASS__ ? '_' . get_class($this) : '');
        $this->model = new $model;
        $this->f3 = Base::instance();
    }

    public function index()
    {
        $this->render();
    }

    /**
     * рендеринг страницы
     */
    protected function render()
    {
        $this->f3->set('content', View::instance()->render('view/' . $this::VIEW . '.php'));
        echo View::instance()->render($this->f3->get('layout'));
        exit;
    }

    /**
     * отправка результата через ajax в формате json
     */
    protected function sendResponse($result)
    {
        echo json_encode($result);
        exit;
    }

    public function page404()
    {
        $this->f3->set('content', View::instance()->render('view/page404.php'));
        echo View::instance()->render($this->f3->get('layout'));
        exit;
    }
}