<?php

namespace App\Imports;

use App\Models\inquiry;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class InquiryImport implements ToCollection
{
    /**
     * @param array $row
     *
     * @return User|null
     */
    public function collection(Collection $rows)
    {
		$sql = "INSERT IGNORE INTO inquiry ( account, created_at, updated_at ) VALUES ";
		$values = array_fill(0, count($rows), "( ?, NOW(), NOW() )");
		$binds = [];
		foreach ($rows as $row) {
			$binds[]= $row[0];
		}
		$sql .= implode(', ', $values);
		//$sql .= " ON DUPLICATE KEY UPDATE account =VALUES(account),updated_at =NOW()";
		\Illuminate\Support\Facades\DB::insert($sql, $binds);
    }
	public function chunkSize(): int
    {
        return 1000;
    }
}