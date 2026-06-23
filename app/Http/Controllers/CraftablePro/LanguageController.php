<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\Language\BulkDestroyLanguageRequest;
use App\Http\Requests\CraftablePro\Language\CreateLanguageRequest;
use App\Http\Requests\CraftablePro\Language\DestroyLanguageRequest;
use App\Http\Requests\CraftablePro\Language\EditLanguageRequest;
use App\Http\Requests\CraftablePro\Language\IndexLanguageRequest;
use App\Http\Requests\CraftablePro\Language\StoreLanguageRequest;
use App\Http\Requests\CraftablePro\Language\UpdateLanguageRequest;
use App\Models\Language;
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

class LanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexLanguageRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $languagesQuery = QueryBuilder::for(Language::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'name', 'short_name', 'description'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'name', 'short_name', 'description', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($languagesQuery->select(['id'])->pluck('id'));
        }

        $languages = $languagesQuery
            ->with([])
            ->select('id', 'name', 'short_name', 'description', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('languages_url', $request->fullUrl());

        return Inertia::render('Language/Index', [
            'languages' => $languages,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateLanguageRequest $request): Response
    {
        return Inertia::render('Language/Create', [
            
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreLanguageRequest $request): RedirectResponse
    {
        $language = Language::create($request->validated());
        
        return redirect()->route('craftable-pro.languages.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditLanguageRequest $request, Language $language): Response
    {
        
        return Inertia::render('Language/Edit', [
            'language' => $language,
            
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLanguageRequest $request, Language $language): RedirectResponse
    {
        $language->update($request->validated());
        
        if (session('languages_url')) {
            return redirect(session('languages_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.languages.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyLanguageRequest $request, Language $language): RedirectResponse
    {
        
        $language->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyLanguageRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    Language::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                Language::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
