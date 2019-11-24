<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
  <head>
    @include('partials._head')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{$writing->title or 'Untitled'}} - {{config('app.name')}}</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/writing.css') }}">
  </head>
  <body>
    @include('partials.topbars._topbar', ['type' => 'writing'])


    <div class="down-container">
      <ul class="notif-container scrlbar-2">
        @if (!empty($sys_notif))
          @foreach ($sys_notif as $notif)
            @include('partials._notification-box', ['notification' => $notif])
          @endforeach
        @endif
      </ul>


      <article id="w-cont-global">
        <div id="w-cont-topmsg">
          <p id="w-cont-fail" class="f3_sub_msg" style="display: none">Sorry, we couldn't load previous section</p>
          <div id="w-cont-load" class="w-load-crcl" style="display: none">
            <div class="loader">
              <svg class="circular-loader" viewBox="25 25 50 50" >
                <circle class="loader-path" cx="50" cy="50" r="20" fill="none" stroke="#b8b9bc" stroke-width="5" />
              </svg>
            </div>
          </div>
        </div>


        <div class="w-article-head" @if (!empty($sections) && !$sections[0]->isFirst)style="display: none"@endif>
          <header id="w-top">
            @if (!empty($writing->cover) && $writing->cover->position == 'top')
              <figure contenteditable="false" id="w-cover__top" class="writing-cover">
                <div class="w-innerf-cont" contenteditable="false" data-size="{{$writing->cover->attr}}">
                  <div class="prog-load" data-size="{{$writing->cover->attr}}"><img src="{{ $writing->cover->path }}" class="prog-load-elem" contenteditable="false"></div>
                  <img class="w-innerf" data-src="{{ $writing->cover->path }}" data-file-id="{{$writing->cover->id}}" alt="cover photo">
                </div>
              </figure>
            @endif


            <div class="w-cont-mid w-article-head" id="w-author-top" @if (!empty($sections) && !$sections[0]->isFirst)style="display: none"@endif>
              @if (!empty($authors))
                @foreach ($authors as $author)
                  <div class="w-author_single">
                    <a href="{{ asset($author->str_id) }}">
                      <img src="{{$author->avatar_path}}?s=45" alt="avatar" class="avatar-regular__big w-author-avatar">
                    </a>

                    <span class="f1_usrname_0 w-author__name">
                      <a href="{{ asset($author->str_id) }}">
                        {{ $author->nickname}}
                      </a>
                    </span>
                  </div>
                @endforeach
              @else
                <div class="w-author_single">
                  <img src="{{ asset('images/avatar.jpg') }}" alt="avatar" class="avatar-small">
                  <span class="f1_usrname_0 w-author__name">Unknown</span>
                </div>
              @endif
            </div>

            @if (!empty($writing->cover) && $writing->cover->position == 'mid')
              <figure contenteditable="false" id="w-cover__mid" class="writing-cover">
                <div class="w-innerf-cont" contenteditable="false" data-size="{{$writing->cover->attr}}">
                  <div class="prog-load" data-size="{{$writing->cover->attr}}"><img src="{{ $writing->cover->path }}" class="prog-load-elem" contenteditable="false"></div>
                  <img class="w-innerf" data-src="{{ $writing->cover->path }}" data-file-id="{{$writing->cover->id}}" alt="cover photo">
                </div>
              </figure>
            @endif


            <h1 class="f3_title_0 w-cont-mid" id="w-title">{{$writing->title or 'Untitled'}}</h1>

            @if (!empty($writing->cover) && $writing->cover->position == 'down')
              <figure contenteditable="false" id="w-cover__down" class="writing-cover">
                <div class="w-innerf-cont" contenteditable="false" data-size="{{$writing->cover->attr}}">
                  <div class="prog-load" data-size="{{$writing->cover->attr}}"><img src="{{ $writing->cover->path }}" class="prog-load-elem" contenteditable="false"></div>
                  <img class="w-innerf" data-src="{{ $writing->cover->path }}" data-file-id="{{$writing->cover->id}}" alt="cover photo">
                </div>
              </figure>
            @endif
          </header>
        </div>



        <div id="w-content-cont" class="writing-cont">
          @include('layouts.writings._inook-section', ['sections' => $sections])
        </div>

        <footer class="w-cont-footer w-cont-mid">
          @if (!empty($authors))
            <div class="w-footer-row w-footer-authors">
              @foreach ($authors as $author)
                <div class="w-author_single">
                  <a href="{{ asset($author->str_id) }}">
                    <img src="{{$author->avatar_path}}?s=45" alt="avatar" class="avatar-regular__big w-footer-avatar">
                  </a>

                  <div class="w-footer-author">
                    <span class="w-footer-author-name f1_usrname_2">
                      <a href="{{ asset($author->str_id) }}">
                        {{ $author->nickname}}
                      </a>
                    </span>
                    <time class="elem-footer-sub f1_sysinf-0-5" datetime="{{$writing->published_at}}">{{$writing->published_at_parsed}}</time>
                  </div>
                  @if (Auth::id() != $author->id)
                    <button role="button" data-need-auth action-follow="{{$authors[0]->id}}" class="btn-action__0 @if($author->u_following)btn-action-half @endif w-follow-btn"></button>
                    @endif
                  </div>
                @endforeach
            </div>
          @endif

          <div class="w-footer-row w-footer-actions">
            <div class="w-footer-rate w-footer-action">
              <span class="f1_sys-sect_0__small w-like-counter" id="wrt-likes" data-base-likes="{{$writing->likes - $writing->usr_likes}}">{{$writing->likes}}</span>
              @include('partials.inputs._like-btn', ['usr_likes' => $writing->usr_likes, 'counter_el' => 'wrt-likes'])
            </div>
          </div>

          @if (!empty($writing->tags))
            <div class="w-footer-tags w-footer-row">
              <ul class="wf-tags">
                @foreach ($writing->tags as $tag)
                  <li class="w-tag-elem f1_sysinf-0-5"><a href="{{asset('tag/'.str_replace_array(' ', ['_'], $tag))}}"><span class="w-tag-elembox">{{$tag}}</span></a></li>
                @endforeach
              </ul>
            </div>
          @endif
        </footer>
      </article>

      <div class="div-line"></div>

      @include('layouts.writings._under-writing', ['sections' => $sections, 'comments' => $comments])
    </div>


    @include('partials._scripts')

    @include('auth._login-modal')

    <script type="text/javascript" src="/js/autosize.min.js"></script>
    {{-- <script type="text/javascript" src="/js/editor.js"></script> --}}
    <script type="text/javascript" src="/js/writing.js"></script>

    <script type="text/javascript">
      var writing_id = "{{$writing->id}}";
      var csrf_token = "{{csrf_token()}}";
      autosize($('.txtarea-resize'));

      if (document.getElementsByClassName('like-btn').length && userLogged) {
        document.getElementsByClassName('like-btn')[0].addEventListener('mousedown', function(e){
          likeAction(e, writing_id);
        });

        document.getElementsByClassName('like-btn')[0].addEventListener('mouseup', function(){
          clearInterval(likeTimer);
        });
      }


      window.onmousewheel = function(e){
        if (e.deltaY < 0 && $(window).scrollTop() == 0) {
          var ltstSec = document.getElementsByClassName('w-section-cont')[0];
          if (ltstSec.getAttribute('isfrst') == 0) {
            var ltstSecId = ltstSec.getAttribute('sec-id');
            loadPrevSections(ltstSecId);
          }
        }
      }
    </script>
  </body>
</html>
