<?php
namespace Yoan1005\Admigen\Controllers;

use App\User;

use Image;
use Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Response;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{

    protected function loginAdmin(Request $request)
    {
        $user = \App\User::where('email', $request->email)->first();
        if ($user) {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                Auth::login($user);

                return redirect(route('admin.dashboard'));
            }  else {
                return redirect(route('admin.loginPage'))->with('errors', 'Mot de passe incorrect.');
            }
        } else {
            return redirect(route('admin.loginPage'))->with('errors', 'Vous n\'êtes pas autorisé à accéder à ces pages.');
        }
    }

    public function showDashboard()
    {

        return view('admigen::dashboard');
    }

    // MODELS : SHOW
    public function show($model)
    {
        $modelI = '\App\\'.ucfirst($model) ;
        $datas = $modelI::whereNotNull('id');
        if ($conditions = config('admigen.conditions.'.ucfirst($model).'')) {
          foreach ($conditions as $condition) {
            $datas = $datas->where($condition['field'], $condition['operator'], $condition['value']);
          }
        }
        $datas = $datas->paginate(20);

        $trad = config('admigen.trads.'.ucfirst($model).'');
        $fields = config('admigen.fields.'.ucfirst($model).'');

         $can = [
        'show' => false,
        'add' => true,
        'edit' => true,
        'delete' => true,
        ];

        $canAddName = config('admigen.models')[ucfirst($model)];

        return view('admigen::table', compact('datas', 'trad', "fields", 'can', 'canAddName', 'model'));
    }


    // MODELS : VIEW ADD

    // GENERIQUE
    public function new($model)
    {
        $modelI = '\App\\'.ucfirst($model) ;

      $instance = new $modelI;
      if (view()->exists('backend.addedit.edit-'.$model.'')) {
        return view('backend.addedit.edit-'.$model.'', compact('instance'));
      } else {
        return view('admigen::addedit', compact('instance'));
      }
    }


    // MODELS : VIEW EDIT

    // GENERIQUE
    public function edit($model, $id)
    {
        $modelI = '\App\\'.ucfirst($model) ;

        $instance = $modelI::find($id);
        if (view()->exists('backend.addedit.edit-'.$model.'')) {
          return view('backend.addedit.edit-'.$model.'', compact('instance'));
        } else {
          return view('admigen::addedit', compact('instance'));
        }

    }

    // MODELS : STORE

    // GÉNÉRIQUE
     public function store(Request $request, $model)
     {
        $modelI = '\App\\'.ucfirst($model) ;

        $client = $modelI::firstOrNew(['id' => $request->id]);

        $client->fill($request->all());
        $client->save();

        return redirect(route('admin.show', $model));
     }


    // MODELS : UPDATE

    // GÉNÉRIQUE
     public function update(Request $request, $model)
     {
        $modelI = '\App\\'.ucfirst($model) ;

        $client = $modelI::firstOrNew(['id' => $request->id]);

        $client->fill($request->all());
        $client->save();

        return redirect(route('admin.show', $model));
     }


    // MODELS : DELETE
    public function delete($model, $id)
    {
        $modelI = '\App\\'.ucfirst($model) ;

        $modelI::destroy($id);
        return back();
    }

    // SAVE IMG

    public function saveImg(Request $request)
    {
        $model = "App\\". ucfirst($request->model);

        $field = $request->field;

          $fichier = $model::firstOrNew(['id' => $request->id]);
            $public_path = 'userfiles/'.mb_strtolower($request->model).'s/';
            $path = public_path( $public_path );

            if (!is_dir($path)) {
                mkdir($path, 0777);
            }
            $photo = $request->file('file');
            $name = sha1(date('YmdHis') . str_random(30));
            $save_name = $name . '.' . $photo->getClientOriginalExtension();
            $resize_name = 'thumb_' . $name . '.' . $photo->getClientOriginalExtension();

            Image::make($photo)
                ->resize(250, null, function ($constraints) {
                    $constraints->aspectRatio();
                })
                ->save($path . '/' . $resize_name);

            Image::make($photo)
                ->resize(1600, null, function ($constraints) {
                    $constraints->aspectRatio();
                })
                ->save($path . '/' . $save_name);

            // $photo->move($path, $save_name);


            $fichier->$field = '/'.$public_path.$save_name;
            $fichier->save();

        return Response::json([
            'model_id' => $fichier->id,
            'message' => 'Image saved Successfully'
        ], 200);
    }

    public function deleteImg($model, $field, $id)
    {
        $model = "App\\". ucfirst($model);
        $instance = $model::whereId($id)->firstOrFail();
        $instance->$field = NULL;
        $instance->save();

        return back();
    }

    public function reordonner(Request $request) {

      $photos = $request->input('photos');
      $model_r = ucfirst($request->model);
      $model = 'App\\'.$model_r ;

      foreach ($photos as $key => $photo) {
        $image = $model::whereId($photo['id'])->first();
        $image->position = $photo['ordre'];
        $image->save();
      }

      return response()->json(['success' => true]);
    }


}
