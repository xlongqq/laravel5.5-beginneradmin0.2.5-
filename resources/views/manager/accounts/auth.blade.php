<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>layui</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/plugins/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="/css/ztree-metro-style.css">
    <script src="/plugins/layui/layui.js"></script>
    <script src="/js/jquery-1.9.1.js"></script>
    <script src="/js/jquery.ztree.all.min.js"></script>
</head>

<body>

<div class="layui-fluid main">
    <fieldset class="layui-elem-field layui-field-title">
        <legend>授权</legend>
    </fieldset>
    <input type="hidden" id="group_id" name="id" value="{{ Request::input('id') }}">

    <div class="layui-tab-content">
        <div class="layui-tab-item layui-show">
            <ul id="tree" class="ztree"></ul>
        </div>
        <a id="gameAdd" class="layui-btn" style="float: left; margin-left: 20px;">授权</a>
    </div>
</div>
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
<script>
    layui.use(['form', 'layedit', 'laydate'], function() {
        var form = layui.form(),
            layer = layui.layer,
            $ = layui.jquery,
            laydate = layui.laydate;

        /**
         * 加载树形授权菜单
         */
        var _id = $("#group_id").val();
        var tree = $("#tree");
        var zTree;

        // zTree 配置项
        var setting = {
            check: {
                enable: true
            },
            view: {
                dblClickExpand: false,
                showLine: true,
                showIcon: false,
                selectedMulti: false
            },
            data: {
                simpleData: {
                    enable: true,
                    idKey: "id",
                    pIdKey: "cid",
                    rootpid: ""
                },
                key: {
                    name: "label"
                }
            }
        };

        $.ajax({
            url: "{{ route('manager.accounts.modifyLimit') }}?act=getPermissions",
            type: "post",
            dataType: "json",
            headers:{
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            cache: false,
            data: {
                id: _id
            },
            success: function (data) {
                zTree = $.fn.zTree.init(tree, setting, data);
            }
        });

        /**
         * 授权提交
         */
        $("#gameAdd").on("click", function () {
            var checked_ids,auth_rule_ids = [];
            checked_ids = zTree.getCheckedNodes(); // 获取当前选中的checkbox
            $.each(checked_ids, function (index, item) {
                auth_rule_ids.push(item.id);
            });
            $.ajax({
                url: "{{ route('manager.accounts.modifyLimit') }}?act=setPermissions",
                type: "post",
                headers:{
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                cache: false,
                data: {
                    id: _id,
                    auth_rule_ids: auth_rule_ids
                },
                dataType:'json',
                success: function (data) {
                    if(data.success){
                        layer.msg('授权成功');
                        setTimeout(function () {
                            history.go(0);
                        }, 1000);
                    }else{
                        layer.msg(data.message);
                    }
                }
            });
        });

    });
</script>

</body>

</html>