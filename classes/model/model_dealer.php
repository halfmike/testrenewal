<?php

/**
 * модель Дистрибьтор
 */
class Model_Dealer Extends Model
{

    /**
     * вгрузка данных для дистрибьютора
     * добавление псевдонимов
     */
    public function insertSupplyData($data, $dealerId, $file)
    {
        $sql = 'INSERT INTO supply SET dealer_id=' . $dealerId . ', date=SYSDATE(), file=\'' . $file . '\'';
        $this->db->exec($sql);
        $supplyId = $this->db->lastInsertId();

        foreach($data as $val)
        {
            $sql = 'SELECT id FROM pharmacy_alias WHERE dealer_id =' . $dealerId . ' AND alias=\'' . $val['pharmacy'] . '\'';
            $pharmacyAlias = $this->db->exec($sql);
            if (empty($pharmacyAlias))
            {
                $sql = 'INSERT INTO pharmacy_alias SET alias=\'' . $val['pharmacy'] . '\', dealer_id=' . $dealerId;
                $this->db->exec($sql);
                $pharmacyAliasId = $this->db->lastInsertId();
            }
            else
            {
                $pharmacyAliasId = $pharmacyAlias[0]['id'];
            }

            $sql = 'SELECT id FROM product_alias WHERE dealer_id =' . $dealerId . ' AND alias=\'' . $val['product'] . '\'';
            $prodAlias = $this->db->exec($sql);
            if (empty($prodAlias))
            {
                $sql = 'INSERT INTO product_alias SET alias=\'' . $val['product'] . '\', dealer_id=' . $dealerId;
                $this->db->exec($sql);
                $prodAliasId = $this->db->lastInsertId();
            }
            else
            {
                $prodAliasId = $prodAlias[0]['id'];
            }

            $sql = 'INSERT INTO supply_list SET supply_id=' . $supplyId . ', product_alias_id=' . $prodAliasId . ', pharmacy_alias_id=' . $pharmacyAliasId . ', count=' . $val['count'];
            $this->db->exec($sql);
        }
    }

    /**
     * все поставки для дистрибьютора
     */
    public function getSupplys($dealerId)
    {
        $sql = 'SELECT s.id, s.date, s.file, count(sl.id) as count
                FROM supply s
                INNER JOIN supply_list sl ON s.id=sl.supply_id
                WHERE s.dealer_id=' . $dealerId . '
                GROUP BY sl.supply_id
                ORDER BY s.date';
        $res = $this->db->exec($sql);
        return $res;
    }

    /**
     * имя файл поставки
     */
    public function getFileSupply($supplyId, $dealerId)
    {
        $sql = 'SELECT file FROM supply WHERE id=' . $supplyId . ' AND dealer_id=' . $dealerId;
        $res = $this->db->exec($sql);
        return $res[0]['file'];
    }

    /**
     * удаление поставки
     */
    public  function delSupply($supplyId, $dealerId)
    {
        $sql = 'SELECT id FROM supply WHERE id=' . $supplyId . ' AND dealer_id=' . $dealerId;
        $res = $this->db->exec($sql);
        if (empty($res)) throw new Exception('неверные данные о поставке');
        $sql = 'DELETE FROM supply_list WHERE supply_id=' . $supplyId;
        $this->db->exec($sql);
        $sql = 'DELETE FROM supply WHERE id=' . $supplyId . ' AND dealer_id=' . $dealerId;
        $this->db->exec($sql);
        $sql = 'DELETE FROM `pharmacy_alias` WHERE id NOT IN (
                    SELECT DISTINCT pharmacy_alias_id
                    FROM supply_list sl
                    INNER JOIN supply s ON s.id=sl.supply_id
                    WHERE s.dealer_id=' . $dealerId . '
                )
                AND dealer_id=' . $dealerId;
        $this->db->exec($sql);
        $sql = 'DELETE FROM product_alias WHERE id NOT IN (
                    SELECT DISTINCT product_alias_id
                    FROM supply_list sl
                    INNER JOIN supply s ON s.id=sl.supply_id
                    WHERE s.dealer_id=' . $dealerId . '
                )
                AND dealer_id=' . $dealerId;
        $this->db->exec($sql);
    }
}