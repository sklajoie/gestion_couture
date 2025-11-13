<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('Accueil')}}" class="brand-link">
      <img src="/assets/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">{{config('app.name')}}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="/assets/dist/img/user.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="{{route('Accueil')}}" class="d-block">{{Auth::user()->name ?? ''}}</a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item  {{ request()->routeIs('Accueil*') ? 'menu-open' : '' }}">
            <a href="{{route('Accueil')}}" class="nav-link {{ request()->routeIs('Accueil*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                TABLEAU BORD
              </p>
            </a>
          </li>
          
          <li class="nav-item 
          {{ request()->routeIs('Fiche-Ventes*') ? 'menu-open' : '' }}
          {{ request()->routeIs('Ventes*') ? 'menu-open' : '' }}
          {{ request()->routeIs('Registre-vente*') ? 'menu-open' : '' }}
           ">
            <a href="#" class="nav-link 
            {{ request()->routeIs('Fiche-Ventes*') ? 'active' : '' }}
            {{ request()->routeIs('Ventes*') ? 'active' : '' }}
            {{ request()->routeIs('Registre-vente*') ? 'active' : '' }}
             ">
              <i class="nav-icon fas fa-cart-plus"></i>
              <p>
                VENTES
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('Fiche-Ventes.index')}}" class="nav-link {{ request()->routeIs('Fiche-Ventes*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>FICHE VENTE</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('Ventes')}}" class="nav-link {{ request()->routeIs('Ventes*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>LISTE VENTE</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('Registre-vente')}}" class="nav-link {{ request()->routeIs('Registre-vente*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>REGISTRE VENTE</p>
                </a>
              </li>
             
             
            </ul>
          </li>
          <li class="nav-item 
          {{ request()->routeIs('Commandes*') ? 'menu-open' : '' }}
          {{ request()->routeIs('Registre-Commande*') ? 'menu-open' : '' }}
           ">
            <a href="#" class="nav-link
             {{ request()->routeIs('Commandes*') ? 'active' : '' }}
             {{ request()->routeIs('Registre-Commande*') ? 'active' : '' }}
              ">
              <i class="nav-icon fas fa-credit-card "></i>
              <p>
                COMMANDES
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('Commandes.index')}}" class="nav-link {{ request()->routeIs('Commandes*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Commandes</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('Registre-Commande')}}" class="nav-link {{ request()->routeIs('Registre-Commande*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Registre</p>
                </a>
              </li>
             
             
            </ul>
          </li>
          <li class="nav-item 
          {{ request()->routeIs('Approvisions*') ? 'menu-open' : '' }}
          {{ request()->routeIs('Registre-Approvisions*') ? 'menu-open' : '' }}
            ">
            <a href="#" class="nav-link 
            {{ request()->routeIs('Approvisions*') ? 'active' : '' }}
            {{ request()->routeIs('Registre-Approvisions*') ? 'active' : '' }}
              ">
              <i class="nav-icon fas fa-anchor "></i>
              <p>
                APPROVISIONS
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
            
              <li class="nav-item">
                <a href="{{route('Approvisions.index')}}" class="nav-link {{ request()->routeIs('Approvisions*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Liste</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('Registre-Approvisions')}}" class="nav-link {{ request()->routeIs('Registre-Approvisions*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Registre</p>
                </a>
              </li>
             
             
            </ul>
          </li>
          <li class="nav-item {{ request()->routeIs('Fournisseur*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->routeIs('Fournisseur*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-handshake"></i>
              <p>
                FOURNISSEURS
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('Fournisseur.index')}}" class="nav-link {{ request()->routeIs('Fournisseur*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Fournisseurs</p>
                </a>
              </li>
             
            </ul>
          </li>
          <li class="nav-item {{ request()->routeIs('Produits*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->routeIs('Produits*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-server "></i>
              <p>
                PRODUITS
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('Produits.index')}}" class="nav-link {{ request()->routeIs('Produits*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Produits</p>
                </a>
              </li>
             
            </ul>
          </li>
          <li class="nav-item 
          {{ request()->routeIs('Caisses*') ? 'menu-open' : '' }}
          {{ request()->routeIs('Ressources*') ? 'menu-open' : '' }}
          {{ request()->routeIs('Natures*') ? 'menu-open' : '' }}
            ">
            <a href="#" class="nav-link 
            {{ request()->routeIs('Caisses*') ? 'active' : '' }}
            {{ request()->routeIs('Ressources*') ? 'active' : '' }}
            {{ request()->routeIs('Natures*') ? 'active' : '' }}
              ">
              <i class="nav-icon fas fa-university"></i>
              <p>
                CAISSES
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('Caisses.index')}}" class="nav-link {{ request()->routeIs('Caisses*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Caisses</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('Ressources.index')}}" class="nav-link {{ request()->routeIs('Ressources*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Ressources</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('Natures.index')}}" class="nav-link {{ request()->routeIs('Natures*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Natures</p>
                </a>
              </li>
             
            </ul>
          </li>
        
          <li class="nav-item {{ request()->routeIs('Users*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->routeIs('Users*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-users"></i>
              <p>
                UTILISATEURS
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item ">
                <a href="{{route('Users.index')}}" class="nav-link {{ request()->routeIs('Users*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Utilisateurs</p>
                </a>
              </li>
             
            </ul>
          </li>
          
         
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>