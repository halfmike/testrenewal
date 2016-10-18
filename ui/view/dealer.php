<script>
    function delSupply(obj)
    {
        var data = {
            'supply' : $(obj).attr('supply')
        }
        $.ajax({
            url: '/dealer/delsupply/<? echo $dealer['id'] ?>',
            type: 'POST',
            data: data,
            cache: false,
            dataType: 'json',
            beforeSend: function() {
                $('#supplystatus').html('<img src="images/loading.gif" style="width: 20px; margin: 15px">');
            },
            success: function( data ){
                if( data.status == 'success' ){
                    $('#supplyTab').html($('#tplSupply').render(data));
                    $('.delSupBtn').on('click', function() {
                        delSupply(this);
                    });
                    $('#supplystatus').html('<div class="alert alert-success"><strong>Отлично!</strong> Поставка удалена.</div>');
                }
                else if (data.status == 'error'){
                    $('#supplystatus').html('<div class="alert alert-danger"><strong>Ошибка!</strong> ' + data.message + '.</div>');
                }
                else
                {
                    $('#supplystatus').html('<div class="alert alert-danger"><strong>Неизвестная ошибка!</strong></div>');
                }
            }
        });
    }

    $(function(){
        $('#BSbtnsuccess').filestyle({
            buttonName : 'btn-success',
            buttonText : ' Файл'
        });

        // загрузка файла поставки
        // все загружается через ajax
        $('#uploadBtn').click(function(e){
            e.stopPropagation();
            e.preventDefault();

            var data = new FormData();
            data.append('supply', $('#BSbtnsuccess')[0].files[0]);
            $.ajax({
                url: '/dealer/upload/<? echo $dealer['id'] ?>',
                type: 'POST',
                data: data,
                cache: false,
                dataType: 'json',
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#loadstatus').html('<img src="images/loading.gif" style="width: 20px; margin: 15px">');
                },
                success: function( data ){
                    if( data.status == 'success' ){
                        // рендеринг шаблоны поставок
                        $('#supplyTab').html($('#tplSupply').render(data));
                        $('.delSupBtn').on('click', function() {
                            delSupply(this);
                        });
                        $('#loadstatus').html('<div class="alert alert-success"><strong>Отлично!</strong> Данные успешно загружены.</div>');
                    }
                    else if (data.status == 'error'){
                        $('#loadstatus').html('<div class="alert alert-danger"><strong>Ошибка!</strong> ' + data.message + '.</div>');
                    }
                    else
                    {
                        $('#loadstatus').html('<div class="alert alert-danger"><strong>Неизвестная ошибка!</strong></div>');
                    }
                }
            });
        });

        $('.delSupBtn').click(function() {
            delSupply(this);
        });
    })
</script>

<style>
    .pt20 {
        padding-top: 15px !important;
    }
</style>

<div class="col-sm-3 col-md-2 sidebar">
    <ul class="nav nav-sidebar">
        <?
        foreach ($dealers as $val)
        {
            ?>
            <li <? echo $PARAMS['param'] == $val['id'] ? 'class="active"' : '' ?>><a href="/dealer/show/<? echo $val['id'] ?>" class="list-group-item"><? echo $val['name'] ?></a></li>
            <?
        }
        ?>
    </ul>
</div>

<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
    <?
    if ($dealer)
    {
        ?>
            <h1 class="page-header"><? echo $dealer['name'] ?></h1>
                <h4>Импорт данных</h4>
                <div class="row">
                    <div id="loadstatus" class="col-xs-5" style="text-align: center;"></div>
                </div>
                <div class="row">
                    <div class="col-xs-4">
                        <div id="upload" class="uploader">
                        <form action="" enctype="multipart/form-data" method="post">
                            <input type="file" id="BSbtnsuccess" name="supply" style="width: 200px;">
                        </form>
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <button type="button" class="btn btn-success" id="uploadBtn">Загрузить</button>
                    </div>
                </div>
            <br><br><br>
            <h2 class="sub-header">Поставки</h2>
            <div class="row">
                <div id="supplystatus" class="col-xs-5" style="text-align: center;"></div>
            </div>
            <div class="col-xs-6">
            <div class="table-responsive" id="supplyTab">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Дата</th>
                        <th>Позиций</th>
                        <th>Файл</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?
                    if ($supplys)
                    {
                        foreach ($supplys as $val)
                        {
                            ?>
                            <tr>
                                <td class="pt20"><? echo $val['date'] ?></td>
                                <td class="pt20"><? echo $val['count'] ?></td>
                                <td class="pt20"><? echo $val['file'] ?></td>
                                <td><button type="button" class="btn btn-danger delSupBtn" style="width: 120px;" supply="<? echo $val['id'] ?>">Удалить</button></td>
                            </tr>
                            <?
                            $i++;
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            </div>
        <?
    }
    ?>
</div>

<script type="text/x-jsrender" id="tplSupply">
<table class="table table-striped">
    <thead>
    <tr>
        <th>Дата</th>
        <th>Позиций</th>
        <th>Файл</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    {{for supplys}}
    <tr>
        <td class="pt20">{{:date}}</td>
        <td class="pt20">{{:count}}</td>
        <td class="pt20">{{:file}}</td>
        <td><button type="button" class="btn btn-danger delSupBtn" style="width: 120px;" supply="{{:id}}">Удалить</button></td>
    </tr>
    {{/for}}
    </tbody>
</table>
</script>