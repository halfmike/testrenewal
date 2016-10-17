<?php

/**
 * контроллер Аптеки
 */
class Pharmacy Extends Controller
{
    CONST VIEW = 'pharmacy';

    private $pharmacys;
    private $pharmacyId;
    private $pharmacy;

    public function __construct()
    {
        parent::__construct();
        $this->f3->set('menu_pharmacy', "class='active'");
        $this->pharmacys = $this->model->getPharmacys();
        $this->f3->set('pharmacys', $this->pharmacys);
        $this->pharmacyId = $this->f3->get('PARAMS.param');
        $this->pharmacy = (int)$this->pharmacyId ? $this->model->getPharmacy($this->pharmacyId) : false;
    }

    /**
     * вывод данных по аптеке
     */
    public function show()
    {
        if (empty($this->pharmacy)) $this->page404();
        $alias = $this->model->getAliasList($this->pharmacyId);
        $this->f3->set('alias', $alias);
        $dealers = $this->model->getDealers();
        $this->f3->set('dealers', $dealers);
        $this->f3->set('pharmacy', $this->pharmacy);
        $this->render();
    }

    /**
     * добавление аптеки
     */
    public function add()
    {
        if ($_POST['action'] == 'add')
        {
            try
            {
                $name = trim($_POST['name']);
                if (empty($name)) throw new Exception('Не указано название аптеки');
                $this->model->addPharmacy($name);
                $this->pharmacys = $this->model->getPharmacys();
                $this->f3->set('pharmacys', $this->pharmacys);
                $this->f3->set('addSuccess', true);
            }
            catch(Exception $e)
            {
                $this->f3->set('error', $e->getMessage());
            }
        }
        $this->f3->set('add', true);
        $this->render();
    }

    /**
     * привязка к псевдониму
     */
    public function savealias()
    {
        try
        {
            $aliasId = (int)$_POST['alias'];
            $dealerId = (int)$_POST['dealer'];
            if (empty($this->pharmacy)) throw new Exception('Неверная аптека');
            if (empty($aliasId)) throw new Exception('Не указан псевдоним');
            if (empty($dealerId)) throw new Exception('Не указан дистрибьютор');
            if (($aliasId > 0) && empty($this->model->getPharmacyAlias($aliasId))) throw new Exception('Неверный псевдоним аптеки');
            if (empty($this->model->getDealer($dealerId))) throw new Exception('Неверный дистрибьютор');
            $this->model->savePharmacyAlias($this->pharmacyId, $aliasId, $dealerId);
            $result = ['status' => 'success'];
        }
        catch(Exception $e)
        {
            $result = ['status' => 'error', 'message' => $e->getMessage()];
        }
        $this->sendResponse($result);
    }

    /**
     * список продуктов, поставленных в аптеку
     */
    public function getsupplys()
    {
        try
        {
            if (empty($this->pharmacy)) throw new Exception('Неверная аптека');
            $dealerId = (int)$_POST['dealer'];
            if (($dealerId > 0) && empty($this->model->getDealer($dealerId))) throw new Exception('Неверный дистрибьютор');
            $supplys = $this->model->getSupplys($this->pharmacyId, $dealerId);
            $result = ['status' => 'success', 'supplys' => $supplys];
        }
        catch(Exception $e)
        {
            $result = ['status' => 'error', 'message' => $e->getMessage()];
        }
        $this->sendResponse($result);
    }
}