<?php

namespace Modules\MemberType\Http\Controllers;

use Modules\Support\Http\Controllers\BackendController;
use Modules\MemberType\Http\Requests\MemberTypeValidate;
use Modules\MemberType\Models\MemberType;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class MemberTypeController extends BackendController
{
    public function index(): Response
    {
        $memberTypes = MemberType::orderBy('name')
            ->search(request('searchContext'), request('searchTerm'))
            ->paginate(request('rowsPerPage', 10))
            ->withQueryString()
            ->through(fn ($memberType) => [
                    'id' => $memberType->id,
                    'name' => $memberType->name,
                    'created_at' => $memberType->created_at->format('d/m/Y H:i') . 'h'
            ]);

        return inertia('MemberType/MemberTypeIndex', [
            'memberTypes' => $memberTypes
        ]);
    }

    public function create(): Response
    {
        return inertia('MemberType/MemberTypeForm');
    }

    public function store(MemberTypeValidate $request): RedirectResponse
    {
        MemberType::create($request->validated());

        return redirect()->route('memberType.index')
            ->with('success', 'MemberType created.');
    }

    public function edit(int $id): Response
    {
        $memberType = MemberType::find($id);

        return inertia('MemberType/MemberTypeForm', [
            'memberType' => $memberType
        ]);
    }

    public function update(MemberTypeValidate $request, int $id): RedirectResponse
    {
        $memberType = MemberType::findOrFail($id);

        $memberType->update($request->validated());

        return redirect()->route('memberType.index')
            ->with('success', 'MemberType updated.');
    }

    public function destroy(int $id): RedirectResponse
    {
        MemberType::findOrFail($id)->delete();

        return redirect()->route('memberType.index')
            ->with('success', 'MemberType deleted.');
    }
}
