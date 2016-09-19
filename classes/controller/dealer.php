<?php


class Dealer Extends Controller
{
    CONST VIEW = 'dealer';
    CONST SUPPLY_FILE_PATH = 'files/supply/';
    private $dealers;
    private $dealerId;
    private $dealer;

    public function __construct()
    {
        parent::__construct();
        $this->f3->set('menu_dealer', "class='active'");
        $this->dealers = $this->model->getDealers();
        $this->f3->set('dealers', $this->dealers);
        $this->dealerId = $this->f3->get('PARAMS.param');
        $this->dealer = (int)$this->dealerId ? $this->model->getDealer($this->dealerId) : false;
    }

    public function show()
    {
        if (empty($this->dealer)) $this->page404();
        $this->f3->set('dealers', $this->dealers);
        $this->f3->set('dealer', $this->dealer);
        $supplys = $this->model->getSupplys($this->dealerId);
        $this->f3->set('supplys', $supplys);
        $this->render();
    }

    # вгрузка поставки
    public function upload()
    {
        $result = [];
        try
        {
            if (empty($this->dealer)) throw new Exception('Неверный дистрибьютор');
            if (empty($_FILES['supply'])) throw new Exception('файл отсутствует');
            if ($_FILES['supply']["type"] != 'text/plain') throw new Exception('неверный формат файла');
            $fname = 'files/tmp/' . $_FILES['supply']['name'];
            move_uploaded_file($_FILES['supply']['tmp_name'], $fname);
            $correctData = $this->parseSupplyFile($fname);
            $supplyFileName = 'd' . $this->dealerId . '_' . date('Y-m-d-H_i_s') . '.txt';
            copy($fname, $this::SUPPLY_FILE_PATH . $supplyFileName);
            unlink($fname);

            $this->model->insertSupplyData($correctData, $this->dealerId, $supplyFileName);

            $supplys = $this->model->getSupplys($this->dealerId);
            $result = ['status' => 'success', 'supplys' => $supplys];
        }
        catch(Exception $e)
        {
            $result = ['status' => 'error', 'message' => $e->getMessage()];
        }
        $this->sendResponse($result);
    }

    # парсинг файла поставки
    private function parseSupplyFile($file)
    {
        if (!file_exists($file)) throw new Exception('файл отсутствует');
        $data = file($file);
        unset($data[0]);
        if (empty($data)) throw new Exception('данные отсутствуют');
        $pattern = explode('|', $this->dealer['pattern']);
        $correctData = [];
        foreach($data as $val)
        {
            $res = array_combine($pattern, explode(chr(9), $val));
            if (empty($res['product'] || empty($res['product'] || !(int)$res['count']))) throw new Exception('некорректные данные в файле');
            $correctData[] = $res;
        }
        return $correctData;
    }

    # удаление поставки
    public function delsupply()
    {
        $result = [];
        try
        {
            if (empty($this->dealer)) throw new Exception('Неверный дистрибьютор');
            $supplyId = (int)$_POST['supply'];
            if (!$supplyId) throw new Exception('не указана поставка');
            $file = $this->model->getFileSupply($supplyId, $this->dealerId);
            if (!empty($file) && file_exists($this::SUPPLY_FILE_PATH . $file)) unlink($this::SUPPLY_FILE_PATH . $file);
            $this->model->delSupply($supplyId, $this->dealerId);
            $supplys = $this->model->getSupplys($this->dealerId);
            $result = ['status' => 'success', 'supplys' => $supplys];
        }
        catch(Exception $e)
        {
            $result = ['status' => 'error', 'message' => $e->getMessage()];
        }
        $this->sendResponse($result);
    }
}