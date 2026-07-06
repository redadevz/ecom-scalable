<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\BarCode\BulkDestroyBarCodeRequest;
use App\Http\Requests\CraftablePro\BarCode\CreateBarCodeRequest;
use App\Http\Requests\CraftablePro\BarCode\DestroyBarCodeRequest;
use App\Http\Requests\CraftablePro\BarCode\EditBarCodeRequest;
use App\Http\Requests\CraftablePro\BarCode\IndexBarCodeRequest;
use App\Http\Requests\CraftablePro\BarCode\StoreBarCodeRequest;
use App\Http\Requests\CraftablePro\BarCode\UpdateBarCodeRequest;
use App\Models\BarCode;
use Brackets\CraftablePro\Queries\Filters\FuzzyFilter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class BarCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexBarCodeRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $barCodesQuery = QueryBuilder::for(BarCode::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'item_id', 'bar_code', 'is_active', 'description'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'item_id', 'bar_code', 'is_active', 'description', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($barCodesQuery->select(['id'])->pluck('id'));
        }

        $barCodes = $barCodesQuery
            ->with(['item:id,name'])
            ->select('id', 'item_id', 'bar_code', 'is_active', 'description', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('barCodes_url', $request->fullUrl());

        return Inertia::render('BarCode/Index', [
            'barCodes' => $barCodes,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateBarCodeRequest $request): Response
    {
        return Inertia::render('BarCode/Create', [
            'items' => \App\Models\Item::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreBarCodeRequest $request): RedirectResponse
    {
        $barCode = BarCode::create($request->validated());
        
        return redirect()->route('craftable-pro.bar-codes.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditBarCodeRequest $request, BarCode $barCode): Response
    {
        
        return Inertia::render('BarCode/Edit', [
            'barCode' => $barCode,
            'items' => \App\Models\Item::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBarCodeRequest $request, BarCode $barCode): RedirectResponse
    {
        $barCode->update($request->validated());
        
        if (session('barCodes_url')) {
            return redirect(session('barCodes_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.bar-codes.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyBarCodeRequest $request, BarCode $barCode): RedirectResponse
    {
        
        $barCode->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyBarCodeRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    BarCode::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                BarCode::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
