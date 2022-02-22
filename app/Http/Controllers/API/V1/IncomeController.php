<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Income;
use App\Models\Category;
use Illuminate\Http\Request;

class IncomeController extends BaseController
{
    protected $income = '';

    public function __construct(Income $income, Category $category)
    {
        $this->middleware('auth:api');
        $this->income = $income;
        $this->category = $category;
    }

    public function index()
    {
        $incomes = $this->income->with('category:id,title')->latest()->paginate(10);
        $categories = $this->category->orderBy('title', 'ASC')->get()->toArray();

        $data = [
            'incomes' => $incomes,
            'categories' => $categories,
        ];

        return $this->sendResponse($data, 'Income list');
    }

    public function list()
    {
        $incomes = $this->income->pluck('title', 'id');

        return $this->sendResponse($incomes, 'Income list');
    }

    public function store(Request $request)
    {
        $request->validate([
            'source' => 'required',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'category_id' => 'required|integer',
            'description' => 'required',
        ]);

        $income = $this->income->create(
            [
                'source'      => $request->get('source'),
                'amount'      => $request->get('amount'),
                'date'        => $request->get('date'),
                'category_id' => $request->get('category_id'),
                'description' => $request->get('description'),
            ]
        );

        return $this->sendResponse($income, 'Income Created Successfully');
    }

    public function update(Request $request, $id)
    {
        $income = $this->income->findOrFail($id);

        $income->update($request->all());

        return $this->sendResponse($income, 'Income Information has been updated');
    }

    public function destroy($id)
    {
        $this->authorize('isAdmin');

        $income = $this->income->findOrFail($id);

        $income->delete();

        return $this->sendResponse($income, 'Income has been Deleted');
    }
}
