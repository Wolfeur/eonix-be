<?php

namespace App\Http\Controllers;

use App\Http\Resources\PersonResource;
use App\Models\Person;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class PersonController extends Controller {
	/**
	 * Display a listing of the resource.
	 */
	public function index() {
		return response()->json(Person::all());
	}

	public function search(Request $request) {
		$firstNameFilter = '%' . preg_replace('([%_])', '\\$1' ,$request->first_name ?? '') . '%';
		$lastNameFilter = '%' . preg_replace('([%_])', '\\$1' ,$request->last_name ?? '') . '%';

		$persons = Person::where('first_name', 'LIKE', $firstNameFilter)->where('last_name', 'LIKE', $lastNameFilter)->get();

		return response(PersonResource::collection($persons));
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request) {
		try {
			$person = new Person();
			$person->fill($request->all());
			$person->save();

			return response(new PersonResource($person), 201);
		} catch (QueryException $exception) {
			return response(['error' => $exception->errorInfo], 400);
		}
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Person $person) {
		return response(new PersonResource($person));
		/*$person = Person::find($id);
		if (empty($person)) {
			return response()->json([
				'message' => 'Person not found'
			], 404);
		}

		return response()->json($person);*/
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, Person $person) {
		try {
			$person->fill($request->all());
			$person->save();

			return response(new PersonResource($person));
		} catch (QueryException $exception) {
			return response(['error' => $exception->errorInfo], 400);
		}
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Person $person) {
		try {
			$person->delete();

			return response(NULL, 204);
		} catch (QueryException $exception) {
			return response(['error' => $exception->errorInfo], 400);
		}
	}
}
