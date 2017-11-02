<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>layui</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/plugins/layui/css/layui.css" media="all">
</head>

<body>

<div class="layui-fluid main">
    <fieldset class="layui-elem-field layui-field-title">
        <legend>新增子账号</legend>
    </fieldset>
    <form class="layui-form layui-form-pane" action="">
        {{ csrf_field() }}
        <div class="layui-form-item">
            <label class="layui-form-label">登录账号</label>
            <div class="layui-input-block">
                <input type="text" name="username" value="" lay-verify="required" placeholder="请输入登录账号" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">登录密码</label>
            <div class="layui-input-block">
                <input type="password" name="password" value="" lay-verify="required" placeholder="请输入登录密码" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">确认密码</label>
            <div class="layui-input-block">
                <input type="password" name="password_confirmation" value="" lay-verify="required" placeholder="请输入确认密码" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">权限组</label>
            <div class="layui-input-block">
                <select name="role_id" lay-filter="aihao">
                    <option value="">请选择</option>
                    @foreach($role_list as $item)
                    <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <button class="layui-btn" lay-submit="" lay-filter="demo1">提交</button>
        </div>
    </form>
</div>
<script src="/plugins/layui/layui.js"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
<script>
    layui.use(['form', 'layedit', 'laydate'], function() {
        var form = layui.form(),
            layer = layui.layer,
            $ = layui.jquery,
            laydate = layui.laydate;

        //监听提交
        form.on('submit(demo1)', function(data) {
            var index = layer.load(1, {
                shade: [0.1,'#fff'] //0.1透明度的白色背景
            });
            console.log(data.field);
            $.ajax({
                type: "POST",
                url: "{{ route('manager.accounts.add') }}",
                data: data.field,
                dataType: "json",
                success: function(res){
                    layer.close(index);
                    layer.msg(res.msg, {time:1100});
                    if (res.success) {
                        parent.location.reload();
                    }
                }
            });
            return false;
        });

    });
</script>

</body>

</html>