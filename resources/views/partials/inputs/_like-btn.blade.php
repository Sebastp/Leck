@if (empty($usr_likes))
  @php
    $usr_likes = 0;
  @endphp
@endif
<div class="like-container like-active" data-likesnr='{{$usr_likes}}' @if (!empty($counter_el))data-counter-id="{{$counter_el}}"@endif oncontextmenu="return false;" data-need-auth>
  <div class="like-btn">
    <svg viewBox="0 0 1114 1027">
    		<path stroke-width="80" fill-rule="evenodd" clip-rule="evenodd" d="M343.1,34.9c-167.3,0-305.9,140.5-305.9,307.4
    			c0,116.2,65.6,193.3,141.7,269.5c126.7,126.8,253.3,253.6,380,380.3c71.2-71.6,142.5-143.2,213.7-214.7
    			c72.1-72.5,141-153.8,219-220.1c40.1-34.1,68.8-102.8,79.5-155c18.3-89.4-8.1-182.4-64.1-253.6c-109.4-139-324.2-151.8-450-26
    			C502.8,66.1,420.6,34.9,343.1,34.9z"/>
    </svg>

    <div class="like-btn-fullbcg"></div>
  </div>
  <div class="like-ratebar">
    <div class="like-ratebar-positive"></div>
  </div>
  <span class="like-usr__likes f1_sysinf-0-5" title="Your likes">{{$usr_likes}}</span>
</div>
