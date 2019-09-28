<?php

namespace App\Admin\Extensions;

use Encore\Admin\Grid\Exporters\ExcelExporter; 
use Maatwebsite\Excel\Concerns\WithMapping;

class MemberExporter extends ExcelExporter implements WithMapping
{
    protected $fileName = '资料导出.xls';

    protected $columns = [
        'account'   => '会员帐号',
        'qq_number' => 'QQ号',
		'registration_date' => '注册日期',
    ];
	
	public function map($user) : array
    {
        return [
			$user->account,
			$user->qq_number,
			$user->registration_date,
        ];
    }
}