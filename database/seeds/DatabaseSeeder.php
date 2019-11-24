<?php
// require base_path().'/vendor/autoload.php';

use Illuminate\Database\Seeder;
use \Illuminate\Database\Eloquent\Factory;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
    // $this->call(users::class);

  /*    factory(leck\User::class, 20)->create()->each(function ($u) {
        $u->posts()->saveMany(factory(App\Post::class)->make());
      });

      factory(leck\Book::class, 20)->create()->each(function ($b) {
        $b->posts()->saveMany(factory(App\Post::class)->make());
      });*/

      $usrsNr = 40;
      $writNr = 30;

      $tagsNr = 20;
      factory(leck\Tag::class, $tagsNr)->create();
      
      $verifTags = ['entrepreneurship', 'art', 'horror', 'drama', 'adventure', 'business', 'science'];
      foreach ($verifTags as $tagName) {
        factory(leck\Tag::class, 1)->create(['title' => $tagName, 'verified' => 1]);
      }

      $usrsObjs = factory(leck\User::class, $usrsNr)->create();
      $wrObjs = factory(leck\Writing::class, $writNr)->create();
      $wrArr = $wrObjs->toArray();
      $wrObjs2 = $wrObjs;

      $usrIds = $usrsObjs->pluck('id')->all();

      foreach ($usrsObjs as $usr) {
        $toFollowArr = [];
        for ($s=0; $s < rand(0, $usrsNr-1); $s++) {
          $tfId = rand(1, $usrsNr);
          if (in_array($tfId, $toFollowArr)) {
            continue;
          }
          factory(leck\Follow::class, 1)->create(['user_id' => $usr->id, 'followed_id' => $tfId]);
          array_push($toFollowArr, $tfId);
        }
      }

      $sectsObjs = factory(leck\Section::class, rand($writNr, $writNr*3))->create();

      foreach ($sectsObjs as $sect) {
        factory(leck\Writing_section::class, 1)->create(['writing_id' => $wrObjs2[rand(0, count($wrObjs2)-1)]->id, 'section_id' => $sect->id]);
        $paragArr = [];
        for ($s=0; $s < rand(4, 40); $s++) {
          if ($s == 0) {
            $provData = ['section_id' => $sect->id, 'position_after' => 'first0'];
          }else {
            $provData = ['section_id' => $sect->id, 'position_after' => $paragArr[$s-1]->id];
          }
          $parObj = factory(leck\Paragraph::class, 1)->create($provData);
          array_push($paragArr, $parObj[0]);
        }
      }

      foreach ($wrArr as $wr) {
        for ($s=0; $s < rand(0, 100); $s++) {
          $provData = ['writing_id' => $wr['id'], 'user_id' => $usrIds[rand(0, count($usrIds)-1)]];
          leck\Writing_traffic::create($provData);
        }

        $frstSectqury = leck\Writing_section::where('writing_id', $wr['id'])->select('section_id');
        if ($frstSectqury->exists()) {
          $sectIdsArr = $frstSectqury->get()->pluck('section_id')->all();
          foreach ($sectIdsArr as $in => $sid) {
            if ($in == 0) {
              leck\Section::where('id', $sid)->update([
                'first' => 1
              ]);
            }
            if ($in != count($sectIdsArr)-1) {
              if ($in == 0 && count($sectIdsArr) > 1) {
                $loopMax = count($sectIdsArr)-1;
                $loopRange = rand(1, $loopMax);
              }else{
                $loopMax = count($sectIdsArr)-1 - $in;
                $loopRange = rand(0, $loopMax);
              }

              if ($in != count($sectIdsArr)-1){
                for ($s=0; $s < $loopRange; $s++) {
                  $avalibelNexts = array_slice($sectIdsArr, -1*$loopMax);
                  $parObj = factory(leck\Split::class, 1)->create(['position' => $s, 'section_id' => $sid, 'next_id' => $avalibelNexts[rand(0,count($avalibelNexts)-1)]]);
                }
              }
            }
          }
        }


        $usrID = $usrIds[rand(0, count($usrIds)-2)];
        factory(leck\Writing_tag::class, rand(1, 6))->create(['writing_id' => $wr['id']]);
        factory(leck\Writing_privilege::class, 1)->create(['user_id' => $usrID, 'writing_id' => $wr['id']]);
        foreach ($usrIds as $uId) {
          if (rand(0, 5)) {
            factory(leck\Like::class, 1)->create(['user_id' => $uId, 'writing_id' => $wr['id']]);
          }
        }
      }

      for ($c=1; $c < 11; $c++) {
        factory(leck\Writing_file::class, 1)->create(['file_id' => 'c'.(string)$c]);
      }
    }
}
