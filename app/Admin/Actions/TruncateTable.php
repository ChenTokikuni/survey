<?php

namespace App\Admin\Actions;

use Encore\Admin\Actions\Action;
use Illuminate\Http\Request;

class TruncateTable extends Action
{

	protected $selector = '.truncate-table';

	protected $table;
	protected $whitelist = ['inquiry','member'];

	public function __construct($table = null)
	{
		if ($table) {
			$this->table = $table;
		}

		parent::__construct();
	}

	public function handle(Request $request)
	{
		try {
			$table = $request->input('table');
			if (! $table) {
				throw new \Exception('未指定资料表.');
			}

			if (! in_array($table, $this->whitelist)) {
				throw new \Exception('指定的资料表未在白名单之中.');
			}

			\Illuminate\Support\Facades\DB::table($table)->truncate();

			return $this->response()->success('清空完成.')->refresh();
		} catch (\Exception $e) {
			return $this->response()->error('发生错误: ' . $e->getMessage());
		}
	}

	public function dialog()
	{
		$this->confirm('确定 ?');
	}

	public function html()
	{
		return <<<HTML
<a class="btn btn-sm btn-danger truncate-table" data-table="{$this->table}"><i class="fa fa-trash"></i>&nbsp;&nbsp;清空资料</a>
HTML;
	}

}
