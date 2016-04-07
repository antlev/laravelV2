@content('nav')
<!-- Menu de navigation -->
<div class="col-lg-2 col-md-2 col-sm-offset-2 col-sm-2">
  <div class="dropdown ">
    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Navigation Forum
      <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
      <li class="dropdown-header" >
        <a style="font-weight:bold" href="{{url('forum')}}">Index</a>
      </li>
      @foreach($categories as $cats)  <!-- On affiche les sous_catégories -->
        <li class="dropdown-header">
          <a style="font-weight:bold" href="{{url('forum/'.$cats->cat_id.'/')}}">
            <h4>{{$cats->cat_nom}}</h4>
          </a>
        </li>
        @foreach($topics as $topic_as) <!-- On affiche les catégories -->
          @if($topic_as->topic_cat==$cats->cat_id)
            <li>
              <a href="{{url('forum/'.$cats->cat_id.'/'.$topic_as->topic_id)}}">
                <h5>{{$topic_as->topic_titre}}</h5>
              </a>
            </li>
          @endif
        @endforeach
      @endforeach
    </ul>
  </div>
</div>
@endcontent