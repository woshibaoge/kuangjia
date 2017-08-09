<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="./static/bt3/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <table class="table table-hover">
        <tr>
            <td>ID</td>
            <td>标题</td>
            <td>点击次数</td>
            <td>操作</td>
        </tr>
        <?php foreach ( $arcData as $k => $arc ): ?>
            <tr>
                <td><?php echo $k + 1; ?></td>
                <td><?php echo $arc['title']; ?></td>
                <td><?php echo $arc['click']; ?></td>
                <td>
                    <a href="?s=home/entry/update&aid=<?php echo $arc['aid'] ?>" class="btn btn-warning btn-xs">编辑</a>
                    <a href="javascript:if(confirm('确定删除吗？')) location.href='?s=home/entry/remove&aid=<?php echo $arc['aid'] ?>';" class="btn btn-danger btn-xs">删除</a>
                </td>
            </tr>
        <?php endforeach ?>
    </table>
    <hr>
    <form action="" method="post" class="form-horizontal" role="form">
        <div class="form-group">
            <label for="inputID" class="col-sm-2 control-label">标题:</label>
            <div class="col-sm-10">
                <input type="text" name="title" id="inputID" class="form-control" value="" title="" required="required">
            </div>
        </div>
        <div class="form-group">
            <label for="inputID" class="col-sm-2 control-label">点击次数:</label>
            <div class="col-sm-10">
                <input type="text" name="click" id="inputID" class="form-control" value="" title="" required="required">
            </div>
        </div>
        <div class="form-group">
            <label for="inputID" class="col-sm-2 control-label">验证码:</label>
            <div class="col-sm-10">
                <input type="text" name="captcha" id="inputID" class="form-control" value="" title="" required="required">
                <img src="?s=home/entry/captcha&mt=<?php echo rand() ?>" alt="">

            </div>
        </div>


        <div class="form-group">
            <div class="col-sm-10 col-sm-offset-2">
                <button type="submit" class="btn btn-primary">添加</button>
            </div>
        </div>
    </form>
</div>

</body>
</html>