<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>后台首页</title>
</head>
<body>
    <ul>
        <li><a href="#kf_list">客服列表</a> </li>
        <li><a href="#kf_add">添加客服</a> </li>
        <li><a href="#kf_del">删除客服</a> </li>
    </ul>
    <div id="kf_list">
        <form action="/admin/addKF" method="post">
            前缀:<input type="text" name="account"></br>
            昵称:<input type="text" name="nickname"></br>
            密码:<input type="password" name="password"></br>
            <input type="submit"></br>
        </form>
    </div>

    <div>
        <form action="/admin/">

        </form>
    </div>

</body>
</html>