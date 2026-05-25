<?php

namespace App\Http\Controllers;

use App\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::query();
        
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }
        
        if ($request->has('from_date')) {
            $query->where('expense_date', '>=', $request->from_date);
        }
        if ($request->has('to_date')) {
            $query->where('expense_date', '<=', $request->to_date);
        }

        $expenses = $query->orderBy('expense_date', 'desc')->paginate($request->get('per_page', 15));
        
        return response()->json($expenses);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category'     => 'required|string',
            'amount'       => 'required|numeric',
            'description'  => 'nullable|string',
            'expense_date' => 'required|date',
        ]);

        $expense = Expense::create($request->all());

        return response()->json([
            'message' => 'Expense recorded successfully',
            'expense' => $expense
        ], 201);
    }

    public function show($id)
    {
        $expense = Expense::findOrFail($id);
        return response()->json(['expense' => $expense]);
    }

    public function update(Request $request, $id)
    {
        $expense = Expense::findOrFail($id);
        
        $request->validate([
            'category'     => 'sometimes|required|string',
            'amount'       => 'sometimes|required|numeric',
            'description'  => 'nullable|string',
            'expense_date' => 'sometimes|required|date',
        ]);

        $expense->update($request->all());

        return response()->json([
            'message' => 'Expense updated successfully',
            'expense' => $expense
        ]);
    }

    public function destroy($id)
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();
        
        return response()->json(['message' => 'Expense deleted successfully']);
    }
}
