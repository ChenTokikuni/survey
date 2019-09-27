<?php

namespace App\Imports;

use App\Models\member;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class MemberImport implements ToCollection
{
    /**
     * @param array $row
     *
     * @return User|null
     */
	public function collection(Collection $rows)
    {
		$sql = "INSERT IGNORE INTO member ( account, qq_number, registration_date, created_at, updated_at ) VALUES ";
		$values = array_fill(0, count($rows), "( ?, ?, ?, NOW(), NOW() )");
		$binds = [];
		foreach ($rows as $row) {
			for ($i = 0; $i < 2; $i++) {
				$binds[]= $row[0];
				$binds[]= $row[1];
				$binds[]= $row[2];
			}
		}
		$sql .= implode(', ', $values);
		\Illuminate\Support\Facades\DB::insert($sql, $binds);
    }
	public function chunkSize(): int
    {
        return 1000;
    }
}