<?php

namespace leck\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Illuminate\Http\Request;

use leck\Paragraph;

class ProcessParagraph implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $reqAdd;
    public $reqRemove;
    public $writing_id;
    private $response;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($reqestAdd, $reqestRemove, $writing_id)
    {
      $this->reqAdd = $reqestAdd;
      $this->reqRemove = $reqestRemove;
      $this->writing_id = $writing_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      if (!empty($this->reqAdd) && is_array($this->reqAdd)) {
        foreach ($this->reqAdd as $section) {
          $section_id = $section['id'];

          foreach ($section['innerElmts'] as $innerNode) {
            $paragraph = (object) [];
            $paragraph->id = $innerNode['id'];
            $paragraph->type = $innerNode['type'];
            $paragraph->content = $innerNode['content'];
            $paragraph->atribute = $innerNode['atribute'];
            $paragraph->position = $innerNode['position'];
            if (!empty($innerNode['excover'])) {
              $paragraph->excover = filter_var($innerNode['excover'], FILTER_VALIDATE_BOOLEAN);
            }else {
              $paragraph->excover = false;
            }

            Paragraph::CreateUpdate($this->writing_id, $section_id, $paragraph);
          }
        }
      }

      if (!empty($this->reqRemove) && is_array($this->reqRemove)) {
        foreach ($this->reqRemove as $paragraph_id) {
          Paragraph::remove($this->writing_id, $paragraph_id);
        }
      }
    }


    public function getResponse($req, $writing_id)
    {
      return $req;
    }
}
