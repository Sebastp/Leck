<section class="columns__1 hm-feed">
  <div class="hm-feed-top">
    <h3 class="f1_sys-sect_0 hm-feed-header">Your Feed</h3>
    <div class="hm-div"></div>
  </div>

  @if (!empty($u_feed))
    <ul class="hm-feed-mid">
      @foreach ($u_feed as $index => $feed_li)
        <li class="hm-feed-item">
          <div class="feed-item-top">
            <div class="feed-item-left">
              @if (!$feed_li->visited)
                <div class="feed-item-unvisited"></div>
              @else
                <span>{{$index+1}}</span>
              @endif
            </div>

            <a href="{{ asset($feed_li->authors[0]->str_id.'/'.$feed_li->str_id) }}">
              <h4 class="feed-item-title text-over_elip">{{$feed_li->title}}</h4>
            </a>
          </div>
            <div class="feed-item-footer">
              <a href="{{ asset($feed_li->authors[0]->str_id) }}">
                <span class="f1_usrname_1 feed-item-date">{{$feed_li->authors[0]->nickname}}</span>
              </a>
              <span class="f1_sysinf-0">{{$feed_li->published_at_parsed}}</span>
            </div>
        </li>
      @endforeach
    </ul>

    <div class="hm-feed-bottom">
      <span class="f1_content-1"><a href="{{asset('feed')}}">See more</a></span>
    </div>
  @endif
</section>
