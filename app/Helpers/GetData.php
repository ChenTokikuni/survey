<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class GetData
{
	public static function inquiryOptions()
	{
		$data = [];
		$rows = \App\Models\inquiry::all();
		foreach ($rows as $row) {
			$data[$row->account] = $row->account;
		}
		return $data;
	}
	public static function memberAccountOptions()
	{
		$data = [];
		$rows = \App\Models\member::all();
		foreach ($rows as $row) {
			$data[$row->account] = $row->account;
		}
		return $data;
	}
	public static function memberQqNumberOptions()
	{
		$data = [];
		$rows = \App\Models\member::all();
		foreach ($rows as $row) {
			$data[$row->qq_number] = $row->qq_number;
		}
		return $data;
	}
}
