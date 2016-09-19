<?php


class Model
{
    protected $db;
    public function __construct()
    {
        $f3 = Base::instance();
        $db = new \DB\SQL(
            $f3->get('db_dns') . $f3->get('db_name'),
            $f3->get('db_user'),
            $f3->get('db_pass')
        );
        $this->db = $db;
    }

    # получить дистрибьютора
    public function getDealer(int $id)
    {
        $res = $this->db->exec('SELECT id, name, pattern FROM dealer WHERE id =' . $id);
        return $res[0];
    }

    # список дистрибьюторов
    public function getDealers()
    {
        $res = $this->db->exec('SELECT id, name FROM dealer order by name');
        return $res;
    }

    # получить псевдоним для аптеки
    public function getPharmacyAlias(int $id)
    {
        $res = $this->db->exec('SELECT id, pharmacy_id, alias, dealer_id FROM pharmacy_alias WHERE id =' . $id);
        return $res[0];
    }

    # получить псевдоним для продукта
    public function getProductAlias(int $id)
    {
        $res = $this->db->exec('SELECT id, product_id, alias, dealer_id FROM product_alias WHERE id =' . $id);
        return $res[0];
    }

}