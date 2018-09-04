<form action="login/check" method="post">
    <!-- login/check意味着我们待会要用到控制器Login的check函数 -->
    name: <input type="text" name="u_name">
    password: <input type="password" name="u_pw">
    <input type="submit" name="submit" value="submit">
</form>

<a href="login/logout">退出</a>
<!-- 调用控制器Login的logout函数 -->