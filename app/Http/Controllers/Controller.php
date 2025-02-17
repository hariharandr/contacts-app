<?php

namespace App\Http\Controllers;

use App\Services\ContactService;
use Illuminate\Routing\Controller;
use App\Http\Requests\StoreContactRequest;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    protected $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    public function store(StoreContactRequest $request)
    {
        $validatedData = $request->validated();
        $contact = $this->contactService->createContact($validatedData);
        return response()->json($contact, 201);
    }
}
