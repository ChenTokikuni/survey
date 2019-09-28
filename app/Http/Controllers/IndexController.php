<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\GetData;

class IndexController extends Controller
{
	// 首頁
	public function index(Request $request)
	{
		try {
			return view('welcome', $this->getVarsIndex($request));
		} catch (\Exception $e) {
			echo '网页发生错误 (' . $e->getMessage() . ')';
		}
	}
	protected function getVarsIndex(Request $request)
	{
		$vars = [
		];
		return $vars;
	}
	//送出
	public function send(Request $request)
	{
		$res = ['error' => '', 'msg' => ''];

		try {
			$userid = trim($request->input('userid'));
			$qq = $request->input('qq');
			$creat_at = date('Y-m-d',strtotime($request->input('creat_at')));
			if(!is_numeric($qq)){
				$res['error'] = 106;
				$res['msg'] = '资料格式不正确';
				return response()->json($res);
			}
			$data_exists = DB::table('member')->Where('account','=',$userid)->Where('qq_number','=',$qq)->exists();
			if($data_exists){
				$data_check = DB::table('member')->select('registration_date')->Where('account','=',$userid)->Where('qq_number','=',$qq)->get()->toArray();
				if($data_check['0']->registration_date == $creat_at){
					$res['error'] = 107;
					$res['msg'] = '输入相同资料';
					return response()->json($res);
				}else{
					DB::table('member')->Where('account','=',$userid)->Where('qq_number','=',$qq)->update([
						'registration_date' => $creat_at,
					]);
				}
				$res['error'] = -1;
				$res['msg'] = 'update';
				return response()->json($res);
			}
			DB::table('member')->insert([
				'account' => $userid,
				'qq_number'=> $qq,
				'registration_date' => $creat_at,
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s')
			]);
			
			$res['error'] = -1;
			$res['msg'] = 'success';
		} catch (\Exception $e) {
			$res['error'] = $e->getCode();
			$res['msg'] = $e->getMessage();
		}

		return response()->json($res);
	}
	//查詢
	public function indexSearch(Request $request)
	{
		try {
			return view('search', $this->getVars($request));
		} catch (\Exception $e) {
			echo '网页发生错误 (' . $e->getMessage() . ')';
		}
	}
	protected function getVars(Request $request)
	{
		// 會員資料
		$member = null;
		if ($request->session()->exists('member')) {
			$member = $request->session()->get('member');
		}
		$vars = [
			// TODO: 用 cache
			'member' => $member,
		];
		return $vars;
	}
	public function login(Request $request)
	{
		$res = ['error' => '', 'msg' => ''];

		try {
			// 檢查
			if ($request->session()->exists('member')) {
				// 您已经登入
				throw new \Exception($request->session()->get('member.username'), 100);
			}

			$username = trim($request->input('username'));
			if (! $username) {
				throw new \Exception('请输入会员帐号.', 101);
			}
			$username_exists = DB::table('inquiry')->Where('account','=',$username)->exists();
			if (! $username_exists) {
				throw new \Exception('此帐号无查询功能.', 102);
			}
			// 會員資料存入 session
			$request->session()->put('member', [
				'username' => $username,
			]);

			$res['error'] = -1;
			$res['msg'] = $username;
		} catch (\Exception $e) {
			$res['error'] = $e->getCode();
			$res['msg'] = $e->getMessage();
		}

		return response()->json($res);
	}
	
	// 登出
	public function logout(Request $request)
	{
		$res = ['error' => '', 'msg' => ''];
		try {
			$request->session()->forget('member');
			/* 會連 CSRF toekn 一起刪除
			$request->session()->flush();
			*/

			$res['error'] = -1;
			$res['msg'] = 'success';
		} catch (\Exception $e) {
			$res['error'] = $e->getCode();
			$res['msg'] = $e->getMessage();
		}

		return response()->json($res);
	}
	public function search(Request $request)
	{
		$res = ['error' => '', 'msg' => ''];

		try {
			// 檢查
			if (!$request->session()->exists('member')) {
				// 您未登入
				$res['error'] = 103;
				$res['msg'] = '未登入';
				return response()->json($res);
			}
			
			$username = trim($request->input('username'));
			
			$username_exists = DB::table('member')->Where('account','=',$username)->exists();
			
			if(! $username_exists){
				$res['error'] = 104;
				$res['msg'] = '查无此帐号';
				return response()->json($res);
			}
			
			$qq_data = DB::table('member')->select('qq_number')->where('account','=',$username)->distinct()->get()->toArray();
			$registration_date = DB::table('member')->where('account','=',$username)->min('registration_date');
			
			$res['error'] = -1;
			$res['data'] = [
				'qqnumber'=>$qq_data,
				'registration_date'=>$registration_date,
			];
			$res['msg'] = '';
		} catch (\Exception $e) {
			$res['error'] = $e->getCode();
			$res['msg'] = $e->getMessage();
		}

		return response()->json($res);
	}
}
