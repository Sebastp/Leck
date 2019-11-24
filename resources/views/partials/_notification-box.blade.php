<li class="notif-box" onmouseover="hoverNotifBox()" onmouseout="closeNotifBox()">
    <div class="notif-close" onclick="closeNotifBox(this)">
      {{-- <svg class="w-x__0" viewBox="6.712 8.781 10.611 10.612">
      <path d="M16.659,8.781l-4.643,4.643L7.375,8.781L6.712,9.443l4.643,4.643l-4.643,4.643l0.663,0.662l4.643-4.642l4.643,4.644
      l0.662-0.664l-4.643-4.643l4.644-4.643"/>
    </svg>--}}
    <svg viewBox="2.421 1.115 12.021 12.02">
      <path fill="#FFFFFF" d="M13.287,13.135L2.421,2.271l1.155-1.156l10.864,10.866L13.287,13.135z"/>
      <path fill="#FFFFFF" d="M2.421,11.981L13.285,1.116l1.156,1.155L3.577,13.135L2.421,11.981z"/>
    </svg>
  </div>
  <p class="f1_sysinf-0-5 notif-box-msg">{{$notification}}</p>
</li>
