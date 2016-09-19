<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
<h1 class="page-header">Добавить препарат</h1>
<div class="row">
    <div class="col-xs-3" style="text-align: center;">
        <?
        if ($addSuccess)
        {
            ?>
            <div class="alert alert-success"><strong>Отлично!</strong> Препарат добавлен.</div>
            <?
        }
        elseif ($error)
        {
            ?>
            <div class="alert alert-danger"><strong>Ошибка!</strong> <? echo $error ?></div>
            <?
        }
        ?>
    </div>
</div>
<div class="row">
    <form action="" method="post">
        <div class="col-xs-2">
            <div id="upload" class="uploader">
                <input type="hidden" name="action" value="add">
                <input type="text" class="form-control" name="name" placeholder="название препарата">
            </div>
        </div>
        <div class="col-xs-1">
            <button type="submit" class="btn btn-success">Добавить</button>
        </div>
    </form>
</div>
</div>