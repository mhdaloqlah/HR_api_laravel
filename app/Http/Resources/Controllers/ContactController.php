<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Http\Resources\ContactCollection;
use App\Http\Resources\ContactResource;
use App\Notifications\SendContact;
use Spatie\QueryBuilder\QueryBuilder;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contacts = QueryBuilder::for(Contact::class)
        ->allowedSorts(['status','created_at'])
        ->get();
        return new ContactCollection($contacts);

    }

   

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContactRequest $request)
    {
        $validated = $request->validated();
      

        
        $contact = Contact::create($validated);
        $contact->email='info@scduae.com';
        $contact->notify(new SendContact());
        return new ContactResource($contact);
       
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
       return new  ContactResource($contact);
    }

    
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContactRequest $request, Contact $contact)
    {
        //
    }

     /**
     * Update the specified resource in storage.
     */
    public function updatestatus($id)
    {
        $contact = Contact::find($id);
        $contact->status='read';
        $contact->update();
        return response()->json([
            'success' => true, 'message' => 'Status of message updated successfully!',
            'data' => new ContactResource($contact)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();
        return response(
            [
                'message' => 'Message has been deleted',
                'message_ar'=>'تم حذف الرسالة بنجاح'
            ]
        );
        }
}
