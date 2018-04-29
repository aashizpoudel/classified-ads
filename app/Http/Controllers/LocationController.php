<?php

namespace App\Http\Controllers;

use App\City;
use App\Country;
use App\State;
use Illuminate\Http\Request;

use App\Http\Requests;
use Yajra\Datatables\Datatables;

class LocationController extends Controller
{

    public function countries(){
        $title = trans('app.countries');
        $countries = Country::all();
        return view('admin.countries', compact('title', 'countries'));
    }

    public function getCountriesData(){
        return Datatables::of(Country::select('id', 'country_name', 'country_code'))
            ->removeColumn('id')->make();
    }

    public function stateList(){
        $title = trans('app.states');
        $countries = Country::all();
        return view('admin.states', compact('title', 'countries'));
    }

    public function saveState(Request $request){
        $rules = [
            'country_id' => 'required',
            'state_name' => 'required',
        ];
        $this->validate($request, $rules);

        $duplicate = State::whereStateName($request->state_name)->count();
        if ($duplicate > 0){
            return back()->with('error', trans('app.state_exists_in_db'));
        }

        $data = [
            'state_name' => $request->state_name,
            'country_id' => $request->country_id,
        ];

        State::create($data);
        return back()->with('success', trans('app.state_created'));
    }


    public function getStatesData(){
        $states = State::select('states.id', 'state_name', 'country_name', 'country_id')->leftJoin('countries', 'countries.id','=','states.country_id');

        return Datatables::of($states)
            ->addColumn('actions',function($state){
                $html = '<a href="'.route('edit_state', $state->id).'" class="btn btn-primary"><i class="fa fa-edit"></i> </a>';
                $html .= '<a href="javascript:;" data-id="'.$state->id.'" class="btn btn-danger deleteState"><i class="fa fa-trash"></i> </a>';
                return $html;
            })
            ->removeColumn('id', 'country_id')->make(true);
    }


    public function stateEdit($id){
        $state = State::find($id);
        $title = trans('app.edit_state');
        $countries = Country::all();
        return view('admin.state_edit', compact('title', 'countries', 'state'));
    }
    
    public function stateEditPost(Request $request, $id){
        $state = State::find($id);

        $rules = [
            'country_id' => 'required',
            'state_name' => 'required',
        ];
        $this->validate($request, $rules);

        $duplicate = State::whereStateName($request->state_name)->where('id', '!=', $id)->count();
        if ($duplicate > 0){
            return back()->with('error', trans('app.state_exists_in_db'));
        }

        $data = [
            'state_name' => $request->state_name,
            'country_id' => $request->country_id,
        ];

        $state->update($data);
        return back()->with('success', trans('app.state_updated'));
    }

    public function stateDestroy(Request $request){
        $state = State::find($request->state_id);
        if ($state){
            $state->delete();
        }
        return ['success'=>1, 'msg'=>trans('app.state_deleted')];
    }

    public function cityList(){
        $title = trans('app.cities');
        $countries = Country::all();
        return view('admin.cities', compact('title', 'countries'));
    }

    public function getCityData(){
        $cities = City::select('cities.id', 'city_name', 'state_name', 'state_id', 'country_name', 'country_id')->leftJoin('states', 'states.id','=','cities.state_id')->leftJoin('countries', 'countries.id','=','states.country_id')->orderBy('city_name', 'asc');

        return Datatables::of($cities)
            ->addColumn('actions',function($city){
                $html = '<a href="'.route('edit_city', $city->id).'" class="btn btn-primary"><i class="fa fa-edit"></i> </a>';
                $html .= '<a href="javascript:;" data-id="'.$city->id.'" class="btn btn-danger deleteCity"><i class="fa fa-trash"></i> </a>';
                return $html;
            })
            ->removeColumn('id', 'state_id','country_id')->make(true);
    }


    public function saveCity(Request $request){
        $rules = [
            'country' => 'required',
            'state' => 'required',
            'city_name' => 'required',
        ];
        $this->validate($request, $rules);

        $duplicate = City::whereCityName($request->city_name)->count();
        if ($duplicate > 0){
            return back()->with('error', trans('app.city_exists_in_db'));
        }

        $data = [
            'city_name' => $request->city_name,
            'state_id' => $request->state,
        ];

        City::create($data);
        return back()->with('success', trans('app.city_created'));
    }

    public function cityEdit($id){
        $city = City::find($id);

        if (!$city)
            return view('admin.error.error_404');

        $title = trans('app.edit_city');
        $countries = Country::all();
        
        $states = null;
        if ($city->state) 
            $states = State::whereCountryId($city->state->country_id)->get();

        return view('admin.city_edit', compact('title', 'countries', 'city', 'states'));
    }

    public function cityEditPost(Request $request, $id){
        $city = City::find($id);

        $rules = [
            'country'       => 'required',
            'state'         => 'required',
            'city_name'     => 'required',
        ];
        $this->validate($request, $rules);

        $duplicate = City::whereCityName($request->city_name)->where('id', '!=', $id)->count();
        if ($duplicate > 0){
            return back()->with('error', trans('app.state_exists_in_db'));
        }

        $data = [
            'city_name' => $request->city_name,
            'state_id'     => $request->state,
        ];
        $city->update($data);

        return back()->with('success', trans('app.city_updated'));
    }

    public function cityDestroy(Request $request){
        $state = City::find($request->city_id);
        if ($state){
            $state->delete();
        }
        return ['success'=>1, 'msg'=>trans('app.city_deleted')];
    }


}
