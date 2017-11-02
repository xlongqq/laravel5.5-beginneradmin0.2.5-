<?php

namespace App\Http\Controllers\Manager;

use App\Models\Manager;
use App\Models\ManagerRole;
use App\Models\ManagerRoleUser;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AccountsController extends Controller
{
    public function index(Request $request)
    {
        $accounts_list = Manager::query()->with('roles')->paginate(10);
        return view('manager.accounts.index', [
            'accounts_list' => $accounts_list,
        ]);
    }

    public function addAccount(Request $request)
    {
        $role_list = ManagerRole::query()->where('id', '<>', 1)->get();
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'username' => 'required|min:5|max:50|unique:managers,username',
                'password' => 'required|min:6|max:30|confirmed',
                'role_id' => 'required|numeric|exists:manager_roles,id'
            ], [
                'username.required'  => '请输入登录账号',
                'username.min'  => '登录账号最少5个字符',
                'username.max'  => '登录账号最长50个字符',
                'username.unique'  => '登录账号已存在',
                'password.required'  => '请输入登录密码',
                'password.min'  => '登录密码最少6个字符',
                'password.max'  => '登录密码最长30个字符',
                'password.confirmed'  => '确认密码有误',
                'role_id.required'  => '请选择权限组',
                'role_id.numeric'  => '权限组参数类型错误',
                'role_id.exists'  => '权限组不存在',
            ]);

            if ($validator->fails()) {
                $errors = ['success' => false,'msg'=>$validator->errors()->first()];
                return $errors;
            }

            try {
                DB::begintransaction();
                $account = new Manager();
                $account->username = $request->input('username');
                $account->password = bcrypt($request->input('password'));
                if (!$account->save()) {
                    throw new \Exception('添加失败');
                }
                $role_user = new ManagerRoleUser();
                $role_user->role_id = $request->input('role_id');
                $role_user->user_id = $account->id;
                if (!$role_user->save()) {
                    throw new \Exception('权限赋予失败');
                }
                DB::commit();
            } catch (\Exception $e){
                DB::rollBack();
                return ['success' => false,'msg'=>$e->getMessage()];
            }
            return ['success' => true,'msg'=>'添加成功'];
        }
        return view('manager.accounts.add', [
            'role_list' => $role_list,
        ]);
    }

    public function editAccount(Request $request)
    {
        $role_list = ManagerRole::query()->where('id', '<>', 1)->get();
        $account =Manager::query()->with('roles')->find($request->input('id'));
        if (empty($account)) {
            return ['success' => false,'msg'=>'子账号不存在'];
        }
        if ($request->isMethod('post')) {

            if (!empty($request->input('password'))) {

                $validator = Validator::make($request->all(), [
                    'password' => 'min:6|max:30',
                ], [
                    'password.min'  => '登录密码最少6个字符',
                    'password.max'  => '登录密码最长30个字符',
                ]);

                if ($validator->fails()) {
                    $errors = ['success' => false,'msg'=>$validator->errors()->first()];
                    return $errors;
                }
            }

            $validator = Validator::make($request->all(), [
                'role_id' => 'required|numeric|exists:manager_roles,id'
            ], [
                'role_id.required'  => '请选择权限组',
                'role_id.numeric'  => '权限组参数类型错误',
                'role_id.exists'  => '权限组不存在',
            ]);

            if ($validator->fails()) {
                $errors = ['success' => false,'msg'=>$validator->errors()->first()];
                return $errors;
            }

            try {
                DB::begintransaction();
                if (!empty($request->input('password'))) {
                    $account->password = bcrypt($request->input('password'));
                    if (!$account->save()) {
                        throw new \Exception('编辑失败');
                    }
                }
                if ($request->input('role_id') != $account['roles'][0]['id']) {
                    if (!ManagerRoleUser::query()->where('user_id', $account->id)->delete()) {
                        throw new \Exception('权限变更失败');
                    }
                    $role_user = new ManagerRoleUser();
                    $role_user->role_id = $request->input('role_id');
                    $role_user->user_id = $account->id;
                    if (!$role_user->save()) {
                        throw new \Exception('权限变更失败');
                    }
                }
                DB::commit();
            } catch (\Exception $e){
                DB::rollBack();
                return ['success' => false,'msg'=>$e->getMessage()];
            }
            return ['success' => true,'msg'=>'编辑成功'];
        }
        return view('manager.accounts.edit', [
            'role_list' => $role_list,
            'account' => $account,
        ]);
    }

    public function destroyAccount(Request $request)
    {
        $id = $request->input('id');
        try {
            DB::begintransaction();
            $result = Manager::query()->where('id', $id)->delete();
            if (!$result) {
                throw new \Exception('操作失败');
            }
            $result = ManagerRoleUser::query()->where('user_id', $id)->delete();
            if (!$result) {
                throw new \Exception('操作失败');
            }
            DB::commit();
        } catch (\Exception $e){
            DB::rollBack();
            return ['success' => false,'msg'=>$e->getMessage()];
        }
        return ['success' => true,'msg'=>'操作成功'];
    }

    public function groups(Request $request)
    {
        $role_list = ManagerRole::query()->get();
        return view('manager.accounts.groups', [
            'role_list' => $role_list,
        ]);
    }

    public function addGroup(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:2|max:50',
            ], [
                'name.required'  => '请输入权限组名称',
                'name.min'  => '权限组名称最少2个字符',
                'name.max'  => '权限组名称最长50个字符',
            ]);

            if ($validator->fails()) {
                $errors = ['success' => false,'msg'=>$validator->errors()->first()];
                return $errors;
            }

            try {
                DB::begintransaction();
                $role = new ManagerRole();
                $role->name = $request->input('name');
                if (!$role->save()) {
                    throw new \Exception('添加失败');
                }
                DB::commit();
            } catch (\Exception $e){
                DB::rollBack();
                return ['success' => false,'msg'=>$e->getMessage()];
            }
            return ['success' => true,'msg'=>'添加成功'];
        }
        return view('manager.accounts.addGroup', [
        ]);
    }

    public function editGroup(Request $request)
    {
        $role = ManagerRole::query()->find($request->input('id'));
        if (empty($role)) {
            return ['success' => false,'msg'=>'权限组不存在'];
        }
        if ($request->isMethod('post')) {

            $validator = Validator::make($request->all(), [
                'name' => 'required|min:2|max:50',
            ], [
                'name.required'  => '请输入权限组名称',
                'name.min'  => '权限组名称最少2个字符',
                'name.max'  => '权限组名称最长50个字符',
            ]);

            if ($validator->fails()) {
                $errors = ['success' => false,'msg'=>$validator->errors()->first()];
                return $errors;
            }

            try {
                DB::begintransaction();
                $role->name = $request->input('name');
                if (!$role->save()) {
                    throw new \Exception('编辑失败');
                }
                DB::commit();
            } catch (\Exception $e){
                DB::rollBack();
                return ['success' => false,'msg'=>$e->getMessage()];
            }
            return ['success' => true,'msg'=>'编辑成功'];
        }
        return view('manager.accounts.editGroup', [
            'role' => $role,
        ]);
    }

    public function modifyLimit(Request $request)
    {
        if ($request->ajax()) {
            if ($request->input('act') == 'getPermissions') {
                return $this->getPermissions($request);
            }
            if ($request->input('act') == 'setPermissions') {
                return $this->setPermissions($request);
            }
            return api_response(false, '', '接口不存在', '10110003');
        }
        $role = ManagerRole::query()->where('id', $request->input('id'))->first();
        return view('manager.accounts.auth', ['role' => $role]);
    }

    protected function getPermissions(Request $request){
        if (1){
            $auth_group_data = DB::table('manager_permission_role')->where('role_id', $request->input('id'))->get()->toArray();
            $auth_rules      = array_column($auth_group_data, 'permission_id');
            $auth_rule_list  = Permission::query()->get();

            foreach ($auth_rule_list as $key => $value) {
                in_array($value['id'], $auth_rules) && $auth_rule_list[$key]['checked'] = true;
            }
        }
        return $auth_rule_list;
    }
    protected function setPermissions(Request $request){
        $post_auth_rules = $request->input('auth_rule_ids');
        $new_role_permission = [];
        foreach ($post_auth_rules as $rule) {
            $new_role_permission[] = [
                'permission_id' => $rule,
                'role_id' => $request->input('id')
            ];
        }
        try {
            DB::begintransaction();
            $exist = DB::table('manager_permission_role')->where('role_id', $request->input('id'))->first();
            if (!empty($exist)) {
                $result = DB::table('manager_permission_role')->where('role_id', $request->input('id'))->delete();
                if (!$result) {
                    throw new \Exception("角色权限初始化失败");
                }
            }
            $result = DB::table('manager_permission_role')->insert($new_role_permission);
            if(!$result){
                throw new \Exception("角色权限赋予失败");
            }
            DB::commit();
        } catch (\Exception $e){
            DB::rollBack();
            return api_response(false, '', $e->getMessage(), '10110006');
        }
        Cache::forget('menus');
        return api_response(true);
    }
}
