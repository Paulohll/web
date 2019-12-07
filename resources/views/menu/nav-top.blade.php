
<nav class="navbar navbar-default navbar-fixed-top be-top-header {{Session::get('color')}}">
  <div class="container-fluid">
    <div class="navbar-header"></div>
    <!-- <a href="{{ url('/bienvenido') }}" class="navbar-brand"></a>-->
    <!-- <div class="page-title"><span>{{Session::get('empresas')->NOM_EMPR}} - {{Session::get('centros')->NOM_CENTRO}}</span></div> -->
    <div class="be-right-navbar {{Session::get('color')}}">
      <ul class="nav navbar-nav navbar-right be-user-nav">
        <li><div class="page-title"><span>{{Session::get('empresas')->NOM_EMPR}} - {{Session::get('centros')->NOM_CENTRO}}</span></div></li>


        <li class="dropdown">
          <a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="dropdown-toggle"><img src="{{ asset('public/img/avatar1.png') }}" alt="Avatar"><span class="user-name">{{Session::get('usuario')->nombre}}</span></a>
          <ul role="menu" class="dropdown-menu">
            <li>
              <div class="user-info">
                <div class="user-name">{{Session::get('usuario')->nombre}}</div>
                <div class="user-position online">disponible</div>
              </div>
            </li>
            <li><a href="{{ url('/cambiarperfil/') }}"><span class="icon mdi mdi-settings"></span> Cambiar de perfil</a></li>
            <li><a href="{{ url('/cerrarsession') }}"><span class="icon mdi mdi-power"></span> Cerrar sesión</a></li>
          </ul>
        </li>
      </ul>
      <!-- <div class="panel-heading" align="center">
        <h3 class="media-heading">
            <div class="ubicacion">
            </div>
        </h3>
      </div> -->

    </div><a href="#" data-toggle="collapse" data-target="#be-navbar-collapse" class="be-toggle-top-header-menu collapsed">Opciones</a>
    <div id="be-navbar-collapse" class="navbar-collapse collapse">
      
            <ul class="nav navbar-nav">
            <li class="active"><a href="{{ url('/bienvenido') }}"><i class="icon mdi mdi-home"></i><span>&nbsp;Inicio</span></a></li>
           
            @foreach(Session::get('listamenu') as $grupo)

                <!-- @if($grupo->orden == 100)
                    <li class="divider">Reportes</li>
                @endif -->
          

                <li  class="dropdown active"  ><a href="#" @click="menu='{{$grupo->id}}'" data-toggle="dropdown" role="button" aria-expanded="false" class="dropdown-toggle"><i class="icon mdi {{$grupo->icono}}"></i><span>&nbsp;{{$grupo->nombre}}</span></a>
                  <ul role="menu" class="dropdown-menu">
                    @foreach($grupo->opcion as $opcion)
                      @if(in_array($opcion->id, Session::get('listaopciones')))
                        <li>
                          <a href="{{ url('/'.$opcion->pagina.'/'.Hashids::encode(substr($opcion->id, -8))) }}">{{$opcion->nombre}}</a>
                        </li>
                      @endif
                    @endforeach
                  </ul>
                </li>
            @endforeach
            </ul>
    </div>
  </div>
</nav>