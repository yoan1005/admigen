<?php
namespace Yoan1005\Admigen\Controllers;

use App\User;

use Image;
use Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Response;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Yoan1005\Admigen\AdPhoto as Photo;

class AdminController extends Controller
{

    protected function loginAdmin(Request $request)
    {
        $user = \App\Models\User::where('email', $request->email)->first();
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
        $modelI = '\App\\Models\\'.ucfirst($model) ;
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
          'export' => !in_array(ucfirst($model), config('admigen.cant.export')),
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
        $modelI = '\App\\Models\\'.ucfirst($model) ;

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
        $modelI = '\App\\Models\\'.ucfirst($model) ;

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
        $modelI = '\App\\Models\\'.ucfirst($model) ;

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
        $modelI = '\App\\Models\\'.ucfirst($model) ;

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
        $modelI = '\App\\Models\\'.ucfirst($model) ;

        $modelI::destroy($id);
        return back();
    }

    // SAVE IMG

    public function saveImg(Request $request)
    {
        $model = "App\\Models\\". ucfirst($request->model);

        $field = $request->field;

          $fichier = $model::firstOrNew(['id' => $request->id]);
            $public_path = 'userfiles/'.mb_strtolower($request->model).'s/';
            $path = public_path( $public_path );

            if (!is_dir($path)) {
                mkdir($path, 0777);
            }
            $photo = $request->file('file');
            $name = sha1(date('YmdHis') . \Str::random(30));
            $save_name = $name . '.' . $photo->getClientOriginalExtension();
            $resize_name = 'thumb_' . $name . '.' . $photo->getClientOriginalExtension();

            $imageExtensions = ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'svg', 'svgz', 'cgm', 'djv', 'djvu', 'ico', 'ief','jpe', 'pbm', 'pgm', 'pnm', 'ppm', 'ras', 'rgb', 'tif', 'tiff', 'wbmp', 'xbm', 'xpm', 'xwd'];
            $extension = $photo->getClientOriginalExtension();
            
            if(in_array($extension, $imageExtensions))
            {
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
            } else {
              $photo->move($path, $save_name);
            }

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
      $model = 'App\\Models\\'.$model_r ;

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
        $model = 'App\\Models\\'.$model_r ;
        $chat = $model::whereId($request->id)->first();
        $chat->{$request->field} = !$chat->{$request->field};
        $chat->save();

        return response()->json(['success' => true, 'moderate' => $chat->{$request->field}]);
    }

    public function exportCSV(Request $request) {
      $model_r = ucfirst($request->model);
      $model = 'App\\Models\\'.$model_r ;
      $start = Carbon::now()->subYears(10);
      $end = Carbon::now();
      $lines = new $model;

      if ($request->start_at && $request->end_at) {
        $start = Carbon::parse($request->start_at);
        $end = Carbon::parse($request->end_at);
        $lines = $lines->whereBetween('created_at', [$start, $end]);
      }
      if ($conditions = config('admigen.export.'.ucfirst($model_r).'.conditions')) {
        foreach ($conditions as $condition) {
          $lines = $lines->where($condition['field'], $condition['operator'], $condition['value']);
        }
      }
      $lines = $lines->get();

      $columns = config('admigen.export.'.ucfirst($model_r).'.columns');

      $fileName = $model_r .'_' . $start->format('Y_m_d') .'--' . $end->format('Y_m_d') . '.csv';

      $headers = array(
        "Content-type"        => "text/csv",
        "Content-Disposition" => "attachment; filename=$fileName",
        "Pragma"              => "no-cache",
        "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
        "Expires"             => "0"
      );

      $callback = function() use ($lines, $columns) {
        $file = fopen('php://output', 'w');
        fputcsv($file, $columns);

        foreach ($lines as $line) {
          foreach ($columns as $key => $value) {
            $row[$value] = $line[$key];
          }
          fputcsv($file, $row);
        }

        fclose($file);
      };

      return response()->stream($callback, 200, $headers);

    }

}
