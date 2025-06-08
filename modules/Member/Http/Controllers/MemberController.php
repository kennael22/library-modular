<?php

namespace Modules\Member\Http\Controllers;

use Modules\Support\Http\Controllers\BackendController;
use Modules\Member\Http\Requests\MemberValidate;
use Modules\Member\Models\Member;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class MemberController extends BackendController
{
    public function index(): Response
    {
        $members = Member::orderBy('name')
            ->search(request('searchContext'), request('searchTerm'))
            ->paginate(request('rowsPerPage', 10))
            ->withQueryString()
            ->through(fn ($member) => [
                    'id' => $member->id,
                    'name' => $member->name,
                    'created_at' => $member->created_at->format('d/m/Y H:i') . 'h'
            ]);

        return inertia('Member/MemberIndex', [
            'members' => $members
        ]);
    }

    public function create(): Response
    {
        return inertia('Member/MemberForm');
    }

    public function store(MemberValidate $request): RedirectResponse
    {
        Member::create($request->validated());

        return redirect()->route('member.index')
            ->with('success', 'Member created.');
    }

    public function edit(int $id): Response
    {
        $member = Member::find($id);

        return inertia('Member/MemberForm', [
            'member' => $member
        ]);
    }

    public function update(MemberValidate $request, int $id): RedirectResponse
    {
        $member = Member::findOrFail($id);

        $member->update($request->validated());

        return redirect()->route('member.index')
            ->with('success', 'Member updated.');
    }

    public function destroy(int $id): RedirectResponse
    {
        Member::findOrFail($id)->delete();

        return redirect()->route('member.index')
            ->with('success', 'Member deleted.');
    }
}
