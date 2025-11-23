<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\GroupRequest;
use App\Models\Group;

class GroupController extends Controller {
  public function index() { return Group::orderBy('name')->get(); }
  public function store(GroupRequest $r) { return Group::create($r->validated()); }
  public function show(Group $group) { return $group; }
  public function update(GroupRequest $r, Group $group) { $group->update($r->validated()); return $group; }
  public function destroy(Group $group) { $group->delete(); return response()->json(['deleted'=>true]); }
}