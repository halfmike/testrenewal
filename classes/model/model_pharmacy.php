<?php

/**
 * модель Аптека
 */

class Model_Pharmacy Extends Model
{
    /**
     * получить список аптек
     */
    public function getPharmacys()
    {
        $res = $this->db->exec('SELECT id, name FROM pharmacy order by name');
        return $res;
    }

    /**
     * получить аптеку
     */
    public function getPharmacy($pharmacyId)
    {
        $res = $this->db->exec('SELECT id, name FROM pharmacy WHERE id=' . $pharmacyId);
        return $res[0];
    }

    /**
     * добавить аптеку
     */
    public function addPharmacy($name)
    {
        $res = $this->db->exec('SELECT id, name FROM pharmacy WHERE name=\'' . $name . '\'');
        if (!empty($res)) throw new Exception('Аптека с таким названием уже существует');
        $this->db->exec('INSERT INTO pharmacy SET name=\'' . $name . '\'');
    }

    /**
     * списки псевдонимов для аптек разбитый по дистрибьюторам
     */
    public function getAliasList($pharmacyId)
    {
        $sql = 'SELECT pa.id, pa.alias, pa.pharmacy_id, d.name, d.id dealer_id
                FROM pharmacy_alias pa
                INNER JOIN dealer d ON pa.dealer_id = d.id
                WHERE pa.pharmacy_id=' . $pharmacyId . ' OR pa.pharmacy_id is null
                ORDER BY d.id, pa.alias';
        $alias = $this->db->exec($sql);
        $res = [];
        foreach($alias as $val)
        {
            $res[$val['dealer_id']]['dealer_name'] = $val['name'];
            $res[$val['dealer_id']]['alias'][] = $val;
        }
        return $res;
    }

    /**
     * привязка псевдонима к аптеке для конкретного дистрибьютора
     */
    public function savePharmacyAlias($pharmacyId, $aliasId, $dealerId)
    {
        $sql = 'UPDATE pharmacy_alias SET pharmacy_id=null WHERE pharmacy_id=' . $pharmacyId . ' AND dealer_id=' . $dealerId;
        $this->db->exec($sql);
        if ($aliasId > 0)
        {
            $sql = 'UPDATE pharmacy_alias SET pharmacy_id=' . $pharmacyId . ' WHERE id=' . $aliasId . ' AND dealer_id=' . $dealerId;
            $this->db->exec($sql);
        }
    }

    /**
     * поставки препаратов для аптеки
     */
    public function getSupplys($pharmacyId, $dealerId)
    {
        $param = $dealerId ? ' AND pa.dealer_id=' . $dealerId . ' AND pha.dealer_id=' . $dealerId . ' ' : '';
        $sql = 'SELECT p.id, p.name, SUM(sl.count) as sum
                FROM product p
                INNER JOIN product_alias pa ON p.id=pa.product_id
                INNER JOIN supply_list sl ON sl.product_alias_id=pa.id
                INNER JOIN pharmacy_alias pha ON pha.id=sl.pharmacy_alias_id
                INNER JOIN pharmacy ph ON ph.id=pha.pharmacy_id
                WHERE ph.id=' . $pharmacyId .'
                ' . $param . '
                GROUP BY p.id
                ';
        $res = $this->db->exec($sql);
        return $res;
    }
}