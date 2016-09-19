<?php


class Product Extends Controller
{
    CONST VIEW = 'product';

    private $products;
    private $productId;
    private $product;

    public function __construct()
    {
        parent::__construct();
        $this->f3->set('menu_product', "class='active'");
        $this->products = $this->model->getproducts();
        $this->f3->set('products', $this->products);
        $this->productId = $this->f3->get('PARAMS.param');
        $this->product = (int)$this->productId ? $this->model->getproduct($this->productId) : false;
    }

    public function show()
    {
        if (empty($this->product)) $this->page404();
        $alias = $this->model->getAliasList($this->productId);
        $this->f3->set('alias', $alias);
        $this->f3->set('product', $this->product);
        $this->render();
    }

    public function add()
    {
        if ($_POST['action'] == 'add')
        {
            try
            {
                $name = trim($_POST['name']);
                if (empty($name)) throw new Exception('Не указано название продукта');
                $this->model->addProduct($name);
                $this->products = $this->model->getProducts();
                $this->f3->set('products', $this->products);
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


    public function savealias()
    {
        try
        {
            if (empty($this->product)) throw new Exception('Неверный продукт');
            $aliasId = (int)$_POST['alias'];
            $dealerId = (int)$_POST['dealer'];
            if (empty($aliasId)) throw new Exception('Не указан псевдоним');
            if (empty($dealerId)) throw new Exception('Не указан дистрибьютор');
            if (($aliasId > 0) && empty($this->model->getProductAlias($aliasId))) throw new Exception('Неверный псевдоним продукта');
            if (empty($this->model->getDealer($dealerId))) throw new Exception('Неверный дистрибьютор');
            $this->model->saveProductAlias($this->productId, $aliasId, $dealerId);
            $result = ['status' => 'success'];
        }
        catch(Exception $e)
        {
            $result = ['status' => 'error', 'message' => $e->getMessage()];
        }
        $this->sendResponse($result);
    }
}