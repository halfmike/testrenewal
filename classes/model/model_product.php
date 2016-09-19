<?php


class Model_product Extends Model
{
    # список препаратов
    public function getProducts()
    {
        $res = $this->db->exec('SELECT id, name FROM product order by name');
        return $res;
    }

    # получить препарат
    public function getProduct($productId)
    {
        $res = $this->db->exec('SELECT id, name FROM product WHERE id=' . $productId);
        return $res[0];
    }

    # добавить препарат
    public function addProduct($name)
    {
        $res = $this->db->exec('SELECT id, name FROM product WHERE name=\'' . $name . '\'');
        if (!empty($res)) throw new Exception('Препарат с таким названием уже существует');
        $this->db->exec('INSERT INTO product SET name=\'' . $name . '\'');
    }

    # списки псевдонимов для препаратов разбитый по дистрибьюторам
    public function getAliasList($productId)
    {
        $sql = 'SELECT pa.id, pa.alias, pa.product_id, d.name, d.id dealer_id
                FROM product_alias pa
                INNER JOIN dealer d ON pa.dealer_id = d.id
                WHERE pa.product_id=' . $productId . ' OR pa.product_id is null
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

    # привязка псевдонима к продукту для конкретного дистрибьютора
    public function saveProductAlias($productId, $aliasId, $dealerId)
    {
        $sql = 'UPDATE product_alias SET product_id=null WHERE product_id=' . $productId . ' AND dealer_id=' . $dealerId;
        $this->db->exec($sql);
        if ($aliasId > 0)
        {
            $sql = 'UPDATE product_alias SET product_id=' . $productId . ' WHERE id=' . $aliasId . ' AND dealer_id=' . $dealerId;
            $this->db->exec($sql);
        }
    }
}