<nav class="navbar">
        <div class="navbar-left">
            <a href="#" class="logo"><img src="{{ asset('images/logolargo.png') }}" width="150" alt="Logo"></a>
            <ul class="menu">
                <li><a href="{{ route('dashboard') }}"><i class="fa-solid fa-house"></i>&nbsp;&nbsp;Inicio</a></li>
                @if(Auth::user()->rol == "admin" || Auth::user()->rol == "usuario" )
                <li class="dropdown">
                    <a href="#"><i class="fa-solid fa-list-check"></i>&nbsp;&nbsp;Solicitudes </a>
                    <div class="dropdown-content">
                    <a href="{{ route('ver.solicitudes.departamento') }}"><i class="fa-solid fa-people-group"></i>&nbsp;Totales</a>
                      
                    <a href="{{ route('ver.solicitudes.totales') }}"><i class="fa-solid fa-user-xmark"></i>&nbsp;Sin Asignar</a>
 
                        <a href="{{ route('ver.cerrados') }}"><i class="fa-solid fa-door-closed"></i>&nbsp;Cerradas</a>
    
                    </div>
                </li>
    
                <li class="dropdown">
                    <a href="{{ route('vista-missolicitudes') }}"><i class="fa-solid fa-check-to-slot"></i>&nbsp;&nbsp;Mis Solicitudes</a>
                </li>
                @endif
                <li class="dropdown">
                    <a href="{{ route('ver.peticiones') }}"><i class="fa-solid fa-paper-plane"></i>&nbsp;&nbsp;Peticiones Realizadas</a>
                </li>

                @if(Auth::user()->rol == "admin")
                <li class="dropdown">
                    <a href="#"><i class="fa-solid fa-shield-halved"></i>&nbsp;&nbsp;Administración</a>
                    <div class="dropdown-content" style="width:175px;">
                        <a href="{{ route('tickets.export') }}"> <i class="fa-solid fa-file-excel"></i> Exportar Tickets</a>
                        <a href="{{ route('listado.campana') }}"> <i class="fa-regular fa-rectangle-list"></i> Campañas</a>
                        <a href="{{ route('listado.categorias') }}"><i class="fa-solid fa-layer-group"></i> Categorías</a>
                        <a href="{{ route('listado.sedes') }}"><i class="fa-solid fa-location-dot"></i> Sedes</a>
                        <a href="{{ route('listado.tipos') }}"><i class="fa-solid fa-table"></i> Tipos</a>
                        <a href="{{ route('listado.motivos') }}"><i class="fa-solid fa-pause"></i> Motivos Pausa</a>
                    </div>
                </li>
                @endif

                @if(Auth::user()->team_id == "3" || Auth::user()->rol == "admin")
                <li class="dropdown">
                    <a href="#"><i class="fa-solid fa-chalkboard-user"></i>&nbsp;&nbsp;Formación</a>
                    <div class="dropdown-content" style="width:170px;">
                        <a href="{{ route('forms.export') }}"> <i class="fa-solid fa-file-excel"></i><span style="font-size:14px;"> Exportar Forms</span></a>
                        <a href="{{ route('ver.formacion') }}"> <i class="fa-solid fa-bullhorn"></i><span style="font-size:14px;"> Nueva Formación</span></a>
                        <a href="{{ route('listado.forms') }}"><i class="fa-regular fa-rectangle-list"></i><span style="font-size:14px;"> Solicitudes</span></a>
                    </div>
                </li>
                @endif
            </ul>
        </div>

        <ul class="menu">
        <li><a href="{{ route('ver.crearticket') }}"><i class="fa-solid fa-plus"></i>&nbsp;&nbsp;Nuevo Ticket</a></li>

            <li class="dropdown">
                <a href="#"><i class="fa-solid fa-user-astronaut"></i>&nbsp;&nbsp;{{$username}}</a>
                <div class="dropdown-content">
          
                <a href="{{ route('ver-manual') }}"><i class="fa-solid fa-circle-question"></i> Ayuda</a>
                    <a href="{{ route('signout') }}"><i class="fa-solid fa-power-off"></i> Cerrar Sesión</a>
                </div>
            </li>
        </ul>
    </nav>