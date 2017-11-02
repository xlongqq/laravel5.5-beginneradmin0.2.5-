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
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        <legend>权限组列表</legend>
    </fieldset>
    <div class="add_div">
        <input id="add_accounts" type="button" style="float: right;margin-bottom: 10px;" class="layui-btn layui-btn" value="新增权限组">
    </div>

    <div class="layui-form">
        <table class="layui-table">
            <colgroup>
                <col width="250">
                <col width="250">
                <col width="250">
                <col width="200">
            </colgroup>
            <thead>
                <tr>
                    <th>权限组名称</th>
                    <th>创建时间</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
            @foreach($role_list as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['created_at'] }}</td>
                    <td>
                        <button class="layui-btn config-btn {{ $item['id']==1?'layui-btn-disabled':'' }}" {{ $item['id']==1?'disabled':'' }} config_id="{{ $item['id'] }}">编辑</button>
                        <button class="layui-btn destroy-btn {{ $item['id']==1?'layui-btn-disabled':'layui-btn-danger' }}" {{ $item['id']==1?'disabled':'' }} config_id="{{ $item['id'] }}">授权</button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<script src="/plugins/layui/layui.js"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
<script>
    layui.use(['form', 'layedit', 'laydate'], function() {
        var form = layui.form(),
            $ = layui.jquery,
            layer = layui.layer;

        $('#add_accounts').click(function () {
            layer.open({
                type: 2,
                title: '新增权限组',
                shadeClose: true,
                shade: 0.8,
                area: ['50%', '80%'],
                maxmin: true,
                content: '{{ route("manager.accounts.addGroup") }}',
            });
        });

        $('.config-btn').click(function () {
            var config_id = $(this).attr('config_id');
            layer.open({
                type: 2,
                title: '权限组编辑',
                shadeClose: true,
                shade: 0.8,
                area: ['50%', '80%'],
                maxmin: true,
                content: '{{ route("manager.accounts.editGroup") }}?id='+config_id,
            });
        });

        $('.destroy-btn').click(function () {
            var config_id = $(this).attr('config_id');
            layer.open({
                type: 2,
                title: '权限组授权',
                shadeClose: true,
                shade: 0.8,
                area: ['50%', '80%'],
                maxmin: true,
                content: '{{ route("manager.accounts.modifyLimit") }}?id='+config_id,
            });
        });
    });
</script>

</body>

</html>