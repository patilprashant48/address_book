<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Models\Contact;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class ContactController extends Controller {
  public function index(Request $r) {
    $groupsParam = $r->query('groups');
    $mode = strtolower($r->query('mode','any'));
    $query = Contact::with('groups');

    if ($groupsParam) {
      $names = array_filter(array_map('trim', explode(',', $groupsParam)));
      if ($mode === 'all') {
        foreach ($names as $name) {
          $query->whereHas('groups', fn(Builder $q)=>$q->where('name',$name));
        }
      } else {
        $query->whereHas('groups', fn(Builder $q)=>$q->whereIn('name',$names));
      }
    }
    return $query->get()->map(fn($c)=>[
      'id'=>$c->id,
      'name'=>$c->name,
      'email'=>$c->email,
      'phone'=>$c->phone,
      'company'=>$c->company,
      'groups'=>$c->groups->pluck('name')->values()
    ]);
  }

  public function store(ContactRequest $r) {
    $data = $r->validated();
    $groups = $data['groups'] ?? [];
    unset($data['groups']);
    $contact = Contact::create($data);
    if ($groups) {
      $ids = Group::whereIn('name',$groups)->pluck('id');
      $contact->groups()->sync($ids);
    }
    return $this->show($contact);
  }

  public function show(Contact $contact) {
    $contact->load('groups');
    return [
      'id'=>$contact->id,
      'name'=>$contact->name,
      'email'=>$contact->email,
      'phone'=>$contact->phone,
      'company'=>$contact->company,
      'groups'=>$contact->groups->pluck('name')->values()
    ];
  }

  public function update(ContactRequest $r, Contact $contact) {
    $data = $r->validated();
    $groups = $data['groups'] ?? null;
    unset($data['groups']);
    $contact->update($data);
    if (is_array($groups)) {
      $ids = Group::whereIn('name',$groups)->pluck('id');
      $contact->groups()->sync($ids);
    }
    return $this->show($contact);
  }

  public function destroy(Contact $contact) {
    $contact->delete();
    return response()->json(['deleted'=>true]);
  }

  public function addGroups(Request $r, Contact $contact) {
    $names = $r->input('groups',[]);
    $ids = Group::whereIn('name',$names)->pluck('id');
    $contact->groups()->syncWithoutDetaching($ids);
    return $this->show($contact);
  }

  public function removeGroup(Contact $contact, Group $group) {
    $contact->groups()->detach($group->id);
    return $this->show($contact);
  }
}