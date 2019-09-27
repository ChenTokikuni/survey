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
				throw new \Exception('此帐号无查询功能.', 101);
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
}
