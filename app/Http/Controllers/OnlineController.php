<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OnlineController extends Controller
{
    public function updateValue(Request $request)
    {
        $validatedData = $request->validate([
            'table_name' => 'required|string',
            'field_to_update' => 'required|string',
            'new_value' => 'required|string',
            'where_condition' => 'nullable|string',
        ]);

        try {
            // Construct the raw SQL query
            $query = "
                UPDATE \"public\".\"{$validatedData['table_name']}\" 
                SET \"{$validatedData['field_to_update']}\" = '{$validatedData['new_value']}'
                WHERE ctid IN (
                    SELECT ctid 
                    FROM \"public\".\"{$validatedData['table_name']}\" 
                    WHERE \"uid\"='{$validatedData['where_condition']}'
                    LIMIT 1 FOR UPDATE
                )
                RETURNING \"uid\", \"status\";
            ";
            echo $query;exit();
            \Log::info("Executing query: " . $query);

            // Execute the query
            $result = DB::select($query);
           //     echo $query;exit();
            // Return a success response with the returned data
            return response()->json(['message' => 'Value updated successfully', 'data' => $result[0] ?? null], 200);
        } catch (\Exception $e) {
            \Log::error('Database update failed', ['exception' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to update value'], 500);
        }
    }

    public function getValue(Request $request)
    {
        $validatedData = $request->validate([
            'table_name' => 'required|string',
            'field_to_get' => 'required|string',
            'where_condition' => 'nullable|string',
        ]);

        try {
            $query = "
                SELECT {$validatedData['field_to_get']}
                FROM {$validatedData['table_name']}
            ";
                $query .= "
                    WHERE {$validatedData['where_condition']} IS NOT NULL
                ";
   
            $result = DB::select($query);
            echo $query;exit();
            return response()->json(['value' => $result[0] ?? null], 200);
        } catch (\Exception $e) {
            \Log::error('Database query failed', ['exception' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to retrieve value'], 500);
        }
    }
}
