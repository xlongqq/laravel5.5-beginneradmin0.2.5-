<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>layui</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/plugins/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="/css/global.css" media="all">
</head>

<body>
<div class="layui-fluid main">
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        <legend>权限列表</legend>
    </fieldset>
    <div class="add_div">
        <input id="add_permission" type="button" style="float: right;margin-bottom: 10px;" class="layui-btn layui-btn" value="新增权限">
    </div>

    <div class="layui-form">
        <table class="layui-table">
            <colgroup>
                <col width="50">
                <col width="250">
                <col width="250">
                <col width="100">
                <col width="50">
                <col width="100">
                <col width="250">
            </colgroup>
            <thead>
                <tr>
                    <th>id</th>
                    <th>权限路由</th>
                    <th>权限名称</th>
                    <th>图标</th>
                    <th>父级id</th>
                    <th>排序</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
            @foreach($permissions as $item)
                <tr>
                    <td>{{ $item['id'] }}</td>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['label'] }}</td>
                    <td>
                        @if(!empty($item['icon']))
                            @if($item['cid']==0)
                                <i class="fa {{ $item['icon'] }}" aria-hidden="true"></i>
                            @else
                                <i class="layui-icon">{{$item['icon']}}</i>
                            @endif
                        @endif
                    </td>
                    <td>{{ $item['cid'] }}</td>
                    <td>{{ $item['sort'] }}</td>
                    <td>
                        <button class="layui-btn config-btn" config_id="{{ $item['id'] }}">编辑</button>
                        <button class="layui-btn destroy-btn" config_id="{{ $item['id'] }}">删除</button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $permissions->links() }}
    </div>
</div>
<script src="/plugins/layui/layui.js"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
<script>
    layui.use(['form', 'layedit', 'laydate'], function() {
        var form = layui.form(),
            $ = layui.jquery,
            layer = layui.layer;

        $('#add_permission').click(function () {
            layer.open({
                type: 2,
                title: '新增权限',
                shadeClose: true,
                shade: 0.8,
                area: ['50%', '80%'],
                maxmin: true,
                content: '{{ route("manager.permission.add") }}',
            });
        });

        $('.config-btn').click(function () {
            var config_id = $(this).attr('config_id');
            layer.open({
                type: 2,
                title: '权限编辑',
                shadeClose: true,
                shade: 0.8,
                area: ['50%', '80%'],
                maxmin: true,
                content: '{{ route("manager.permission.edit") }}?id='+config_id,
            });
        });

        $('.destroy-btn').click(function () {
            var config_id = $(this).attr('config_id');
            layer.confirm('确定删除吗？', function () {
                $.ajax({
                    type: "POST",
                    url: "{{ route('manager.permission.del') }}",
                    data: {id:config_id},
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    dataType: "json",
                    success: function(res){
                        layer.msg(res.msg, {time:1100});
                        if (res.success) {
                            location.reload();
                        }
                    }
                });
            });
        });
    });
</script>

</body>

</html>