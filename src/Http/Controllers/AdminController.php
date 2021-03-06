<?php
namespace Yoan1005\Admigen\Controllers;

use App\User;

use Image;
use Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Response;
use App\Http\Controllers\Controller;
use Yoan1005\Admigen\AdPhoto as Photo;

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
        if ($conditions = config('admigen.orderby.'.ucfirst($model).'')) {
          foreach ($conditions as $condition) {
            $datas = $datas->orderby($condition['field'], $condition['value']);
          }
        }

        if (!in_array(ucfirst($model), config('admigen.cant.paginate'))) {
          $datas = $datas->paginate(20);
        } else {
          $datas = $datas->get();
        }

        $trad = config('admigen.trads.'.ucfirst($model).'');
        $fields = config('admigen.fields.'.ucfirst($model).'');

         $can = [
          'show' => !in_array(ucfirst($model), config('admigen.cant.show')),
          'add' => !in_array(ucfirst($model), config('admigen.cant.add')),
          'edit' => !in_array(ucfirst($model), config('admigen.cant.edit')),
          'order' => !in_array(ucfirst($model), config('admigen.cant.order')),
          'delete' => !in_array(ucfirst($model), config('admigen.cant.delete')),
          ];

        $canAddName = config('admigen.models')[ucfirst($model)];

        if (view()->exists('backend.'.$model.'.liste')) {
          return view('backend.'.$model.'.liste', compact('datas', 'trad', "fields", 'can', 'canAddName', 'model'));
        } else {
          return view('admigen::table', compact('datas', 'trad', "fields", 'can', 'canAddName', 'model'));
        }
    }


    // MODELS : VIEW ADD

    // GENERIQUE
    public function new($model)
    {
        $modelI = '\App\\'.ucfirst($model) ;

      $instance = new $modelI;
      if (view()->exists('backend.'.$model.'.edit')) {
        return view('backend.'.$model.'.edit', compact('instance'));
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
        if (view()->exists('backend.'.$model.'.edit')) {
          return view('backend.'.$model.'.edit', compact('instance'));
        } else {
          return view('admigen::addedit', compact('instance'));
        }

    }

    // MODELS : STORE

    // GÉNÉRIQUE
     public function store(Request $request, $model)
     {
        $modelI = '\App\\'.ucfirst($model) ;

        $client = $modelI::firstOrCreate(['id' => $request->id]);

        if ($client->wasRecentlyCreated == true) {
          $client->save();
          $client = $modelI::where('id', $client->id)->firstOrFail();
        }

        foreach ($client->getAttributes() as $key => $value) {
          if (array_key_exists($key, $client->getOriginal())) {
            $client->$key = ($request->$key) ? $request->$key : $value;
          } else {
            unset($client->$key);
          }
        }


        $client->save();

        if ($request->textes) {
          foreach ($request->textes as $key => $value) {
            if ($value) {
              $client->saveTextes(1, $key, $value);
            }
          }
        }
        if ($request->images) {
            $client->saveImgs(1, $request->images);
        }
        if ($request->images_sup) {
            $client->saveImgs(1, $request->images_sup);
        }

        return redirect(route('admin.edit', ['model' => strtolower(class_basename($client)),'id' => $client->id ]));

        // return back();
        return redirect(route('admin.show', $model));
     }


    // MODELS : UPDATE

    // GÉNÉRIQUE
     public function update(Request $request, $model)
     {
        $modelI = '\App\\'.ucfirst($model) ;

        $client = $modelI::firstOrCreate(['id' => $request->id]);

        if ($client->wasRecentlyCreated == true) {
          $client->save();
          $client = $modelI::where('id', $client->id)->firstOrFail();
        }
        foreach ($client->getAttributes() as $key => $value) {
          if (array_key_exists($key, $client->getOriginal())) {
            $client->$key = (isset($request->$key)) ? $request->$key : $value;
          } else {
            unset($client->$key);
          }
        }

        $client->save();

        if ($request->textes) {

          foreach ($request->textes as $key => $value) {
              $client->saveTextes(1, $key, $value);
          }
        }
        if ($request->images) {
            $client->saveImgs(1, $request->images);
        }
        if ($request->images_sup) {
            $client->saveImgs(1, $request->images_sup);
        }

        return redirect(route('admin.edit', ['model' => strtolower(class_basename($client)),'id' => $client->id ]));

        return back();
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
                    $constraints->upsize();
                })
                ->save($path . '/' . $resize_name);

            Image::make($photo)
                ->resize(1600, null, function ($constraints) {
                    $constraints->aspectRatio();
                    $constraints->upsize();
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


    public function deleteImg($model, $field, $id, $img_id = null)
    {

        if ($img_id) {
          Photo::where('id', $img_id)->delete();
        } else {
          Photo::where('umodel', $model)->where('utype', $field)->where('uid', $id)->delete();
        }
        return back();
    }

    public function reordonner(Request $request) {

      $photos = $request->input('photos');
      $model_r = ucfirst($request->model);
      $model = 'App\\'.$model_r ;

      foreach ($photos as $key => $photo) {
        $image = $model::whereId($photo['id'])->first();
        foreach ($image->getAttributes() as $key => $value) {
          if (array_key_exists($key, $image->getOriginal())) {
            $image->$key = ($request->$key) ? $request->$key : $value;
          } else {
            unset($image->$key);
          }
        }
        $image->position = $photo['ordre'];
        $image->save();
      }

      return response()->json(['success' => true]);
    }

    public function updateImgType(Request $request) {


        $image = Photo::whereId($request->id)->first();
        $image->utype = $request->utype;
        $image->save();


      return response()->json(['success' => true]);
    }

    public function changeState(Request $request) {
        $model_r = ucfirst($request->model);
        $model = 'App\\'.$model_r ;
        $chat = $model::whereId($request->id)->first();
        $chat->{$request->field} = !$chat->{$request->field};
        $chat->save();

        return response()->json(['success' => true, 'moderate' => $chat->{$request->field}]);
      }

}
