<script>
    $(function() {
        $('.alias').click(function() {
            var data = {
                'dealer' : $(this).attr('name'),
                'alias' : $(this).val()
            }
            $.ajax({
                url: '/product/savealias/<? echo $product['id'] ?>',
                type: 'POST',
                data: data,
                cache: false,
                dataType: 'json',
                beforeSend: function() {
                    $('#status').html('<img src="images/loading.gif" style="width: 20px; margin: 15px">');
                },
                success: function( data ){
                    if( data.status == 'success' ){
                        $('#status').html('<div class="alert alert-success"><strong>Отлично!</strong> Соответствие сохранено.</div>');
                    }
                    else if (data.status == 'error'){
                        $('#status').html('<div class="alert alert-danger"><strong>Ошибка!</strong> ' + data.message + '.</div>');
                    }
                    else
                    {
                        $('#status').html('<div class="alert alert-danger"><strong>Неизвестная ошибка!</strong></div>');
                    }
                }
            });
        });
    })
</script>
<style>
    input, label {
        cursor: pointer;
    }
</style>
<div class="col-sm-3 col-md-2 sidebar">
    <ul class="nav nav-sidebar">
        <?
        foreach ($products as $val)
        {
            ?>
            <li <? echo $PARAMS['param'] == $val['id'] ? 'class="active"' : '' ?>><a href="/product/show/<? echo $val['id'] ?>" class="list-group-item"><? echo $val['name'] ?></a></li>
            <?
        }
        ?>
    </ul>
    <ul class="nav nav-sidebar">
        <li <? echo $add == true ? 'class="active"' : '' ?>><a href="/product/add" class="list-group-item">добавить</a></li>
    </ul>
</div>
<?
    if ($product)
    {
        ?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <h1 class="page-header"><? echo $product['name'] ?></h1>
        <h3 class="sub-header">Псевдонимы продукта</h3>
            <div class="row">
                <div id="status" class="col-xs-5" style="text-align: center;"></div>
            </div>
        <div class="col-xs-8">
            <form action="" method="post">
            <?
            foreach($alias as $key => $dealer)
            {
            ?>
                <div class="col-xs-3">
                <h4 style="color: blue;"><? echo $dealer['dealer_name'] ?></h4>
                <label style="color: red;"><input type="radio" style="width: 25px;" name="<? echo $key ?>" value="-1" class="alias" checked="checked">нет соответствия</label>
            <?
                foreach($dealer['alias'] as $val)
                {
                    ?>
                        <label><input <? echo $PARAMS['param'] == $val['product_id'] ? 'checked="checked"' : '' ?> type="radio" class="alias" style="width: 25px;" name="<? echo $key ?>" value="<? echo $val['id'] ?>"><? echo $val['alias'] ?></label>
                        <br>
                    <?
                }
            ?>
            </div>
            <?
            }
            ?>
            </form>
        </div>
        </div>
        <?
    }
    echo $add ? View::instance()->render('view/product_add.php') : '';
?>